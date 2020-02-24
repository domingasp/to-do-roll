<?php include("connection.php") ?><?php
    // Check if called with AJAX
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: index.php");
    }

    $listId = !empty(trim($_POST["listId"])) ? trim($_POST["listId"]) : "";

    $listIdErr = (empty($listId) ? "No list specified." : "");

    // Check if any errors occur in the input
    if (empty($listIdErr)) {
        // Get access to session variables
        session_start();

        // Sets new list title
        $stmt = $conn->prepare("UPDATE List SET is_deleted = 1 WHERE list_id = ? AND account_id = ?");
        $stmt->bind_param("ss", $listId, $_SESSION["account_id"]);
        $stmt->execute();
        $rows_changed = $stmt->affected_rows;
        $stmt->close();

        if ($rows_changed > 0) {
            //Return the data back to caller
            echo json_encode(array("success" => true,"error" => false));
        } else {
            //Return the data back to caller
            echo json_encode(array("success" => false,"error" => "List not associated with user."));
        }
    } else {
        //Return the data back to caller
        echo json_encode(array("success" => false,"error" => $listIdErr));
    }
?>