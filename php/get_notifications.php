<?php
// Get notifications for the user
include_once('db_connect.php');

$notifications = [];
$total = 5;
$results = 0;

$user_id = $_SESSION['user_id'] ?? null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if (!isset($user_id)) {
    echo json_encode(['status' => 401, 'message' => 'Must be signed in to access.']);
    exit();
}

$stmt = $con->prepare("
SELECT COUNT(*) AS total
FROM Notifications n
JOIN Carts ca ON n.cart_id = ca.id
WHERE ca.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalRows = $row['total'];
$totalPages = ceil($totalRows / $total);

if($page < 1 || $page > $totalPages) {
    $page = 1;
}

$offset = ($page - 1) * $total;
if($totalRows == 0) {
    echo json_encode([
        'status' => 404,
        'message' => 'No notifications found.',
        'notifications' => [],
        'results' => $results,
        'totalPages' => $totalPages
    ]);
    exit();
}

$stmt = $con->prepare("
SELECT n.cart_id, n.message, n.date_time_created, n.status
FROM Notifications n
JOIN Carts ca ON n.cart_id = ca.id
WHERE ca.user_id = ? LIMIT $total OFFSET $offset");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        'cart_id' => $row['cart_id'],
        'message' => $row['message'],
        'date_time_created' => $row['date_time_created'],
        'status' => $row['status']
    ];
    $results++;
}

$stmt->close();
echo json_encode([
    'status' => 200,
    'message' => 'Notifications retrieved successfully.',
    'notifications' => $notifications,
    'results' => $results,
    'totalPages' => $totalPages
]);
?>