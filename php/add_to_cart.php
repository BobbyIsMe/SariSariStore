<?php
// Add a product to the cart
include_once("db_connect.php");

$user_id = $_SESSION['user_id'] ?? null;
if (!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$product_id = $_POST['product_id'];
$variation_id = $_POST['variation_id'];
$quantity = $_POST['quantity'];
$cart_id = 0;
$variation_id = 0;
$stock_qty = 0;

if (!isset($variation_id) && !isset($product_id) || !is_numeric($product_id) || !isset($quantity) || !is_numeric($quantity)) {
    echo json_encode(['status' => 400, 'message' => 'Invalid product or quantity.']);
    exit();
}

$stmt = $con->prepare("
SELECT 1 FROM Carts WHERE user_id = ? AND status IN('pending','approved') AND type = 'order' LIMIT 1");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
    echo json_encode(['status' => 400, 'message' => 'You already have a reservation.']);
    exit();
}

$price = 0;
$stmt = $con->prepare("
SELECT v.variation_id, p.stock_qty, p.price
FROM Products p
JOIN Variations v ON p.product_id=v.product_id
WHERE p.product_id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['status' => 404, 'message' => 'Product or variation not found.']);
    exit();
}
$row = $result->fetch_assoc();
if ($row['stock_qty'] < $quantity) {
    echo json_encode(['status' => 400, 'message' => 'Insufficient stock.']);
    exit();
}
$price = $row['price'];
$stock_qty = $row['stock_qty'];
$variation_id = $row['variation_id'];
$stmt->close();



// $stmt = $con->prepare("
// SELECT variation_id
// FROM Variations
// WHERE variation_id = ? AND product_id = ?");
// $stmt->bind_param('ii', $variation_id, $product_id);
// $stmt->execute();
// $result = $stmt->get_result();
// if ($result->num_rows === 0) {
//     echo json_encode(['status' => 404, 'message' => 'Variation not found.']);
//     exit();
// }
// $row = $result->fetch_assoc();
// $variation_id = $row['variation_id'];
// $stmt->close();

$stmt = $con->prepare("
SELECT cart_id
FROM Carts
WHERE user_id = ? AND status IN('pending', 'rejected') AND type = 'cart'");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
    $cart_id = $row['cart_id'];
    $stmt->close();

    $stmt = $con->prepare("
    SELECT c.item_qty
    FROM Cart_Items c
    JOIN Carts ca ON c.cart_id = ca.cart_id
    WHERE ca.cart_id = ? AND c.product_id = ? AND c.variation_id = ? AND ca.type = 'cart' AND ca.status IN ('pending', 'rejected')");
    $stmt->bind_param('iii', $cart_id, $product_id, $variation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (($row['item_qty'] + $quantity > $stock_qty && $quantity > 0) || ($row['item_qty'] + $quantity <= 0 && $quantity < 0)) {
            echo json_encode(['status' => 400, 'message' => 'Insufficient stock.']);
            exit();
        }
    }
} else {
    if ($quantity <= 0) {
        echo json_encode(['status' => 400, 'message' => 'Quantity must be above zero.']);
        exit();
    }
    $stmt = $con->prepare("
    INSERT INTO Carts (user_id)
    VALUES (?)");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $cart_id = $con->insert_id;
    $stmt->close();
}

$subtotal = $price * $quantity;

$stmt = $con->prepare("
INSERT INTO Cart_Items (cart_id, product_id, variation_id, item_qty, subtotal)
VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE item_qty = item_qty + ?, subtotal = subtotal + ?");
$stmt->bind_param('iiiidid', $cart_id, $product_id, $variation_id, $quantity, $subtotal, $quantity, $subtotal);
$stmt->execute();
$stmt->close();

echo json_encode([
    'status' => 200,
    'message' => 'Product added to cart successfully.'
]);
