<?php
// Get ordered items of user by User ID
include_once("db_connect.php");
$user_id = $_SESSION['user_id'];
$order_items = [];

if (!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$stmt = $con->prepare("
SELECT p.product_id, p.item_name, cs.subcategory, p.brand, c.item_qty, c.subtotal, c.variation_id
FROM Carts ca
JOIN Cart_Items c ON ca.cart_id = c.cart_id
JOIN Products p ON c.product_id = p.product_id
JOIN Categories cs ON p.category_id = cs.category_id
WHERE ca.user_id = ?  AND ca.type = 'order' AND NOT ca.status = 'closed'");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $order_items[] = [
            'product_id' => $row['product_id'],
            'item_name' => $row['item_name'],
            'subcategory' => $row['subcategory'],
            'brand' => $row['brand'],
            'item_qty' => $row['item_qty'],
            'subtotal' => $row['subtotal'],
            'variation_id' => $row['variation_id']
        ];
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 404, 'message' => 'No items in reservation.']);
    exit();
}

$stmt = $con->prepare("
SELECT total 
FROM Carts 
WHERE user_id = ? AND type = 'order' AND NOT status = 'closed'");
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
    'message' => 'Reservation items retrieved successfully.',
    'order_items' => $order_items,
    'total' => $total
);

echo json_encode($myObj);
