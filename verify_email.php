<?php include("connection.php"); ?><?php
    session_start();
    if (isset($_SERVER["account_id"])) {
        // Redirect to the sign_in page if logged in
        header("Location: index.php");
        die();
    } else {
        // If no email or token provided then redirect to sign in page
        if (!isset($_GET["email"]) || !isset($_GET["verification_token"])) {
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
                // When email verified redirect to sign in page
                if ($verified == 1) {
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
                        header("Location: sign_in.php");
                        die();
                    } else {
                        // Set verified token to be 1 in db
                        $stmt = $conn->prepare("UPDATE Account SET verified = 1 WHERE email = ? AND verification_token = ?");
                        $stmt->bind_param("ss", $email, $verification_token);
                        $stmt->execute();
                        $rows_changed = $stmt->affected_rows;
                        $stmt->close();

                        if ($rows_changed > 0) {
                            // Email verified
                        } else {
                            // Token has expired
                        }

                        header("Location: sign_in.php");
                        die();
                    }
                }
            } else {
                // Redirect to sign in page
                header("Location: sign_in.php");
                die();
            }
        }
    }
?>