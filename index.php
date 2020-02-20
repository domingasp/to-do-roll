<?php include("connection.php") ?><?php
    session_start();

    if (!isset($_SESSION["account_id"])) {
        header("Location: sign_in.php");
        die();
    }

    // Sets the banner message to the session variable if it has been set
    if (isset($_SESSION["banner-msg"])) {
        $banner = $_SESSION["banner-msg"];
        if (strpos($banner, "successfully") !== false || strpos($banner, "has been sent") !== false) {
            unset($_SESSION["banner-msg"]);
        }
    }

?><!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="css/all.css">
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">

        <link rel="icon" href="assets/toDoRollFavicon.png">

        <title>To Do Roll - Homepage</title>
    </head>
    <body>
        <?php if (isset($banner)) {?>
            <div class="banner-outer <?php if (strpos($banner, "successfully") !== false) { echo "banner-success"; } ?>"><span class="banner-text"><?php echo $banner; ?></span><button class="banner-btn <?php if (strpos($banner, "successfully") !== false) { echo "banner-btn-success"; } ?>" onclick="closeBanner(this)"><i class="fas fa-times"></i></button></div>
        <?php } ?>
        <div class="header-bar">
            <a href="index.php"><img src="assets/toDoRollLogo.png" class="form-logo-center"></a>
            <a href="sign_out.php">Sign Out</a>
        </div>

        <?php
            // $stmt = $conn->prepare("SELECT * FROM List WHERE account_id = ?");
            // $stmt->bind_param("i", $_SESSION["account_id"]);
            // $stmt->execute();
            // $list_result = $stmt->get_result();
            // $stmt->close();

            // while ($row = mysqli_fetch_assoc($list_result)) {
            //     echo "<div>";
            //     print_r($row);
            //     $stmt = $conn->prepare("SELECT * FROM Item WHERE list_id = ?");
            //     $stmt->bind_param("i", $row["list_id"]);
            //     $stmt->execute();
            //     $item_result = $stmt->get_result();
            //     $stmt->close();

            //     while ($item_row = mysqli_fetch_assoc($item_result)) {
            //         echo "<div>";
            //         print_r($item_row);
            //         echo "</div>";
            //     }

            //     echo "</div>";
            // }
        ?>

        <script src="js/script.js"></script>
    </body>
</html>