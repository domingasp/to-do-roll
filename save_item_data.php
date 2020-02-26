<?php include("connection.php") ?><?php
    // Check if called with AJAX
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: index.php");
    }

    $itemId = !empty(trim($_POST["itemId"])) ? trim($_POST["itemId"]) : "";
    $listId = !empty(trim($_POST["listId"])) ? trim($_POST["listId"]) : "";

    $itemTitle = !empty(trim($_POST["itemModalInput"])) ? trim($_POST["itemModalInput"]) : "";
    $itemDesc = !empty(trim($_POST["modalItemDescription"])) ? trim($_POST["modalItemDescription"]) : "";
    $itemColour = !empty(trim($_POST["modalColourInput"])) ? trim($_POST["modalColourInput"]) : "";
    $itemIsComplete = !empty(trim($_POST["modalCheckBox"])) ? trim($_POST["modalCheckBox"]) : "";

    $itemIdErr = (empty($itemId) ? "No item specified." : "");
    $listIdErr = (empty($listId) ? "No list specified." : "");

    $itemTitleErr = (empty($itemTitle) ? "No title specified." : (strlen($itemTitle) > 255 ? "The title is too long." : ""));
    $itemDescErr = (strlen($itemDesc) > 21844 ? "The description is too long." : "");
    $itemColourErr = (empty($itemColour) ? "No colour specified." : (strlen($itemColour) > 6 ? "The colour code is too long." : ( !preg_match("/[0-9a-fA-F]+/", $itemColour) ? "Colour code is not hex." : "")));
    $itemIsCompleteErr = (empty($itemIsComplete) ? "No option specified." : "");

    // Check if any errors occur in the input
    if (empty($itemIdErr) && empty($listIdErr) && empty($itemTitleErr) && empty($itemDescErr) && empty($itemColourErr) && empty($itemIsCompleteErr)) {
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
            $isComplete = $itemIsComplete == "true" ? 1 : 0;

            $stmt = $conn->prepare("UPDATE Item SET title = ?, description = ?, colour = ?, is_complete = ? WHERE item_id = ?");
            $stmt->bind_param("sssss", $itemTitle, $itemDesc, $itemColour, $isComplete, $retreivedId);
            $stmt->execute();
            $rows_changed = $stmt->affected_rows;
            $stmt->close();

            if ($rows_changed > 0) {
                //Return the data back to caller
                echo json_encode(array("success" => ["title" => $itemTitle, "desc" => $itemDesc, "colour" => $itemColour, "is_complete" => $itemIsComplete],"error" => false));
            } else {
                echo json_encode(array("success" => "No rows changed.","error" => false));
            }
        } else {
            //Return the data back to caller
            echo json_encode(array("success" => false,"error" => "Item not associated with user."));
        }
        
    } else {
        //Return the data back to caller
        echo json_encode(array("success" => false,"error" => ["itemId" => $itemIdErr, "listId" => $listIdErr, "itemTitle" => $itemTitleErr, "itemDesc" => $itemDescErr, "itemColour" => $itemColourErr, "itemIsComplete" => $itemIsCompleteErr]));
    }
?>