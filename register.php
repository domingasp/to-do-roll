<?php include("connection.php") ?><?php
    session_start();

    // If signed in send them to main page
    if (isset($_SESSION["account_id"])) {
        header("Location: index.php");
        die();
    } else {
        // If form submitted then try to sign user in
        if ($_POST) {
            $name = filter_var(strtolower(trim($_POST["name"])), FILTER_SANITIZE_STRING);
            $email = filter_var(strtolower(trim($_POST["email"])), FILTER_SANITIZE_STRING);
            $password = $_POST["password"];

            // Checks if any input fields are empty and if email is valid
            $name_err = (empty($name) ? "Please enter a name." : (strlen($name) > 255 ? "The name is too long." : ""));
            $email_err = (empty($email) ? "Please enter an email." : (strlen($email) > 255 ? "The email is too long." : (!filter_var($email, FILTER_VALIDATE_EMAIL) ? "Email format is incorrect." : "")));
            $password_err = (empty($password) ? "Please enter a password." : ((strlen($password) < 8) ? "Password should be 8 characters or longer." : ""));

            // If no errors where found create an account
            if (empty($name_err) && empty($email_err) && empty($password_err)) {

                // Checks if email already exists in DB
                $stmt = $conn->prepare("SELECT * FROM Account WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $number = $result->num_rows;

                $email_err = ($number > 0 ? "An account with that email already exists" : "");
                $stmt->close();

                // If email does not exist create account
                if (empty($email_err)) {
                    // Create hashed password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Generate verification token
                    $verification_token = md5(uniqid(rand(), true));

                    // Get current date and time in UTC
                    $current_time = gmdate("Y-m-d H:i:s", time());

                    // Add to database
                    $stmt = $conn->prepare("INSERT INTO Account(email, password, name, date_created, verification_token, token_date, email_last_sent) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssss", $email, $hashed_password, $name, $current_time, $verification_token, $current_time, $current_time);
                    $stmt->execute();
                    $stmt->close();

                    // Regenerate session id on form submission
                    session_regenerate_id();

                    // Redirect to Sign In page
                    header("Location: sign_in.php");
                    die();
                }
            }
        // If data not yet posted set the values for the variables to be blank(prevents "password" from being input into the password field)
        } else {
            $email = "";
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

        <title>To Do Roll - Register</title>
    </head>
    <body>
        <img src="assets/toDoRollLogo.png" class="form-logo-center">
        <div class="form-outer-div">
            <h1 class="form-h1">Register</h1>
            <form class="main-form" action="register.php" onsubmit="return validateForm()" method="post" novalidate>
                <p class="form-instructions">Enter your details to create an account.</p>

                <label class="form-label" for="name">Name</label>
                <input class="form-input <?php if(!empty($name_err)) echo "invalid-input"; ?>" id="name" name="name" type="text" value="<?php echo $name; ?>">
                <?php if(!empty($name_err)) { echo "<p class='input-error'><i class='fas fa-exclamation-circle'></i> " . $name_err . "</p>"; } ?>

                <label class="form-label" for="email">Email</label>
                <input class="form-input <?php if(!empty($email_err)) echo "invalid-input"; ?>" id="email" name="email" type="email" value="<?php echo $email; ?>">
                <?php if(!empty($email_err)) { echo "<p class='input-error'><i class='fas fa-exclamation-circle'></i> " . $email_err . "</p>"; } ?>

                <label class="form-label" for="password">Password</label>
                <input class="form-input <?php if(!empty($password_err)) echo "invalid-input"; ?>" id="password" name="password" type="password" value="<?php echo $password; ?>">
                <?php if(!empty($password_err)) { echo "<p class='input-error'><i class='fas fa-exclamation-circle'></i> " . $password_err . "</p>"; } ?>

                <div class="form-checkbox">
                    <input type="checkbox" id="showPasswordCheckbox" onclick="togglePasswordView()">
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