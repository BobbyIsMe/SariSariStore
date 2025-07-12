<?php
// Get product details by product ID

include_once("db_connect.php");
$prod_id = $_GET['prod_id'] ?? null;

$stmt = $con->prepare("
SELECT *
FROM products 
WHERE prod_id = ?");
$stmt->bind_param('i', $prod_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
echo json_encode($row);
$stmt->close();
?>