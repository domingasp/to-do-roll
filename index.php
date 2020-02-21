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
        <div class="header-bar" id="headerBar">
            <div class="header-logo-name-div">
                <a href="index.php" class="header-logo"><img src="assets/toDoRollLogo.png" style="max-width: 3rem;"></a>
                <p class="header-name">Hi, <?php echo ucwords($name); ?></p>
            </div>
            <ul class="header-ul">
                <div class="header-li-inner-div">
                    <li class="header-li"><a href="#"><i class="fas fa-cog icon-space"></i>Settings</a></li>
                    <li class="header-li"><a href="sign_out.php"><i class="fas fa-door-open icon-space"></i>Sign Out</a></li>
                <div>
            </ul>
        </div>

        <!-- ##################
            ##################
            ##################

            ADD Colour Strip use a div with color
            Work on modal for adding new item
        
            ##################
            ##################
            ##################-->

        <div class="main-body-div" id="mainBodyDiv">
            <div class="list-div">
                <a href="#" class="list-title">To-Do</a>
                <div class="item-div">
                    <a href="#" class="item-a">Have a really loooooooooooooong shower Have a really loooooooooooooong shower Have a really loooooooooooooong shower Have a really loooooooooooooong shower</a>
                    <div class="item-control-div">
                        <a href="#" class="tick-a check-a"><i class="fas fa-check"></i></a>
                    </div>
                </div>
                <div class="item-div">
                    <a href="#" class="item-a">Item 2</a>
                    <div class="item-control-div">
                        <a href="#" class="tick-a check-a"><i class="fas fa-check"></i></a>
                    </div>
                </div>
                <div class="item-div">
                    <a href="#" class="item-a">Item 2</a>
                    <div class="item-control-div">
                        <a href="#" class="tick-a check-a"><i class="fas fa-check"></i></a>
                    </div>
                </div>
                <div class="item-div">
                    <a href="#" class="item-a">Item 2</a>
                    <div class="item-control-div">
                        <a href="#" class="tick-a check-a"><i class="fas fa-check"></i></a>
                    </div>
                </div>
                <div class="item-div">
                    <a href="#" class="item-a">Item 2</a>
                    <div class="item-control-div">
                        <a href="#" class="tick-a check-a"><i class="fas fa-check"></i></a>
                    </div>
                </div>
                <div class="add-item-div">
                    <a href="#" class="add-item-a"><i class="fas fa-plus icon-space"></i>New Item</a>
                </div>
            </div>

            <div class="list-div">
                <a href="#" class="list-title">Second List</a>
                <div class="item-div">
                    <a href="#" class="item-a">Item 1</a>
                    <div class="item-control-div">
                        <a href="#" class="tick-a check-a"><i class="fas fa-check"></i></a>
                    </div>
                </div>
                <div class="add-item-div">
                    <a href="#" class="add-item-a"><i class="fas fa-plus icon-space"></i>New Item</a>
                </div>
            </div>
            <div class="list-div" style="text-align:center;">
                <a href="#" class="list-title"><i class="fas fa-plus icon-space"></i>New List</a>
            </div>
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