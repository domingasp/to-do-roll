<?php include("connection.php") ?><?php
    session_start();

    // If signed in send them to main page
    if (isset($_SESSION["account_id"])) {
        header("Location: index.php");
        die();
    } else {
        if ($_POST) {
            $email = filter_var(strtolower(trim($_POST["email"])), FILTER_SANITIZE_STRING);

            // Checks if any input fields are empty and if email is valid
            $email_err = (empty($email) ? "Please enter an email." : "");

            if (empty($email_err)) {
                // Check whether the email exists
                $stmt = $conn->prepare("SELECT name, password_reset_token, password_reset_last_sent FROM Account WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($name, $password_reset_token, $password_reset_last_sent);
                $stmt->store_result();
                $stmt->fetch();
                $number = $stmt->num_rows;
                $stmt->close();

                // Email exists in db
                if ($number > 0) {
                    // String version of current time to store in db
                    $current_time_str = gmdate("Y-m-d H:i:s", time());

                    // Get current date and time in UTC and transform them into types that can be manipulated
                    $current_time_obj = strtotime($current_time_str);
                    $password_reset_last_sent_php = strtotime($password_reset_last_sent);

                    // If token is older than 8 hours (480 minutes) than generate a new one
                    if (round(abs($current_time_obj-$password_reset_last_sent_php)/60) >= 480) {
                        // Generate verification token
                        $password_reset_token = md5(uniqid(rand(), true));

                        // Update the verification token in the db
                        $stmt = $conn->prepare("UPDATE Account SET password_reset_token = ?, password_reset_last_sent = ? WHERE email = ?");
                        $stmt->bind_param("sss", $password_reset_token, $current_time_str, $email);
                        $stmt->execute();
                        $stmt->close();
                    }

                    $to  = "domingas.p@yahoo.com"; // BEFORE RELEASE: Change to $email
                    $subject = 'Reset Password Link';

                    // Create email and send it
                    // BEFORE RELEASE: Change the url for verification and img (image should be hosted on site)
                    $message = "
                    <html>
                        <head>
                            <title>To Do Roll - Password Reset</title>
                        </head>
                        <body style='font-size:18px; color:#000000;'>
                            <div style='display:block; margin-left:auto; margin-right:auto; width:90%; max-width:352px; padding:16px 24px; border: solid 2px #dddddd; border-radius:8px;'>
                                <img src='https://drive.google.com/uc?export=view&id=1wN4oCibi8Iu_X9S-r-PvbURaJoVBQgRr' style='display:block; max-width: 80px; margin-left:auto; margin-right:auto;' />
                                <p>Hi " . ucwords($name) .",</p>
                                <p>Please use the the following link to reset your password:</p>
                                <a style='display:block; text-decoration:none; text-align:center; background-color:#3EC300; color:#ffffff; padding:12px; border-radius:4px;' href='http://localhost/~domingas/to_do_roll/reset_password.php?email=" . $email . "&password_reset_token=" . $password_reset_token . "'>Reset Password<a>
                                <p>The link expires every 8 hours, if your token has expired then request a new token when logging in.</p>
                                <p>If you did not request a password reset you can safely ignore this email.</p>
                                <p><span style='font-weight: bold;'> To Do Roll Team</span></p>
                            </div>
                        </body>
                    </html>";

                    $headers  = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From: To Do Roll <noreply@todoroll.com>' . "\r\n";

                    mail($to, $subject, $message, $headers);

                    $_SESSION["banner-msg"] = "A password reset link has been sent to your email, please check your inbox.";
                    
                    // Redirect to the sign in page
                    header("Location: sign_in.php");
                    die();

                // Invalid email, redirect to sign_in page
                } else {
                    $email_err = "No account exists with that email.";
                }
            }
        // If data not yet posted set the values for the variables to be blank(prevents "password" from being input into the password field)
        } else {
            $email = "";
        }
    }

?><!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="css/all.css">
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">

        <link rel="icon" href="assets/toDoRollFavicon.png">

        <title>To Do Roll - Forgot Password</title>
    </head>
    <body>
        <a href="index.php"><img src="assets/toDoRollLogo.png" class="form-logo-center"></a>
        <div class="form-outer-div">
            <h1 class="form-h1">Forgot Password?</h1>
            <a href="sign_in.php" class="back-a"><i class="fas fa-arrow-left"></i> Back</a>
            <form class="main-form" action="forgot_password.php" method="post" novalidate>
                <p class="form-instructions">Enter your email address to receive a password reset link.</p>
                <label class="form-label" for="email">Email</label>
                <input class="form-input <?php if(!empty($email_err)) echo "invalid-input"; ?>" id="email" name="email" type="email" value="<?php echo $email; ?>">
                <?php if (!empty($email_err)) { echo "<p class='input-error'><i class='fas fa-exclamation-circle'></i> " . $email_err . "</p>"; } ?>

                <button class="basic-btn form-btn" type="submit">Send Reset Link</button>

                <p class="form-p">Or, if you do not have an account</p>
                <a class="basic-btn form-a" href="register.php">Register</a>
            </form>
        </div>

        <script src="js/script.js"></script>
    </body>
</html>