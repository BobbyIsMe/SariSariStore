<?php 
// Get cart items of user by User ID
include_once("db_connect.php");
$user_id = $_SESSION['user_id'];
$cart_items = [];
$cart_id = null;

if (!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$stmt = $con->prepare("
SELECT ca.cart_id, p.product_id, p.image, p.item_name, cs.subcategory, p.brand, c.item_qty, c.subtotal, v.variation_name, (CASE WHEN p.stock_qty > 0 THEN 'In Stock' ELSE 'Out of Stock' END) AS stock_status
FROM Carts ca
JOIN Cart_Items c ON ca.cart_id = c.cart_id
JOIN Products p ON c.product_id = p.product_id
JOIN Categories cs ON p.category_id = cs.category_id
JOIN Variations v ON p.variation_id = v.variation_id
WHERE ca.user_id = ? AND ca.type = 'cart'");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = [
            'product_id' => $row['product_id'],
            'image' => $row['image'],
            'item_name' => $row['item_name'],
            'subcategory' => $row['subcategory'],
            'brand' => $row['brand'],
            'item_qty' => $row['item_qty'],
            'subtotal' => $row['subtotal'],
            'variation_name' => $row['variation_name'],
            'stock_status' => $row['stock_status']
        ];
    }
    $cart_id = $row['cart_id'];
    $stmt->close();
} else {
    echo json_encode(['status' => 404, 'message' => 'No items in cart.']);
    exit();
}

$stmt = $con->prepare("
SELECT total 
FROM Carts 
WHERE user_id = ? AND type = 'cart'");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total = 0;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total = $row['total'];
}

$stmt->close();
$myObj = array(
    'status' => 200,
    'message' => 'Cart items retrieved successfully.',
    'cart_items' => $cart_items,
    'cart_id' => $cart_id,
    'total' => $total
);

echo json_encode($myObj);
?>