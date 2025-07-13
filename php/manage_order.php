<?php
include_once('db_connect.php');
include_once('admin_status.php');
include_once('check_product_validation.php');
requireAdmin($con, 'staff');

$cart_id = $_POST['cart_id'] ?? null;
$date_time_deadline = $_POST['date_time_deadline'] ?? null;
$status = $_POST['status'] ?? null;

if (!isset($cart_id)) {
    echo json_encode(['status' => 400, 'message' => 'Cart ID is required.']);
    exit();
}
if (!isset($status) || !in_array($status, ['approved', 'rejected', 'closed'])) {
    echo json_encode(['status' => 400, 'message' => 'Invalid status.']);
    exit();
}

if (!isset($date_time_deadline)) {
    echo json_encode(['status' => 400, 'message' => 'Invalid date time.']);
    exit();
}

$orders = [];
$stock_list = [];
if ($status == 'approved') {
    $orders = checkProductValidation($con, " ca.type = 'order' AND ca.cart_id = ? AND ca.status = 'pending'", 'i', $cart_id);
    if ($orders === null) {
        echo json_encode(['status' => 404, 'message' => 'Order not found.']);
        exit();
    }

    foreach ($carts[$cart_id] as $product_id => $item) {
        if ($product_id == null) {
            json_encode(['status' => 404, 'message' => 'Product is unavailable.']);
        } else

            if ($item['variation_id'] == null) {
            json_encode(['status' => 404, 'message' => 'Variation is unavailable.']);
        } else

            if ($item['quantity'] <= 0) {
            json_encode(['status' => 400, 'message' => 'Insufficient stock for product ID: ' . $item['product_id']]);
        } else {
            $stock_list[] = [
                'product_id' => $item['product_id'],
                'variation_id' => $item['variation_id'],
                'quantity' => $item['quantity']
            ];
            continue;
        }
        exit();
    }

    unset($orders[$cart_id]);
}

if ($status == 'approved') {

    $con->begin_transaction();

    try {
        $stmt = $con->prepare("
        UPDATE Carts 
        SET status = 'approved', date_time_deadline = ?
        WHERE cart_id = ? AND type = 'order'
        ");
        $stmt->bind_param('si', $status, $cart_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Automatically reject orders with invalid items
            $cart_id_list = [];

            foreach ($orders as $cart_id_invalid => $items) {
                foreach ($items as $product_id => $item) {
                    if ($item['quantity'] <= 0 || $item['variation_id'] == null || $product_id == null) {
                        $cart_id_list[$cart_id_invalid] = true;
                        break;
                    }
                }
            }

            $cart_id_list = array_keys($cart_id_list);
            $placeholders = "";
            if (!empty($cart_id_list)) {
                $placeholders = implode(',', array_fill(0, count($cart_id_list), '?'));
                $stmt = $con->prepare("
                UPDATE Carts 
                SET status = 'rejected' 
                WHERE cart_id IN ($placeholders) AND type = 'order'
                ");
                $stmt->bind_param(str_repeat('i', count($cart_id_list)), ...$cart_id_list);
                $stmt->execute();
            }

            $date_time = date('Y-m-d H:i:s');

            //TODO: Notify users about order rejection and approval
            if (!empty($cart_id_list)) {
                $notifications = [];

                $notifications[] = [
                    'cart_id' => $cart_id,
                    'message' => 'Your reservation has been approved. Receive the item before ' . $date_time_deadline . '.',
                    'date_time_created' => $date_time
                ];
                foreach ($cart_id_list as $id) {
                    $notifications[] = [
                        'cart_id' => $id['cart_id'],
                        'message' => 'Your reservation has been rejected due to invalid items.',
                        'date_time_created' => $date_time
                    ];
                }

                $placeholders = [];
                $values = '';
                $params = [];

                foreach ($notifications as $notif) {
                    $placeholders[] = '(?, ?, ?)';
                    $values .= 'iss';
                    $params[] = $notif['cart_id'];
                    $params[] = $notif['message'];
                    $params[] = $notif['date_time_created'];
                }

                $stmt = $con->prepare("INSERT INTO Notifications (cart_id, message, date_time_created) VALUES " . implode(', ', $placeholders));
                $stmt->bind_param($values, ...$params);
                $stmt->execute();
            } else {
                $stmt = $con->prepare("INSERT INTO Notifications (cart_id, message, date_time_created) VALUES (?, ?, ?)");
                $stmt->bind_param('iss', 'Your reservation has been approved. Receive the item before ' . $date_time_deadline . '.', $date_time);
                $stmt->execute();
            }

            // Update stock quantities of products  
            $cases = "";
            $conditions = [];

            foreach ($items as $item) {
                $product_id = (int)$item['product_id'];
                $variation_id = (int)$item['variation_id'];
                $quantity = (int)$item['quantity'];

                $cases .= "WHEN product_id = $product_id AND variation_id = $variation_id THEN $quantity\n";
                $conditions[] = "($product_id, $variation_id)";
            }

            $stmt = $con->query("
            UPDATE Products
            SET stock_qty = CASE $cases 
            END
            WHERE (product_id, variation_id) IN (" . implode(',', $conditions) . ")
            ");

            if ($con->affected_rows <= 0) {
                throw new Exception('Failed to update stock quantities.');
            }

            $con->commit();
            echo json_encode(['status' => 200, 'message' => 'Reservation successful.']);
        } else {
            throw new Exception('Order not found.');
        }
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status' => 500, 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
}
$stmt->close();
