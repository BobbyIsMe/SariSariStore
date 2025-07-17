<?php
// Get ordered items of all customers
include_once("db_connect.php");
include_once("admin_status.php");
requireAdmin($con, 'staff');

$orders = [];
$order_items = [];
$values = [];
$params = '';
$filter = " WHERE ca.type = 'order' ";
$results = 0;
$total = 5;

$status = $_GET['status'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if (!empty($status)) {
    $params .= 's';
    $filter .= " AND status = ?";
    $values[] = $status;
}

$stmt = $con->prepare("
SELECT COUNT(*) AS total 
FROM Carts ca" . $filter);
if (!empty($params))
    $stmt->bind_param($params, ...$values);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalRows = $row['total'];
$totalPages = ceil($totalRows / $total);

if ($page < 1 || $page > $totalPages) {
    $page = 1;
}

$offset = ($page - 1) * $total;
if ($totalRows == 0) {
    echo json_encode([
        'status' => 404,
        'message' => 'No orders found.',
        'products' => [],
        'results' => $results,
        'totalPages' => $totalPages
    ]);
    exit();
}

$stmt = $con->prepare("
SELECT ca.cart_id, CONCAT(n.fname, ' ', n.lname) AS name, ca.status, ca.date_time_deadline, ca.date_time_received, ca.date_time_created, ca.total, COUNT(c.cart_id) AS total_items 
FROM Carts ca
JOIN Cart_Items c ON ca.cart_id = c.cart_id
JOIN Users u ON ca.user_id = u.user_id
JOIN Names n ON u.name_id = n.name_id "
    . $filter . " 
GROUP BY ca.cart_id, name, ca.status, ca.date_time_deadline, ca.date_time_received, ca.date_time_created, ca.total " . "LIMIT $total OFFSET $offset");
if (!empty($params))
    $stmt->bind_param($params, ...$values);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    
    $orders[] = [
        'cart_id' => $row['cart_id'],
        'name' => $row['name'],
        'status' => $row['status'],
        'date_time_deadline' => $row['date_time_deadline'] !== null ? (new DateTime($row['date_time_deadline']))->format('m/d/y h:i A') : 'TBD',
        'date_time_received' => $row['date_time_received'] !== null ? (new DateTime($row['date_time_received']))->format('m/d/y h:i A') : 'TBD',
        'date_time_created' => (new DateTime($row['date_time_created']))->format('m/d/y h:i A'),
        'total' => $row['total'],
        'total_items' => $row['total_items']
    ];
    $results++;
}

$stmt = $con->prepare("
SELECT ca.cart_id, p.image, p.item_name, cs.category, cs.subcategory, p.brand, c.item_qty, c.subtotal, v.variation_name, ca.date_time_deadline
FROM Carts ca
JOIN Cart_Items c ON ca.cart_id = c.cart_id
JOIN Products p ON c.product_id = p.product_id
JOIN Categories cs ON p.category_id = cs.category_id
JOIN Variations v ON c.variation_id = v.variation_id
" . $filter . "
ORDER BY ca.cart_id ASC");
if (!empty($params))
    $stmt->bind_param($params, ...$values);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $order_items[$row['cart_id']][] = [
        'category' => $row['category'],
        'subcategory' => $row['subcategory'],
        'item_name' => $row['item_name'],
        'brand' => $row['brand'],
        'image' => $row['image'],
        'item_qty' => $row['item_qty'],
        'subtotal' => $row['subtotal'],
        'variation_name' => $row['variation_name'],
        'date_time_deadline' => $row['date_time_deadline']
    ];
}

$stmt->close();

$myObj = array(
    'status' => 200,
    'message' => 'Orders retrieved successfully.',
    'orders' => $orders,
    'order_items' => $order_items,
    'results' => $results,
    'totalPages' => $totalPages
);

echo json_encode($myObj);
