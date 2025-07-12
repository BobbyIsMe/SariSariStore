<?php
    session_start();

    // Database connection start 
    $host = "localhost";    // Host name
    $user = "root";         // User
    $password = "1234";         // Password
    $dbname = "sstore";     // Database name

    // Create connection
    $con = mysqli_connect($host, $user, $password,$dbname);

    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>