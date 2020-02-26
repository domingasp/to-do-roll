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
    if (empty($itemIdErr) && empty($listIdErr)) {
        // Get access to session variables
        session_start();

        $stmt = $conn->prepare("SELECT title, description, colour, is_complete FROM Item INNER JOIN List ON Item.list_id = List.list_id WHERE List.account_id = ? AND Item.list_id = ? AND Item.item_id = ?");
        $stmt->bind_param("sss", $_SESSION["account_id"], $listId, $itemId);
        $stmt->execute();
        $stmt->bind_result($title, $desc, $current_colour, $is_complete);
        $stmt->store_result();
        $stmt->fetch();
        $number = $stmt->num_rows;
        $stmt->close();

        // Returns all the list colours the user has previously used, will help consistency in UX
        $all_colours = array();

        $stmt = $conn->prepare("SELECT DISTINCT(colour) FROM Item INNER JOIN List ON Item.list_id = List.list_id WHERE List.account_id = ?");
        $stmt->bind_param("s", $_SESSION["account_id"]);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch row by row
        while($row = $result->fetch_assoc()){
            $all_colours[] = $row["colour"];
        }
        $stmt->close();

        if ($number > 0) {
            //Return the data back to caller
            echo json_encode(array("success" => ["title" => $title, "desc" => $desc, "colour" => $current_colour, "is_complete" => $is_complete, "all_colours" => $all_colours],"error" => false));
        } else {
            //Return the data back to caller
            echo json_encode(array("success" => false,"error" => "Item not associated with user."));
        }
    } else {
        //Return the data back to caller
        echo json_encode(array("success" => false,"error" => $itemIdErr));
    }
?>