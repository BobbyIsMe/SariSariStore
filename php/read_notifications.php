<?php
// Mark notifications as read
include_once('db_connect.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!isset($user_id)) {
    echo json_encode(['status' => 401, 'message' => 'Must be signed in to read notifications.']);
    exit();
}

$stmt = $con->prepare("
UPDATE Notifications n
JOIN Carts c ON n.cart_id = c.cart_id
JOIN Users u ON c.user_id = u.user_id 
SET n.is_read = 1 WHERE u.user_id = ? AND n.is_read = 0");
$stmt->bind_param('i', $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 200, 'message' => 'Notifications marked as read.']);
} else {
    echo json_encode(['status' => 404, 'message' => 'No notifications found.']);
}
?>