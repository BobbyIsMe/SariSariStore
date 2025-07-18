<?php
// Cancel reservation of items
include_once('db_connect.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$stmt = $con->prepare("
UPDATE Carts SET type = 'cart' 
WHERE user_id = ? AND type = 'order' AND status IN ('pending', 'rejected')");
$stmt->bind_param('i', $user_id);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 200, 'message' => 'Reservation cancelled successfully.']);
} else {
    echo json_encode(['status' => 500, 'message' => 'Reservation cannot be cancelled when it is approved.']);
}
?>