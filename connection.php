<?php
    $server_name = "localhost";
    $username = "root";
    $password = "password";
    $database = "activity_roll";

    $conn = mysqli_connect($server_name, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>