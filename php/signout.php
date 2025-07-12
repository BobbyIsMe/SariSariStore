<?php
session_start();

if (isset($_SESSION['user_id'])) {
    session_destroy(); // Destroy the session only if user is signed in
    $response = [
        'status' => 200,
        'message' => 'Signed out successfully.'
    ];
} else {
    $response = [
        'status' => 400,
        'message' => 'No active session found.'
    ];
}

echo json_encode($response, JSON_FORCE_OBJECT);