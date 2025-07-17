<?php
include_once('db_connect.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$stmt = $con->prepare("
SELECT COUNT(*) AS notification_count 
FROM Notifications n 
JOIN Carts c ON n.cart_id = c.cart_id
JOIN Users u ON c.user_id = u.user_id
WHERE u.user_id = ? AND n.is_read = false");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $notification_count = $row['notification_count'];
    if ($notification_count > 0) {
        echo json_encode(['status' => 200, 'message' => 'Notification count retrieved successfully.', 'notification_count' => $notification_count]);
    } else {
        echo json_encode(['status' => 404, 'message' => 'No notifications found.']);
    }
}
