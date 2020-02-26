<?php include("connection.php") ?><?php
    // Check if called with AJAX
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: index.php");
    }

    $itemId = !empty(trim($_POST["itemId"])) ? trim($_POST["itemId"]) : "";
    $listId = !empty(trim($_POST["listId"])) ? trim($_POST["listId"]) : "";

    $itemIdErr = (empty($itemId) ? "No item specified." : "");
    $listIdErr = (empty($listId) ? "No list specified." : "");

    // Check if any errors occur in the input
    if (empty($listIdErr) && empty($itemIdErr)) {
        // Get access to session variables
        session_start();

        // Retreive the item id that is needed
        $stmt = $conn->prepare("SELECT item_id FROM Item INNER JOIN List ON Item.list_id = List.list_id WHERE List.account_id = ? AND Item.list_id = ? AND Item.item_id = ?");
        $stmt->bind_param("sss", $_SESSION["account_id"], $listId, $itemId);
        $stmt->execute();
        $stmt->bind_result($retreivedId);
        $stmt->store_result();
        $stmt->fetch();
        $number = $stmt->num_rows;
        $stmt->close();

        // If item id is retreived then update the values in the database
        if ($number > 0) {
            // Sets new list title
            $stmt = $conn->prepare("UPDATE Item SET is_deleted = 0 WHERE item_id = ?");
            $stmt->bind_param("s", $itemId);
            $stmt->execute();
            $rows_changed = $stmt->affected_rows;
            $stmt->close();

            if ($rows_changed > 0) {
                //Return the data back to caller
                echo json_encode(array("success" => true,"error" => false));
            } else {
                echo json_encode(array("success" => "No rows changed.","error" => false));
            }
        } else {
            //Return the data back to caller
            echo json_encode(array("success" => false,"error" => "Item not associated with user."));
        }
    } else {
        //Return the data back to caller
        echo json_encode(array("success" => false,"error" => $itemIdErr));
    }
?>