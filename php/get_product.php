<?php
// Get product details by product ID

include_once("db_connect.php");
$product_id = $_GET['product_id'] ?? null;

$stmt = $con->prepare("
SELECT *
FROM Products 
WHERE product_id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo json_encode($row);
$stmt->close();
?>