<?php include("connection.php") ?><?php
    session_start();

    if(isset($_SESSION["account_id"])) {
        header("Location: index.php");
    } else {
        
    }

?><!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
    </body>
</html>