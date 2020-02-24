<?php include("connection.php") ?><?php
    // Check if called with AJAX
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: index.php");
    }

    $newItemTextareaName = !empty(trim($_POST["newItemTextareaName"])) ? trim($_POST["newItemTextareaName"]) : "";
    $newItemListId = !empty(trim($_POST["listId"])) ? trim($_POST["listId"]) : "";

    $newItemErr = (empty($newItemTextareaName) ? "Please enter an item title." : (strlen($newItemTextareaName) > 255 ? "The title is too long." : ""));
    $newItemListIdErr = (empty($newItemListId) ? "No list specified." : "");

    // Check if any errors occur in the input
    if (empty($newItemErr) && empty($newItemListIdErr)) {
        // Get access to session variables
        session_start();
        $stmt = $conn->prepare("SELECT * FROM List WHERE list_id = ? AND account_id = ?");
        $stmt->bind_param("ss", $newItemListId, $_SESSION["account_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $number = $result->num_rows;
        $stmt->close();

        if ($number > 0) {
            // Inserts the new data item in the list
            $stmt = $conn->prepare("INSERT INTO Item(title, list_id) VALUES (?, ?)");
            $stmt->bind_param("ss", $newItemTextareaName, $newItemListId);
            $stmt->execute();
            $stmt->close();

            //Return the data back to caller
            echo json_encode(array("success" => $newItemTextareaName,"error" => false));
        } else {
            //Return the data back to caller
            echo json_encode(array("success" => false,"error" => "List not associated with user."));
        }
    } else {
        //Return the data back to caller
        echo json_encode(array("success" => false,"error" => $newItemErr));
    }
?>