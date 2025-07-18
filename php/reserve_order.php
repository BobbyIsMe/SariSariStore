<?php
// Reserve items from cart
include_once('db_connect.php');
include_once('check_product_validation.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$carts = checkProductValidation($con, " ca.type = 'cart' AND ca.user_id = ?", 'i', [$user_id]);

if ($carts === null || empty($carts)) {
    echo json_encode(['status' => 404, 'message' => 'No items in cart.']);
    exit();
}

foreach ($carts as $cart_id => $items) {
    foreach ($items as $product_id => $item) {
        if ($item['quantity'] < 0) {
            echo json_encode(['status' => 400, 'message' => 'Insufficient stock for product: ' . $item['item_name']]);
        } else {
            continue;
        }
        exit();
    }
}

$stmt = $con->prepare("
UPDATE Carts SET type = 'order', status = 'pending', date_time_created = NOW()
WHERE user_id = ? AND type = 'cart' AND status IN ('pending', 'rejected')");
$stmt->bind_param('i', $user_id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 200, 'message' => 'Items reserved successfully.']);
} else {
    echo json_encode(['status' => 500, 'message' => 'Failed to reserve items.']);
}
$stmt->close();
