<?php
include_once('db_connect.php');

$product_id = $_GET['product_id'] ?? null;

if (!isset($product_id)) {
    echo json_encode(['status' => 400, 'message' => 'Product ID is required.']);
    exit();
}

$stmt = $con->prepare("
SELECT v.variation_id, v.variation_name
FROM Variations v
WHERE v.product_id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$variations = [];
if(!$result || $result->num_rows === 0) {
    echo json_encode(['status' => 404, 'message' => 'No variations found.', $variations => null]);
    exit();
}

while ($row = $result->fetch_assoc()) {
    $variations[] = [
        'variation_id' => $row['variation_id'],
        'variation_name' => $row['variation_name']
    ];
}
echo json_encode(['status' => 200, 'message' => 'Variations retrieved successfully.', 'variations' => $variations]);
?>