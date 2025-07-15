<?php
// Get ordered items of user by User ID
include_once("db_connect.php");
$user_id = $_SESSION['user_id'];
$order_items = [];
$cart_id = null;
$status = null;
$date_time_created = null;
$date_time_deadline = null;
$date_time_received = null;
$total = 0;

if (!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$stmt = $con->prepare("
SELECT ca.cart_id, ca.status, ca.date_time_deadline, ca.date_time_created, ca.date_time_received, 
p.product_id, p.image, p.item_name, cs.subcategory, p.brand, c.item_qty, c.subtotal, v.variation_name, p.stock_qty
FROM Carts ca
JOIN Cart_Items c ON ca.cart_id = c.cart_id
JOIN Products p ON c.product_id = p.product_id
JOIN Categories cs ON p.category_id = cs.category_id
JOIN Variations v ON p.variation_id = v.variation_id
WHERE ca.user_id = ?  AND ca.type = 'order' AND NOT ca.status = 'closed'");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $order_items[] = [
            'product_id' => $row['product_id'],
            'image' => $row['image'],
            'item_name' => $row['item_name'],
            'subcategory' => $row['subcategory'],
            'brand' => $row['brand'],
            'item_qty' => $row['item_qty'],
            'subtotal' => $row['subtotal'],
            'variation_name' => $row['variation_name'],
            'stock_qty' => $row['stock_qty']
        ];
        $total += $row['subtotal'];
    }
    $cart_id = $row['cart_id'];
    $status = $row['status'];
    $date_time_created = $row['date_time_created'];
    $date_time_deadline = $row['date_time_deadline'];
    $date_time_received = $row['date_time_received'];
    $stmt->close();
} else {
    echo json_encode(['status' => 404, 'message' => 'No items in reservation.']);
    exit();
}

$myObj = array(
    'status' => 200,
    'message' => 'Reservation items retrieved successfully.',
    'order_items' => $order_items,
    'cart_id' => $cart_id,
    'status' => $status,
    'date_time_created' => $date_time_created,
    'date_time_deadline' => $date_time_deadline,
    'date_time_received' => $date_time_received,
    'total' => $total
);

echo json_encode($myObj);
