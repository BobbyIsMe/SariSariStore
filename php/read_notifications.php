<?php
// Mark notifications as read
include_once('db_connect.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!isset($user_id)) {
    echo json_encode(['status' => 401, 'message' => 'Must be signed in to read notifications.']);
    exit();
}

$stmt = $con->prepare("UPDATE Notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
$stmt->bind_param('i', $user_id);
$stmt->execute();
?>