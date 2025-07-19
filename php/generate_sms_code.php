<?php
require_once('../vendor/autoload.php');

include_once("db_connect.php");

$user_id = $_SESSION['user_id'] ?? null;

if (!isset($user_id)) {
    echo json_encode(['status' => 400, 'message' => 'Must be signed in to proceed.']);
    exit();
}

$verified = $_SESSION['verified'] ?? null;

if (!isset($verified) || $verified == true) {
    echo json_encode(['status' => 400, 'message' => 'You are already verified.']);
    exit();
}

$stmt = $con->prepare("
SELECT resend_date, phone_number
FROM Users
WHERE user_id = ?
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$result || $result->num_rows === 0) {
    echo json_encode(['status' => 404, 'message' => 'User not found.']);
    exit();
}

$resend_date = $row['resend_date'];
$now = date('Y-m-d H:i:s');

if ($resend_date < $now || $resend_date == null) {
    $code = mt_rand(100000, 999999);
    $message = 'Cerina\'s Sari2Store SMS verification code: ' . $code . '. This code will expire in 5 minutes.';

    $config = ClickSend\Configuration::getDefaultConfiguration()
        ->setUsername('BobbyIsMe')
        ->setPassword('578E6995-44E0-D1BA-3324-27D2CA989C39');

    $apiInstance = new ClickSend\Api\SMSApi(new GuzzleHttp\Client(), $config);
    $msg = new \ClickSend\Model\SmsMessage();
    $msg->setBody($message);
    $msg->setTo($row['phone_number']);
    $msg->setSource("sdk");

    $sms_messages = new \ClickSend\Model\SmsMessageCollection();
    $sms_messages->setMessages([$msg]);
    try {
        $result = $apiInstance->smsSendPost($sms_messages);
        $stmt = $con->prepare("
    UPDATE Users 
    SET code = ?, resend_date = DATE_ADD(?, INTERVAL 1 MINUTE), expiration_date = DATE_ADD(?, INTERVAL 5 MINUTE)
    WHERE user_id = ?
    ");
        $stmt->bind_param('sssi', $code, $now, $now, $user_id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['status' => 200, 'message' => 'SMS code sent to your phone number.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 400, 'message' => 'Error occured: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 400, 'message' => 'Please wait 1 minute before sending another SMS.']);
}
