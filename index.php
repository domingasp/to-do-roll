<?php include("connection.php") ?><?php
    session_start();

    if(!isset($_SESSION["account_id"])) {
        header("Location: sign_in.php");
    } else {
        echo $_SESSION["account_id"];
    }

?><!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="css/all.css">
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">

        <link rel="icon" href="assets/toDoRollFavicon.png">

        <title>To Do Roll - Homepage</title>
    </head>
    <body>
        <a href="sign_out.php">Sign Out</a>
    </body>
</html>