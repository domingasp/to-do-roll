<?php include("connection.php"); ?><?php
    session_start();

    // If no email provided to send the verification code to then redirect to sign in page
    if (!isset($_GET["email"])) {
        if (isset($_SESSION["account_id"])) {
            header("Location: index.php");
            die();
        }

        header("Location: sign_in.php");
        die();
    } else {
        $email = $_GET["email"];

        // Check whether the email exists
        $stmt = $conn->prepare("SELECT name, verified, verification_token, token_date, verification_last_sent, date_created FROM Account WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($name, $verified, $verification_token, $token_date, $verification_last_sent, $date_created);
        $stmt->store_result();
        $stmt->fetch();
        $number = $stmt->num_rows;
        $stmt->close();

        // Email exists in db
        if ($number > 0) {

            // If verified then redirect
            if ($verified) {
                if (isset($_SESSION["account_id"])) {
                    header("Location: index.php");
                    die();
                }

                header("Location: sign_in.php");
                die();
            }

            // String version of current time to store in db
            $current_time_str = gmdate("Y-m-d H:i:s", time());

            // Get current date and time in UTC and transform them into types that can be manipulated
            $current_time_obj = strtotime($current_time_str);
            $verification_last_sent_php = strtotime($verification_last_sent);
            $token_date_php = strtotime($token_date);

            // If token is older than 8 hours (480 minutes) than generate a new one
            if (round(abs($current_time_obj-$token_date_php)/60) >= 480) {
                // Generate verification token
                $verification_token = md5(uniqid(rand(), true));

                // Update the verification token in the db
                $stmt = $conn->prepare("UPDATE Account SET verification_token = ?, token_date = ? WHERE email = ?");
                $stmt->bind_param("sss", $verification_token, $current_time_str, $email);
                $stmt->execute();
                $stmt->close();
            }

            // Update the timestamp in the database to reflect that a new email has been sent
            $stmt = $conn->prepare("UPDATE Account SET verification_last_sent = ? WHERE email = ?");
            $stmt->bind_param("ss", $current_time_str, $email);
            $stmt->execute();
            $stmt->close();

            $to  = "domingas.p@yahoo.com"; // BEFORE RELEASE: Change to $email
            $subject = 'Please verify your email';

            // Create email and send it
            // BEFORE RELEASE: Change the url for verification and img (image should be hosted on site)
            $message = "
            <html>
                <head>
                    <title>To Do Roll - Email verification</title>
                </head>
                <body style='font-size:18px; color:#000000;'>
                    <div style='display:block; margin-left:auto; margin-right:auto; width:90%; max-width:352px; padding:16px 24px; border: solid 2px #dddddd; border-radius:8px;'>
                        <img src='https://drive.google.com/uc?export=view&id=1wN4oCibi8Iu_X9S-r-PvbURaJoVBQgRr' style='display:block; max-width: 80px; margin-left:auto; margin-right:auto;' />
                        <p>Hi " . ucwords($name) .",</p>
                        <p>Please use the the following link to verify your email:</p>
                        <a style='display:block; text-decoration:none; text-align:center; background-color:#3EC300; color:#ffffff; padding:12px; border-radius:4px;' href='http://localhost/~domingas/to_do_roll/verify_email.php?email=" . $email . "&verification_token=" . $verification_token . "'>Verify Email<a>
                        <p>The link expires every 8 hours, if your token has expired then request a new token when logging in.</p>
                        <p>Welcome!<br>-<span style='font-weight: bold;'> To Do Roll Team</span></p>
                    </div>
                </body>
            </html>";

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: To Do Roll <noreply@todoroll.com>' . "\r\n";

            mail($to, $subject, $message, $headers);

            $_SESSION["banner-msg"] = "A verification email has been sent, please check your inbox.";

            if (isset($_SESSION["account_id"])) {
                header("Location: index.php");
                die();
            }
            
            // Redirect to the sign in page
            header("Location: sign_in.php");
            die();

        // Invalid email, redirect to sign_in page
        } else {
            if (isset($_SESSION["account_id"])) {
                header("Location: index.php");
                die();
            }

            header("Location: sign_in.php");
            die();
        }
    }

?>