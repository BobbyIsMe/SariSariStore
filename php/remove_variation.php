<?php
include_once('db_connect.php');
include_once('admin_status.php');
requireAdmin($con, 'inventory');

$variation_id = $_POST['variation_id'] ?? null;

if (!isset($variation_id)) {
    echo json_encode(['status' => 400, 'message' => 'Variation ID is required.']);
    exit();
}

$stmt = $con->prepare("SELECT 1 FROM Variations WHERE variation_id = ?");
$stmt->bind_param('i', $variation_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    echo json_encode(['status' => 404, 'message' => 'Variation not found.']);
    exit();
}

try {
    $stmt = $con->prepare("DELETE FROM Variations WHERE variation_id = ?");
    $stmt->bind_param('i', $variation_id);

    $stmt->execute();
    $stmt->close();

    echo json_encode(['status' => 200, 'message' => 'Variation deleted successfully.']);
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
        echo json_encode(['status' => 400, 'message' => 'Cannot remove variation with pending orders.']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
