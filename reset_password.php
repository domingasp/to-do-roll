<?php include("connection.php") ?><?php
    session_start();

    // If signed in send them to main page
    if (isset($_SESSION["account_id"])) {
        header("Location: index.php");
        die();
    } else {
        // If either email or password reset token not set in the url then redirect
        if (!isset($_GET["email"]) || !isset($_GET["password_reset_token"])) {
            header("Location: index.php");
            die();
        } else {
            $email = $_GET["email"];
            $password_reset_token = $_GET["password_reset_token"];
        }

        if ($_POST) {
            $password = $_POST["password"];

            // Checks if any input fields are empty and if email is valid
            $password_err = (empty($password) ? "Please enter a password." : ((strlen($password) < 8) ? "Password should be 8 characters or longer." : ""));

            // If no errors then sign in
            if (empty($password_err)) {
                // Query database for accounts with the email
                $stmt = $conn->prepare("SELECT password_reset_last_sent FROM Account WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($password_reset_last_sent);
                $stmt->store_result();
                $stmt->fetch();
                $number = $stmt->num_rows;
                $stmt->close();

                // If email exists, i.e. results return a row
                if ($number > 0) {
                    // Check if token has expired
                    // String version of current time to store in db
                    $current_time_str = gmdate("Y-m-d H:i:s", time());

                    // Get current date and time in UTC and transform them into types that can be manipulated
                    $current_time_obj = strtotime($current_time_str);
                    $password_reset_last_sent_php = strtotime($password_reset_last_sent);

                    // If the token is older than 8 hours then redirect as token has expired
                    if (round(abs($current_time_obj-$password_reset_last_sent_php)/60) >= 480) {
                        // Sets a banner message
                        $_SESSION["banner-msg"] = "The password reset link has expired. A new link has been sent to your email.";

                        header("Location: forgot_password.php?email=" . $email);
                        die();
                    } else {
                        // Create hashed password
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // Generate verification token
                        $new_password_reset_token = md5(uniqid(rand(), true));

                        // Set new password, change the token to a new token
                        $stmt = $conn->prepare("UPDATE Account SET password = ?, password_reset_last_sent = ?, password_reset_token = ? WHERE email = ? AND password_reset_token = ?");
                        $stmt->bind_param("sssss", $hashed_password, $current_time_str, $new_password_reset_token, $email, $password_reset_token);
                        $stmt->execute();
                        $rows_changed = $stmt->affected_rows;
                        $stmt->close();

                        if ($rows_changed > 0) {
                            // Sets a banner message
                            $_SESSION["banner-msg"] = "Your password has been changed successfully.";
                        } else {
                            // Sets a banner message
                            $_SESSION["banner-msg"] = "The verification token has expired.";
                        }

                        header("Location: index.php");
                        die();
                    }
                } else {
                    header("Location: index.php");
                    die();
                }
            }
        // If data not yet posted set the values for the variables to be blank(prevents "password" from being input into the password field)
        } else {
            $password = "";
        }
    }

?><!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="css/all.css">
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">

        <link rel="icon" href="assets/toDoRollFavicon.png">

        <title>To Do Roll - Reset Password</title>
    </head>
    <body>
        <div style="text-align:center;"><a href="index.php" class="form-logo-a"><img src="assets/toDoRollLogo.png" class="form-logo-center"></a></div>
        <div class="form-outer-div">
            <h1 class="form-h1">Reset Password</h1>
            <form class="main-form" onsubmit="return validateForm()" action="reset_password.php<?php echo "?email=" . $email . "&password_reset_token=" . $password_reset_token; ?>" method="post" novalidate>
                <p class="form-instructions">Please enter a new password.</p>
                <label class="form-label" for="password">New Password</label>
                <input class="form-input <?php if(!empty($password_err)) echo "invalid-input"; ?>" id="password" name="password" type="password" value="<?php echo $password; ?>">
                <?php if (!empty($password_err)) { echo "<p class='input-error'><i class='fas fa-exclamation-circle'></i> " . $password_err . "</p>"; } ?>

                <div class="form-checkbox">
                    <input type="checkbox" id="showPasswordCheckbox" onclick="togglePasswordView()">
                    <label for="showPasswordCheckbox">Show Password</label>
                </div>

                <button class="basic-btn form-btn" type="submit">Change Password</button>
            </form>
        </div>

        <script src="js/script.js"></script>
    </body>
</html>