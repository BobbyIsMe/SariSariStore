<?php 
// Load sidebar categories
include_once("db_connect.php");
$products = [];

$stmt = $con->prepare("
SELECT category,subcategory
FROM products");
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $category = $row['category'];
        $products[$category][] = [
            'subcategory' => $row['subcategory']
        ];
    }
}
$stmt->close();

$myObj = array(
    'products' => $products
);

echo json_encode($myObj);
?>