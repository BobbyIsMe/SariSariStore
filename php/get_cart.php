<?php 
// Get cart items of user by User ID
include_once("db_connect.php");
$user_id = $_SESSION['user_id'];
$cart_items = [];

if (!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$stmt = $con->prepare("
SELECT p.prod_id, p.item_name, p.subcategory, p.brand, c.item_qty, c.price
FROM cart ca
JOIN cart_items c ON ca.cart_id = c.cart_id
JOIN products p ON c.prod_id = p.prod_id
WHERE ca.user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = [
            'prod_id' => $row['prod_id'],
            'item_name' => $row['item_name'],
            'subcategory' => $row['subcategory'],
            'brand' => $row['brand'],
            'item_qty' => $row['item_qty'],
            'price' => $row['price']
        ];
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 404, 'message' => 'No items in cart.']);
    exit();
}

$stmt = $con->prepare("
SELECT total FROM cart WHERE user_id = ?");
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
    'total' => $total
);

echo json_encode($myObj);
?>