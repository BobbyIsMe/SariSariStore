<?php 
// Get ordered items of all customers
include_once("db_connect.php");
include_once("admin_status.php");
requireAdmin($con, 'Manager');

$orders = [];
$values = [];
$params = '';
$filter = " WHERE 1=1";
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
FROM Orders" . $filter);
$stmt->bind_param($params, ...$values);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalRows = $row['total'];
$totalPages = ceil($totalRows / $total);

if($page < 1 || $page > $totalPages) {
    $page = 1;
}

$offset = ($page - 1) * $total;
if($totalRows == 0) {
    echo json_encode([
        'status' => 404,
        'message' => 'No orders found.',
        'products' => [],
        'results' => $results,
        'totalPages' => $totalPages
    ]);
    exit();
}

$filter .= " LIMIT $total OFFSET $offset";

$stmt = $con->prepare("
SELECT ca.cart_id, CONCAT(n.fname, ' ', n.lname) AS name, ca.status, ca.order_date, ca.total, COUNT(c.cart_id) AS total_items 
FROM Cart ca
LEFT JOIN Cart_Items c ON ca.cart_id = c.cart_id
LEFT JOIN Name n ON ca.user_id = n.user_id" . $filter . "
GROUP BY ca.cart_id, name, ca.status, ca.order_date, ca.total" . $filter);
if(!empty($params))
$stmt->bind_param($params, ...$values);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = [
        'cart_id' => $row['cart_id'],
        'name' => $row['name'],
        'status' => $row['status'],
        'order_date' => $row['order_date'],
        'total' => $row['total'],
        'total_items' => $row['total_items']
    ];
    $results++;
}
$stmt->close();

$myObj = array(
    'status' => 200,
    'message' => 'Orders retrieved successfully.',
    'orders' => $orders,
    'results' => $results,
    'totalPages' => $totalPages
);
?>