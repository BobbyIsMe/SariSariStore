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
$cart_id = 0;
$variation_id = 0;
$stock_qty = 0;

if (!isset($variation_id) && !isset($product_id) || !is_numeric($product_id) || !isset($quantity) || !is_numeric($quantity) || $quantity <= 0) {
    echo json_encode(['status' => 400, 'message' => 'Invalid product or quantity.']);
    exit();
}

$price = 0;
$stmt = $con->prepare("
SELECT v.variation_id, p.stock_qty, v.price
FROM Products p
JOIN Variations v ON p.variation_id=v.variation_id
WHERE product_id = ?");
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
SELECT cart_id, c.item_qty
FROM Carts 
WHERE user_id = ? AND status = 'Pending'");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cart_id = $row['cart_id'];
    $stmt->close();

    $stmt = $con->prepare("
    SELECT item_qty
    FROM Cart_Items 
    WHERE cart_id = ? AND product_id = ? AND variation_id = ?");
    $stmt->bind_param('iii', $cart_id, $product_id, $variation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $quantity += $row['item_qty'];
        if ($quantity > $stock_qty) {
            echo json_encode(['status' => 400, 'message' => 'Insufficient stock.']);
            exit();
        }
    }

} else {
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
VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE item_qty = ?, subtotal = ?");
$stmt->bind_param('iiiidid', $cart_id, $product_id, $variation_id, $quantity, $subtotal, $quantity, $subtotal);
$stmt->execute();
$stmt->close();

echo json_encode([
    'status' => 200,
    'message' => 'Product added to cart successfully.'
]);
