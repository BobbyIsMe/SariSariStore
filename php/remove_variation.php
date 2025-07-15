<?php
include_once('db_connect.php');
include_once('admin_status.php');
requireAdmin($con, 'staff');

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

$stmt = $con->prepare("DELETE FROM Variations WHERE variation_id = ?");
$stmt->bind_param('i', $variation_id);

if (!$stmt->execute()) {
    if (strpos($stmt->error, 'foreign key constraint fails') !== false) {
        echo json_encode(['status' => 400, 'message' => 'Cannot remove variation with products.']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Failed to remove variation.']);
    }
} else {
    echo json_encode(['status' => 200, 'message' => 'Variation removed successfully.']);
}

$stmt->close();
