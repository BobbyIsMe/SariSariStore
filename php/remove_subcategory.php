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

try {
    $stmt = $con->prepare("DELETE FROM Categories WHERE category_id = ?");
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['status' => 200, 'message' => 'Category deleted successfully.']);

} catch (Exception $e) {
    if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
        echo json_encode(['status' => 400, 'message' => 'Cannot remove category with products.']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>