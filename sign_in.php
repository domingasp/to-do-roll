<?php include("connection.php") ?><?php
    session_start();

    // If signed in send them to main page
    if(isset($_SESSION["account_id"])) {
        header("Location: index.php");
    } else {
        if ($_POST) {
            $email = filter_var(strtolower(trim($_POST["email"])), FILTER_SANITIZE_STRING);
            $password = $_POST["password"];

            // Checks if any input fields are empty and if email is valid
            $email_err = (empty($email) ? "No email entered" : (!filter_var($email, FILTER_VALIDATE_EMAIL) ? "Invalid email format" : ""));
            $password_err = (empty($password) ? "No password entered" : "");

            // If no errors then sign in
            if (empty($email_err) && empty($password_err)) {
                // Query database for accounts with the email
                $stmt = $conn->prepare("SELECT account_id, password FROM Account WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($account_id, $hashed_password);
                $stmt->fetch();
                $stmt->close();

                if (password_verify($password, $hashed_password)) {
                    // Set session variable
                    $_SESSION["account_id"] = $account_id;

                    // Redirect to Sign In page
                    header("Location: index.php");
                    die();
                } else {
                    echo "invalid input";
                }

            // Else there are errors in input fields
            } else {
                /*
                    ###################
                    # Add error messages on form submit/add local validation
                    ###################
                */
            }
        }
    }

?><!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="css/all.css">
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">

        <link rel="icon" href="assets/toDoRollFavicon.png">

        <title>To Do Roll - Sign In</title>
    </head>
    <body>
        <img src="assets/toDoRollLogo.png" class="form-logo-center">
        <div class="form-outer-div">
            <h1 class="form-h1">Sign In</h1>
            <form class="main-form" action="sign_in.php" method="post" novalidate>
                <label class="form-label" for="email">Email</label>
                <input class="form-input" id="email" name="email" type="email">

                <label class="form-label" for="password">Password</label>
                <input class="form-input form-input-password" id="password" name="password" type="password">

                <div class="form-checkbox">
                    <input type="checkbox" id="showPasswordCheckbox" onclick="togglePasswordView(this)">
                    <label for="showPasswordCheckbox">Show Password</label>
                </div>

                <button class="basic-btn form-btn" type="submit">Sign In</button>

                <p class="form-p">Or, if you do not have an account</p>
                <a class="basic-btn form-a" href="register.php">Register</a>
            </form>
        </div>

        <script src="js/script.js"></script>
    </body>
</html>