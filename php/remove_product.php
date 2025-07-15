<?php
include_once('db_connect.php');
include_once('admin_status.php');
requireAdmin($con, 'inventory');

$product_id = $_POST['product_id'] ?? null;
if (!isset($product_id)) {
    echo json_encode(['status' => 400, 'message' => 'Product ID is required.']);
    exit();
}

$con->begin_transaction();

try {
    $stmt = $con->prepare("DELETE FROM Products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    if (!$stmt->execute()) {
        if (strpos($stmt->error, 'foreign key constraint fails') !== false) {
            throw new Exception('Foreign key constraint violation.');
        } else {
            throw new Exception($stmt->error);
        }
    }
    else
    if ($stmt->affected_rows === 0) {
        $stmt->close();
        throw new Exception('Product not found or could not be removed.');
    }
    $stmt->close();
    echo(json_encode(['status' => 200, 'message' => 'Product removed successfully.']));
    // $orders = checkProductValidation($con, " ca.type = 'order' AND ca.status = 'pending'", '', []);
    // $cart_id_list = [];

    // foreach ($orders as $cart_id_invalid => $items) {
    //     foreach ($items as $product_id => $item) {
    //         if ($item['quantity'] <= 0) {
    //             $cart_id_list[$cart_id_invalid] = true;
    //             break;
    //         }
    //     }
    // }

    // $cart_id_list = array_keys($cart_id_list);

    // if (!empty($cart_id_list)) {
    //     $placeholders = implode(',', array_fill(0, count($cart_id_list), '?'));
    //     $stmt = $con->prepare("
    //         UPDATE Carts 
    //         SET status = 'rejected' 
    //         WHERE cart_id IN ($placeholders) AND type = 'order'
    //     ");
    //     $stmt->bind_param(str_repeat('i', count($cart_id_list)), ...$cart_id_list);
    //     $stmt->execute();
    //     $stmt->close();

    //     $date_time = date('Y-m-d H:i:s');
    //     $notifications = [];

    //     foreach ($cart_id_list as $id) {
    //         $notifications[] = [
    //             'cart_id' => $id,
    //             'message' => 'Your reservation has been rejected due to invalid items.',
    //             'date_time_created' => $date_time,
    //             'status' => 'rejected'
    //         ];
    //     }

    //     notifyUsers($con, $notifications);
    // }

    $con->commit();
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(['status' => 500, 'message' => 'Transaction failed: ' . $e->getMessage()]);
}
