<?php include("connection.php") ?><?php
    // Check if called with AJAX
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: index.php");
    }

    $itemId = !empty(trim($_POST["itemId"])) ? trim($_POST["itemId"]) : "";
    $listId = !empty(trim($_POST["listId"])) ? trim($_POST["listId"]) : "";

    $itemIdErr = (empty($listId) ? "No item specified." : "");
    $listIdErr = (empty($listId) ? "No list specified." : "");

    // Check if any errors occur in the input
    if (empty($itemIdErr) && empty($listIdErr)) {
        // Get access to session variables
        session_start();

        $stmt = $conn->prepare("SELECT Item.item_id FROM Item INNER JOIN List ON Item.list_id = List.list_id WHERE List.account_id = ? AND Item.list_id = ? AND Item.item_id = ?");
        $stmt->bind_param("sss", $_SESSION["account_id"], $listId, $itemId);
        $stmt->execute();
        $stmt->bind_result($item_id);
        $stmt->fetch();
        $stmt->close();

        // Sets new list title
        $stmt = $conn->prepare("UPDATE Item SET is_complete = 0 WHERE item_id = ?");
        $stmt->bind_param("s", $item_id);
        $stmt->execute();
        $stmt->close();

        //Return the data back to caller
        echo json_encode(array("success" => true,"error" => false));
    } else {
        //Return the data back to caller
        echo json_encode(array("success" => false,"error" => $itemIdErr));
    }
?>