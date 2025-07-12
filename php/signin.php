<?php
include_once("db_connect.php");

$retVal = "";
$isValid = true;
$status = 400;
$phone_number = $_POST['phone_number'] ?? null;
$password = $_POST['password'] ?? null;

session_regenerate_id(true);

$phone_number = isset($phone_number) ? trim($phone_number) : '';
$password = isset($password) ? trim($password) : '';

// Check fields are empty or not
if ($phone_number == '' || $password == '') {
    $isValid = false;
    $retVal = "Please fill all fields.";
}

// Check if phone number is valid or not
if ($isValid && !preg_match('/^09\d{9}$/', $phone_number)) {
    $isValid = false;
    $retVal = "Invalid phone number.";
}

// Check if phone number already exists
if ($isValid) {
    $stmt = $con->prepare("
    SELECT Users.*, Names.fname, Names.lname 
    FROM Users 
    JOIN Names ON Users.name_id = Names.name_id 
    WHERE Users.phone_number = ?
");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $obj = mysqli_fetch_object($result);
    $stmt->close();
    $retVal = "Wrong phone number or password.";

    if ($result->num_rows > 0) {
        $isPassword = password_verify($password, $obj->password);
        if ($isPassword == true) {
            session_regenerate_id(true);

            $status = 200;
            $retVal = "Success.";
            $_SESSION['user_id'] = $obj->user_id;
            $_SESSION['admin'] = $obj->admin;
        }
    }
}

$myObj = array(
    'status' => $status,
    'message' => $retVal
);
$myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
echo $myJSON;