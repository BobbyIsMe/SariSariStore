<?php
include_once("db_connect.php");
$retVal = "";
$isValid = true;
$status = 400;

$fname = trim($_POST['first_name']);
$lname = trim($_POST['last_name']);
$phone_number = trim($_POST['phone_number']);
$password = trim($_POST['password']);
$confirmpassword = trim($_POST['confirm_password']);

// Check fields are empty or not
if ($fname == '' || $lname == '' || $phone_number == '' || $password == '' || $confirmpassword == '') {
    $isValid = false;
    $retVal = "Please fill all fields.";
}

// Check if confirm password matching or not
if ($isValid && ($password != $confirmpassword)) {
    $isValid = false;
    $retVal = "Confirm password not matching.";
}

// Check if email is valid or not
if ($isValid && !preg_match('/^09\d{9}$/', $phone_number)) {
    $isValid = false;
    $retVal = "Invalid phone number.";
}

// Check if email already exists
if ($isValid) {
    $stmt = $con->prepare("SELECT * FROM Users WHERE phone_number = ?");
    $stmt->bind_param("s", $phone_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        $isValid = false;
        $retVal = "Phone number already exists.";
    }
}

// Insert records
if ($isValid) {
    $con->begin_transaction();
    try {
        $stmt = $con->prepare("INSERT INTO Names (fname, lname) VALUES (?, ?)");
        $stmt->bind_param("ss", $fname, $lname);
        $stmt->execute();
        $name_id = $stmt->insert_id;
        $stmt->close();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $con->prepare("INSERT INTO Users (name_id, phone_number, password) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $name_id, $phone_number, $hashedPassword);
        $stmt->execute();
        $stmt->close();

        $con->commit();
        $retVal = "Account created successfully.";
        $status = 200;
    } catch (Exception $e) {
        $con->rollback();
        $retVal = "Error: " . $e->getMessage();
        $status = 500;
    }
}

$myObj = array(
    'status' => $status,
    'message' => $retVal
);
$myJSON = json_encode($myObj, JSON_FORCE_OBJECT);
echo $myJSON;