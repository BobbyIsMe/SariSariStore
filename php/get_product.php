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
if ($result && $result->num_rows > 0) {
    echo json_encode([
        'status' => 200,
        'message' => 'Product retrieved successfully.',
        'product_id' => $row['product_id'],
        'image' => $row['image'],
        'item_name' => $row['item_name'],
        'category' => $row['category'],
        'subcategory' => $row['subcategory'],
        'brand' => $row['brand'],
        'stock_qty' => $row['stock_qty'],
        'price' => $row['price'],
        'item_details' => $row['item_details']
    ]);
} else {
    echo json_encode(['status' => 404, 'message' => 'Product not found.', 'product_id' => null]);
}
$stmt->close();
