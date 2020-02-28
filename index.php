<?php include("connection.php") ?><?php
    session_start();

    if (!isset($_SESSION["account_id"])) {
        header("Location: sign_in.php");
        die();
    } else {
        // Get the logged in users details
        $stmt = $conn->prepare("SELECT name FROM Account WHERE account_id = ?");
        $stmt->bind_param("s", $_SESSION["account_id"]);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        $stmt->close();
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
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <script src="js/jquery-3.4.1.min.js"></script>

        <link rel="stylesheet" type="text/css" href="css/all.css">
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">

        <link rel="icon" href="assets/toDoRollFavicon.png">

        <title>To Do Roll - Homepage</title>
    </head>
    <body>
        <?php if (isset($banner)) {?>
            <div class="banner-outer <?php if (strpos($banner, "successfully") !== false) { echo "banner-success"; } ?>"><span class="banner-text"><?php echo $banner; ?></span><button class="banner-btn <?php if (strpos($banner, "successfully") !== false) { echo "banner-btn-success"; } ?>" onclick="closeBanner(this)"><i class="fas fa-times"></i></button></div>
        <?php } ?>
        <div class="header-bar" id="headerBar">
            <div class="header-logo-name-div">
                <a href="index.php" class="header-logo"><img src="assets/toDoRollLogo.png" style="max-width: 3rem;"></a>
                <p class="header-name">Hi, <?php echo ucwords($name); ?></p>
            </div>
            <ul class="header-ul">
                <div class="header-li-inner-div">
                    <li class="header-li"><a href="sign_out.php"><i class="fas fa-door-open icon-space"></i>Sign Out</a></li>
                <div>
            </ul>
        </div>

        <div class="main-body-div" id="mainBodyDiv">
            <?php
                $stmt = $conn->prepare("SELECT * FROM List WHERE account_id = ? AND is_deleted = 0");
                $stmt->bind_param("i", $_SESSION["account_id"]);
                $stmt->execute();
                $list_result = $stmt->get_result();
                $stmt->close();

                // Read in the user lists
                while ($row = mysqli_fetch_assoc($list_result)) {
                    echo "<div id=\"listDiv\" class=\"list-div\" data-id=\"" . $row["list_id"] . "\">";
                    echo "<button class=\"list-title\" onclick=\"openListModal(this)\">" . $row["name"] . "</button>";
                    $stmt = $conn->prepare("SELECT * FROM Item WHERE list_id = ? AND is_complete = 0 AND is_deleted = 0");
                    $stmt->bind_param("i", $row["list_id"]);
                    $stmt->execute();
                    $item_result = $stmt->get_result();
                    $stmt->close();

                    // Read in the items for each list
                    while ($item_row = mysqli_fetch_assoc($item_result)) {
                        echo "<div class=\"item-div\">";
                        echo "<button class=\"item-a\" style=\"border-left-color:" . "#" . $item_row["colour"] . "\" onclick=\"openItemModal(this)\" data-id=\"" . $item_row["item_id"] . "\">" . $item_row["title"] . "</button>";
                        echo "<button class=\"tick-a check-a\" onclick=\"itemComplete(this)\" data-id=\"" . $item_row["item_id"] . "\"><i class=\"fas fa-check\"></i></button>";
                        echo "</div>";
                    }
                    echo "<div class=\"add-item-div\">";
                    echo "<button onclick=\"newListItem(this)\" class=\"add-item-btn\"><i class=\"fas fa-plus icon-space\"></i>New Item</button>";
                    echo "</div>";
                    echo "</div>";
                }
            ?>

            <div class="list-div" style="text-align:center;">
                <button onclick="newList(this)" class="list-title list-title-btn"><i class="fas fa-plus icon-space"></i>New List</button>
            </div>
        </div>

        <script src="js/script.js"></script>
    </body>
</html>