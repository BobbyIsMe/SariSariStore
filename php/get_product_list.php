<?php
// Load homepage products
include_once("db_connect.php");

$products = [];
$values = [];
$params = '';
$filter = " WHERE 1=1";
$results = 0;
$total = 5;

$category = $_GET['category'] ?? null;
$subcategory = $_GET['subcategory'] ?? null;
$brand = $_GET['brand'] ?? null;
$stock_qty = $_GET['stock_qty'] ?? null;
$item_name = $_GET['item_name'] ?? null;
$price = $_GET['price'] ?? null;
$total_sales = $_GET['total_sales'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (!empty($category)) {
    $params .= 's';
    $filter .= " AND category = ?";
    $values[] = $category;
}

if (!empty($subcategory)) { 
    $params .= 's';
    $filter .= " AND subcategory = ?";
    $values[] = $subcategory;
}

if ($brand) {
    $params .= 's';
    $filter .= " AND brand = ?";
    $values[] = $brand;
}

if (!empty($stock_qty) && is_numeric($stock_qty) && $stock_qty >= 0) {
    $params .= 'i';
    $filter .= " AND stock_qty = ?";
    $values[] = $stock_qty;
}

if (!empty($item_name)) {
    $params .= 's';
    $filter .= " AND item_name LIKE ?";
    $values[] = '%' . $item_name . '%';
}

if (!empty($price) && is_numeric($price)) {
    $params .= 'd';
    $filter .= " AND price = ?";
    $values[] = $price;
}

if (!empty($total_sales) && is_numeric($total_sales) && $total_sales == 1) {
    $filter .= " ORDER BY total_sales DESC";
}

$stmt = $con->prepare("
SELECT COUNT(*) AS total 
FROM Products" . $filter);
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

$filter .= " LIMIT $total OFFSET $offset";

$stmt = $con->prepare("
SELECT p.product_id, p.item_name, p.subcategory, p.brand, v.varation_name 
FROM Products p
JOIN Variations v ON p.variation_id=v.variation_id". $filter);
if(!empty($params))
$stmt->bind_param($params, ...$values);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $products[] = [
        'product_id' => $row['product_id'],
        'item_name' => $row['item_name'],
        'subcategory' => $row['subcategory'],
        'brand' => $row['brand']
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