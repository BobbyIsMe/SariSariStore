<?php
// Load homepage products
include_once("db_connect.php");

$products = [];
$values = [];
$params = '';
$order = '';
$filter = " WHERE 1=1";
$order = "";
$results = 0;
$total = 10;

$category = $_GET['category'] ?? null;
$subcategory = $_GET['subcategory'] ?? null;
$brand = $_GET['brand'] ?? null;
$stock_qty = $_GET['stock_qty'] ?? null;
$item_name = $_GET['item_name'] ?? null;
$total_sales = $_GET['total_sales'] ?? null;
$date_restocked = $_GET['date_restocked'] ?? null;
$recent = $_GET['recent'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (!empty($category)) {
    $params .= 's';
    $filter .= " AND cs.category LIKE ?";
    $values[] = '%' . $category . '%';
}

if (!empty($subcategory)) { 
    $params .= 's';
    $filter .= " AND cs.subcategory LIKE ?";
    $values[] = '%' . $subcategory . '%';
}

if (!empty($brand)) {
    $params .= 's';
    $filter .= " AND p.brand LIKE ?";
    $values[] = '%' . $brand . '%';
}

if (!empty($item_name)) {
    $params .= 's';
    $filter .= " AND p.item_name LIKE ?";
    $values[] = '%' . $item_name . '%';
}

$order_parts = [];
if (!empty($total_sales) && in_array($total_sales, ['ASC', 'DESC'])) {
    $order_parts[] = "p.total_sales $total_sales";
}

if (!empty($stock_qty) && in_array($stock_qty, ['ASC', 'DESC'])) {
    $order_parts[] = "p.stock_qty $stock_qty";
}

if(!empty($date_restocked) && in_array($date_restocked, ['ASC', 'DESC'])) {
    $order_parts[] = "p.date_time_restocked $date_restocked";
}

if(!empty($recent) && in_array($recent, ['ASC', 'DESC'])) {
    $order_parts[] = "p.product_id $recent";
}

if (!empty($order_parts)) {
    $order = " ORDER BY " . implode(", ", $order_parts);
} else {
    $order = " ORDER BY p.item_name ASC";
}

$stmt = $con->prepare("
SELECT COUNT(*) AS total 
FROM Products p
JOIN Categories cs ON p.category_id=cs.category_id" . $filter . $order);
if(!empty($params))
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
        'message' => 'No products found.',
        'products' => [],
        'results' => $results,
        'totalPages' => $totalPages
    ]);
    exit();
}

$filter .= $order . " LIMIT $total OFFSET $offset";

$stmt = $con->prepare("
SELECT p.product_id, p.image, p.item_name, cs.category, cs.subcategory, p.brand, p.price, p.stock_qty
FROM Products p
JOIN Categories cs ON p.category_id=cs.category_id". $filter);
if(!empty($params))
$stmt->bind_param($params, ...$values);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $products[] = [
        'product_id' => $row['product_id'],
        'image' => $row['image'],
        'item_name' => $row['item_name'],
        'category' => $row['category'],
        'subcategory' => $row['subcategory'],
        'brand' => $row['brand'],
        'price' => $row['price'],
        'stock_qty' => $row['stock_qty']
    ];
    $results++;
}

$stmt->close();

echo json_encode([
    'status' => 200,
    'message' => 'Products retrieved successfully.',
    'products' => $products,
    'results' => $results,
    'totalPages' => $totalPages
]);
?>