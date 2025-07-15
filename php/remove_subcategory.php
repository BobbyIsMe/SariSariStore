<?php
// Remove subcategory
include_once('db_connect.php');
include_once('admin_status.php');
requireAdmin($con, 'inventory');

$category_id = $_POST['category_id'] ?? null;

$stmt = $con->prepare("SELECT 1 FROM Categories WHERE category_id = ?");
$stmt->bind_param('i', $category_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    echo json_encode(['status' => 404, 'message' => 'Category not found.']);
    exit;
}

$stmt = $con->prepare("DELETE FROM Categories WHERE category_id = ?");
$stmt->bind_param('i', $category_id);

if (!$stmt->execute()) {
    if (strpos($stmt->error, 'foreign key constraint fails') !== false) {
        echo json_encode(['status' => 400, 'message' => 'Cannot remove category with products.']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Failed to remove category.']);
    }
} else {
    echo json_encode(['status' => 200, 'message' => 'Category removed successfully.']);
}
$stmt->close();
?>