<?php include("connection.php") ?><?php
    session_start();

    if(isset($_SESSION["account_id"])) {
        header("Location: index.php");
    } else {

    }

?><!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="assets/toDoRollFavicon.png">
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    </head>
    <body>
    </body>
</html>