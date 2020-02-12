<?php include("connection.php") ?><?php
    session_start();

    // If signed in send them to main page
    if(isset($_SESSION["account_id"])) {
        header("Location: index.php");
    } else {
        // If form submitted then try to sign user in
        if ($_POST) {
            $name = filter_var(strtolower(trim($_POST["name"])), FILTER_SANITIZE_STRING);
            $email = filter_var(strtolower(trim($_POST["email"])), FILTER_SANITIZE_STRING);
            $password = $_POST["password"];

            // Checks if any input fields are empty and if email is valid
            $name_err = (empty($name) ? "No name entered" : "");
            $email_err = (empty($email) ? "No email entered" : (!filter_var($email, FILTER_VALIDATE_EMAIL) ? "Invalid email format" : ""));
            $password_err = (empty($password) ? "No password entered" : (strlen($password) < 8) ? "Password is too short" : "");

            // If no errors where found create an account
            if (empty($name_err) && empty($email_err) && empty($password_err)) {

                // Checks if email already exists in DB
                $stmt = $conn->prepare("SELECT * FROM Account WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $number = $result->num_rows;

                $email_err = ($number > 0 ? "Email already exists" : "");
                $stmt->close();

                // If email does not exist create account
                if (empty($email_err)) {
                    // Create hashed password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Add to database
                    $stmt = $conn->prepare("INSERT INTO Account(email, password, name) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $email, $hashed_password, $name);
                    $stmt->execute();
                    $stmt->close();

                    // Redirect to Sign In page
                    header("Location: sign_in.php");
                    die();
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

        <title>To Do Roll - Register</title>
    </head>
    <body>
        <img src="assets/toDoRollLogo.png" class="form-logo-center">
        <div class="form-outer-div">
            <h1 class="form-h1">Register</h1>
            <form class="main-form" action="register.php" method="post" novalidate>
                <p class="form-instructions">Enter your details to create an account.</p>

                <label class="form-label" for="name">Name</label>
                <input class="form-input" id="name" name="name" type="text">

                <label class="form-label" for="email">Email</label>
                <input class="form-input" id="email" name="email" type="email">

                <label class="form-label" for="password">Password</label>
                <input class="form-input form-input-password" id="password" name="password" type="password">

                <div class="form-checkbox">
                    <input type="checkbox" id="showPasswordCheckbox" onclick="togglePasswordView(this)">
                    <label for="showPasswordCheckbox">Show Password</label>
                </div>

                <button class="basic-btn form-btn" type="submit">Create Account</button>

                <p class="form-p">Already have an account?</p>
                <a class="basic-btn form-a" href="sign_in.php">Sign In</a>
            </form>
        </div>

        <script src="js/script.js"></script>
    </body>
</html>