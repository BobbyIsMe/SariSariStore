<?php
// Notify users about order status changes
include_once('db_connect.php');

function notifyUsers($con, $notifications)
{
    $placeholders = [];
    $values = '';
    $params = [];

    foreach ($notifications as $notif) {
        $placeholders[] = '(?, ?, ?, ?)';
        $values .= 'isss';
        $params[] = $notif['cart_id'];
        $params[] = $notif['message'];
        $params[] = $notif['date_time_created'];
        $params[] = $notif['status'];
    }

    $stmt = $con->prepare("INSERT INTO Notifications (cart_id, message, date_time_created, status) VALUES " . implode(', ', $placeholders));
    $stmt->bind_param($values, ...$params);
    $stmt->execute();
    $stmt->close();
}
