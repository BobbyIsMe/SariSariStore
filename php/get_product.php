<?php
// Get product details by product ID

include_once("db_connect.php");
$product_id = $_GET['product_id'] ?? null;

$stmt = $con->prepare("
SELECT p.product_id, p.image, p.item_name, cs.category, cs.subcategory, p.brand, p.stock_qty, p.price, p.item_details
FROM Products p
JOIN Categories cs ON p.category_id = cs.category_id
WHERE p.product_id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo json_encode($row);
$stmt->close();
?>