<?php
include_once('db_connect.php');
include_once('admin_status.php');
include_once('check_product_validation.php');
include_once('notify_users.php');
requireAdmin($con, 'staff');

$cart_id = $_POST['cart_id'] ?? null;
$status = $_POST['status'] ?? null;

if (!isset($cart_id)) {
    echo json_encode(['status' => 400, 'message' => 'Cart ID is required.']);
    exit();
}
if (!isset($status) || !in_array($status, ['approved', 'rejected', 'closed'])) {
    echo json_encode(['status' => 400, 'message' => 'Invalid status.']);
    exit();
}

$stmt = $con->prepare("
SELECT status
FROM Carts
WHERE cart_id = ? AND type = 'order'
");
$stmt->bind_param('i', $cart_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($stmt->affected_rows == 0) {
    echo json_encode(['status' => 400, 'message' => 'Order not found.']);
    exit();
}

$old_status = $row['status'];
if ($old_status == 'closed') {
    echo json_encode(['status' => 400, 'message' => 'Order is already closed.']);
    exit();
}

$orders = [];
$stock_list = [];
$date_time = date('Y-m-d H:i:s');

if ($status == 'closed') {

    $con->begin_transaction();

    try {
        $stmt = $con->prepare("
        UPDATE Carts 
        SET status = 'closed', date_time_received = ? 
        WHERE cart_id = ? AND type = 'order'
        ");
        $stmt->bind_param('si', $date_time, $cart_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $stmt->close();

            $message = 'Your reservation has been closed.';
            $stmt = $con->prepare("
            INSERT INTO Notifications (cart_id, message, date_time_created, status)
            VALUES (?, ?, NOW(), 'closed')
            ");
            $stmt->bind_param('is', $cart_id, $message);
            $stmt->execute();
            $stmt->close();

            $con->commit();
            echo json_encode(['status' => 200, 'message' => 'Order closed and notification sent.']);
        } else {
            throw new Exception('No changes made.');
        }
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status' => 500, 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
} else if ($status == 'rejected') {
    $con->begin_transaction();

    //TODO: Restock the products
    try {
        $stmt = $con->prepare("
        SELECT c.product_id, (p.stock_qty + c.item_qty) AS quantity
        FROM Carts ca
        LEFT JOIN Cart_Items c ON ca.cart_id = c.cart_id
        LEFT JOIN Products p ON c.product_id = p.product_id
        LEFT JOIN Variations v ON c.variation_id = v.variation_id
        WHERE ca.cart_id = ? AND ca.type = 'order'
        ");
        $stmt->bind_param('i', $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            if ($old_status == 'approved') {
                while ($row = $result->fetch_assoc()) {
                    $stock_list[] = [
                        'product_id' => $row['product_id'],
                        'quantity' => $row['quantity']
                    ];
                }
                $stock_cases = "";
                $sales_cases = "";
                $conditions = [];

                foreach ($stock_list as $item) {
                    $p = (int) $item['product_id'];
                    $quantity = (int)$item['quantity'];

                    $stock_cases .= "WHEN product_id = $p THEN $quantity\n";
                    $sales_cases .= "WHEN product_id = $p THEN total_sales + (stock_qty - $quantity)\n";
                    $conditions[] = "$p";
                }

                $stmt = $con->query("
            UPDATE Products
            SET 
            total_sales = CASE $sales_cases END,
            stock_qty = CASE $stock_cases END
            WHERE (product_id) IN (" . implode(',', $conditions) . ")
            ");

                if ($con->affected_rows <= 0) {
                    throw new Exception('Failed to update stock quantities.');
                }
            }

            $stmt = $con->prepare("
            UPDATE Carts 
            SET status = 'rejected', date_time_deadline = NULL
            WHERE cart_id = ? AND type = 'order'
            ");
            $stmt->bind_param('i', $cart_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $stmt->close();

                $stmt = $con->prepare("
            INSERT INTO Notifications (cart_id, message, date_time_created, status)
            VALUES (?, ?, ?, 'rejected')
            ");
                $message = 'Your reservation has been rejected.';
                $stmt->bind_param('iss', $cart_id, $message, $date_time);
                $stmt->execute();
            } else {
                throw new Exception('Order not found.');
            }
        }
        $con->commit();
        echo json_encode(['status' => 200, 'message' => 'Order for cart ' . $cart_id . ' is rejected.']);
    } catch (Exception $e) {
        $con->rollback();
        echo json_encode(['status' => 500, 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }
} else if ($status == 'approved') {
    $date_time_deadline = $_POST['date_time_deadline'] ?? null;
    if (!isset($date_time_deadline)) {
        echo json_encode(['status' => 400, 'message' => 'Invalid date time.']);
        exit();
    }

    $datetime = DateTime::createFromFormat('Y-m-d\TH:i', $date_time_deadline);

    if (!$datetime) {
        echo json_encode(['status' => 400, 'message' => 'Invalid date time format.']);
        exit();
    }

    $datetime = new DateTime($date_time_deadline);
    $date_format = $datetime->format('m/d/y h:i A');

    $currentDateTime = new DateTime();
    if ($datetime <= $currentDateTime) {
        echo json_encode(['status' => 400, 'message' => 'Deadline must be a future date and time.']);
        exit();
    }

    $con->begin_transaction();

    try {
        $orders_cur = checkProductValidation($con, " ca.type = 'order' AND ca.status IN ('pending', 'rejected') AND ca.cart_id = ?", 'i', [$cart_id]);
        if ($orders_cur === null || empty($orders_cur)) {
            echo json_encode(['status' => 404, 'message' => 'Order not found.']);
            exit();
        }

        foreach ($orders_cur[$cart_id] as $product_id => $item) {
            if ($product_id == null || $item['variation_id']  == null) {
                throw new Exception('Invalid item.');
            }
            if ($item['quantity'] < 0) {
                throw new Exception('Insufficient stock.');
            }
            $stock_list[] = [
                'product_id' => $product_id,
                'quantity' => $item['quantity']
            ];
        }

        $stmt = $con->prepare("
        UPDATE Carts 
        SET status = 'approved', date_time_deadline = ?
        WHERE cart_id = ? AND type = 'order'
        ");
        $stmt->bind_param('si', $date_time_deadline, $cart_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Update stock quantities of products  
            $stock_cases = "";
            $sales_cases = "";
            $conditions = [];

            foreach ($stock_list as $item) {
                $p = $item['product_id'];
                $quantity = (int)$item['quantity'];

                $stock_cases .= "WHEN product_id = $p THEN $quantity ";
                $sales_cases .= "WHEN product_id = $p THEN total_sales + (stock_qty - $quantity) ";
                $conditions[] = "$p";
            }

            $stmt = $con->query("
            UPDATE Products
            SET 
            total_sales = CASE $sales_cases END,
            stock_qty = CASE $stock_cases END
            WHERE (product_id) IN (" . implode(',', $conditions) . ")
            ");

            // Automatically reject orders with invalid items
            $orders = checkProductValidation($con, " ca.type = 'order' AND ca.status = 'pending'", '', []);
            $cart_id_list = [];

            if ($orders !== null || !empty($orders))
                foreach ($orders as $cart_id_invalid => $items) {
                    foreach ($items as $product_id => $item) {
                        if ($item['quantity'] < 0 || $item['variation_id'] == null || $product_id == null) {
                            $cart_id_list[$cart_id_invalid] = true;
                            break;
                        }
                    }
                }

            //TODO: Notify users about order rejection and approval
            if (!empty($cart_id_list)) {

                $cart_id_list = array_keys($cart_id_list);
                $placeholders = "";

                $placeholders = implode(',', array_fill(0, count($cart_id_list), '?'));
                $stmt = $con->prepare("
                UPDATE Carts 
                SET type = 'cart', status = 'rejected' 
                WHERE cart_id IN ($placeholders) AND type = 'order'
                ");
                $stmt->bind_param(str_repeat('i', count($cart_id_list)), ...$cart_id_list);
                $stmt->execute();

                $notifications = [];

                $notifications[] = [
                    'cart_id' => $cart_id,
                    'message' => 'Your reservation has been approved. Receive the item before ' . $date_format . '.',
                    'date_time_created' => $date_time,
                    'status' => 'approved'
                ];
                foreach ($cart_id_list as $id) {
                    $notifications[] = [
                        'cart_id' => $id,
                        'message' => 'Your reservation has been rejected due to invalid items.',
                        'date_time_created' => $date_time,
                        'status' => 'rejected'
                    ];
                }

                notifyUsers($con, $notifications);
            } else {
                $stmt = $con->prepare("INSERT INTO Notifications (cart_id, message, date_time_created, status) VALUES (?, ?, ?, 'approved')");
                $message = 'Your reservation has been approved. Receive the item before ' . $date_format . '.';
                $stmt->bind_param('iss', $cart_id, $message, $date_time);
                $stmt->execute();
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
