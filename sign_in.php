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
        <img src="assets/toDoRollLogo.png" class="form-logo-center">
        <div class="form-outer-div">
            <h1 class="form-h1">Sign In</h1>
            <form class="main-form" novalidate>
                <label class="form-label">Email</label>
                <input class="form-input" type="email">

                <label class="form-label">Password</label>
                <input class="form-input" type="password">

                <button class="basic-btn form-btn" type="submit">Sign In</button>

                <p class="form-p">Or, if you do not have an account</p>
                <a class="basic-btn form-a" href="#">Register</a>
            </form>
        </div>
    </body>
</html>