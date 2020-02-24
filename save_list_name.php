<?php include("connection.php") ?><?php
    // Check if called with AJAX
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: index.php");
    }

    $listModalInput = !empty(trim($_POST["listModalInput"])) ? trim($_POST["listModalInput"]) : "";
    $listId = isset($_POST["listId"]) ? $_POST["listId"] : "";

    $listModalInputErr = (empty($listModalInput) ? "Please enter a list title." : (strlen($listModalInput) > 255 ? "The title is too long." : ""));

    // Check if any errors occur in the input
    if (empty($listModalInputErr)) {
        // Get access to session variables
        session_start();

        // Sets new list title
        $stmt = $conn->prepare("UPDATE List SET name = ? WHERE list_id = ? AND account_id = ?");
        $stmt->bind_param("sss", $listModalInput, $listId, $_SESSION["account_id"]);
        $stmt->execute();
        $rows_changed = $stmt->affected_rows;
        $stmt->close();

        if ($rows_changed > 0) {
            //Return the data back to caller
            echo json_encode(array("success" => $listModalInput,"error" => false));
        } else {
            //Return the data back to caller
            echo json_encode(array("success" => "No changes made","error" => false));
        }
    } else {
        //Return the data back to caller
        echo json_encode(array("success" => false,"error" => $listModalInputErr));
    }
?>