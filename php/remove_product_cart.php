<?php
include_once('db_connect.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!isset($user_id)) {
    echo json_encode(['status' => 401, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$product_id = $_POST['product_id'] ?? null;
if (!isset($product_id)) {
    echo json_encode(['status' => 400, 'message' => 'Product ID is required.']);
    exit();
}

$stmt = $con->prepare("
    DELETE FROM Cart_Items
    WHERE cart_id = (SELECT cart_id FROM Carts WHERE user_id = ? AND type = 'cart' AND status IN ('pending', 'rejected')) 
    AND product_id = ?
");
$stmt->bind_param('ii', $user_id, $product_id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 200, 'message' => 'Product removed from cart successfully.']);
} else {
    echo json_encode(['status' => 404, 'message' => 'Product not found in cart.']);
}