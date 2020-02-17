<?php
    // Start the session and then destroy it
    session_start();
    session_destroy();

    // Redirect to the sign_in page
    header("Location: sign_in.php");
    die();
?>