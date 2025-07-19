<?php
include_once('db_connect.php');
$user_id = $_SESSION['user_id'] ?? null;

if(!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$verified = $_SESSION['verified'] ?? null;

if (!isset($verified) || $verified == true) {
    echo json_encode(['status' => 400, 'message' => 'You are already verified.']);
    exit();
}

$code = $_POST['code'] ?? null;

if(!isset($code)) {
    echo json_encode(['status' => 400, 'message' => 'Code is required.']);
    exit();
}

$stmt = $con->prepare("
SELECT expiration_date, code
FROM Users
WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
$expiration_date = $row['expiration_date'];
$verif_code = $row['code'];

if($expiration_date < date('Y-m-d H:i:s')) {
    echo json_encode(['status' => 400, 'message' => 'Code has expired. Please request a new code.']);
    exit();
}

if($code !== $verif_code) {
    echo json_encode(['status' => 400, 'message' => 'Code does not match.']);
    exit();
}

$stmt = $con->prepare("
UPDATE Users 
SET verified = 1
WHERE user_id = ?
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->close();
$_SESSION['verified'] = true;
echo json_encode(['status' => 200, 'message' => 'Account verified successfully.']);
?>