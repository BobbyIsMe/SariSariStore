<?php
// Get variations of product
include_once("db_connect.php");

$product_id = $_GET['product_id'] ?? null;
$variations = [];

if (!isset($product_id)) {
    echo json_encode(['status' => 400, 'message' => 'Product ID is required.']);
    exit();
}

$stmt = $con->prepare("
SELECT variation_id, variation_name
FROM Variations 
WHERE product_id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $variations[] = [
            'variation_id' => $row['variation_id'],
            'variation_name' => $row['variation_name']
        ];
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 404, 'message' => 'No variations listed.']);
    exit();
}

echo json_encode([
    'status' => 200,
    'message' => 'Variations retrieved successfully.',
    'variations' => $variations
]);
?>