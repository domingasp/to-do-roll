<?php include("connection.php"); ?><?php
    session_start();

    // If no email or token provided then redirect to sign in page
    if (!isset($_GET["email"]) || !isset($_GET["verification_token"])) {
        if (isset($_SESSION["account_id"])) {
            header("Location: index.php");
            die();
        }
        
        header("Location: sign_in.php");
        die();
    } else {
        $email = $_GET["email"];
        $verification_token = $_GET["verification_token"];

        // Update the verified status
        $stmt = $conn->prepare("SELECT verified, token_date FROM Account WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($verified, $token_date);
        $stmt->store_result();
        $stmt->fetch();
        $number = $stmt->num_rows;
        $stmt->close();

        // If email exists
        if ($number > 0) {
            // When email verified redirect to sign in page or index if user signed in
            if ($verified == 1) {
                if (isset($_SESSION["account_id"])) {
                    header("Location: index.php");
                    die();
                }
                header("Location: sign_in.php");
                die();
            } else {
                // Check if token has expired
                // String version of current time to store in db
                $current_time_str = gmdate("Y-m-d H:i:s", time());

                // Get current date and time in UTC and transform them into types that can be manipulated
                $current_time_obj = strtotime($current_time_str);
                $token_date_php = strtotime($token_date);

                // If the token is older than 8 hours then redirect as token has expired
                if (round(abs($current_time_obj-$token_date_php)/60) >= 480) {
                    // Sets a banner message
                    $_SESSION["banner-msg"] = "The link has expired. A new link has been sent to your email.";

                    header("Location: send_verification_email.php?email=" . $email);
                    die();
                } else {
                    // Set verified token to be 1 in db
                    $stmt = $conn->prepare("UPDATE Account SET verified = 1 WHERE email = ? AND verification_token = ?");
                    $stmt->bind_param("ss", $email, $verification_token);
                    $stmt->execute();
                    $rows_changed = $stmt->affected_rows;
                    $stmt->close();

                    // Sets a banner message
                    $_SESSION["banner-msg"] = "Your account has been successfully verified.";

                    if (isset($_SESSION["account_id"])) {
                        header("Location: index.php");
                        die();
                    }

                    header("Location: sign_in.php");
                    die();
                }
            }
        } else {
            if (isset($_SESSION["account_id"])) {
                header("Location: index.php");
                die();
            }

            // Redirect to sign in page
            header("Location: sign_in.php");
            die();
        }
    }
?>