<?php 
// Add a product to the cart
include_once("db_connect.php");

$user_id = $_POST['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$product_id = $_POST['product_id'];
$variation_id = $_POST['variation_id'];
$quantity = $_POST['quantity'];
$cart_id = null;

if(!isset($variation_id) && !isset($product_id) || !is_numeric($product_id) || !isset($quantity) || !is_numeric($quantity) || $quantity <= 0) {
    echo json_encode(['status' => 400, 'message' => 'Invalid product or quantity.']);
    exit();
}

$price = 0;
$stmt = $con->prepare("
SELECT stock_qty, price
FROM Products 
WHERE product_id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['status' => 404, 'message' => 'Product not found.']);
    exit();
}
$row = $result->fetch_assoc();
if ($row['stock_qty'] < $quantity) {
    echo json_encode(['status' => 400, 'message' => 'Insufficient stock.']);
    exit();
}
$price = $row['price'];
$stmt->close();

$stmt = $con->prepare("
SELECT variation_id
FROM Variations
WHERE variation_id = ? AND product_id = ?");
$stmt->bind_param('ii', $variation_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['status' => 404, 'message' => 'Variation not found.']);
    exit();
}

$stmt = $con->prepare("
SELECT cart_id
FROM Carts 
WHERE user_id = ? AND status = 'Pending'");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cart_id = $row['cart_id'];
} else {
    $stmt = $con->prepare("
    INSERT INTO Carts (user_id)
    VALUES (?)");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $cart_id = $con->insert_id;
}

$stmt->close();
$subtotal = $price * $quantity;

$stmt = $con->prepare("
INSERT INTO Cart_Items (cart_id, product_id, variation_id, item_qty, subtotal)
VALUES (? ?, ?, ?, ?) ON DUPLICATE KEY UPDATE item_qty = item_qty + ?, subtotal = subtotal + ?");
$stmt->bind_param('iiiidid', $cart_id, $product_id, $variation_id, $quantity, $subtotal, $quantity, $subtotal);
$stmt->execute();
$stmt->close();

echo json_encode([
    'status' => 200,
    'message' => 'Product added to cart successfully.'
]);
?>