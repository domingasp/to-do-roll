<?php include("connection.php") ?><?php
    // Check if called with AJAX
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        header("Location: index.php");
    }

    $newListInput = !empty(trim($_POST["newListInput"])) ? trim($_POST["newListInput"]) : "";

    $newListInputErr = (empty($newListInput) ? "Please enter a list title." : (strlen($newListInput) > 255 ? "The title is too long." : ""));

    // Check if any errors occur in the input
    if (empty($newListInputErr)) {
        // Get access to session variables
        session_start();

        // Inserts the new data item in the list
        $stmt = $conn->prepare("INSERT INTO List(name, account_id) VALUES (?, ?)");
        $stmt->bind_param("ss", $newListInput, $_SESSION["account_id"]);
        $stmt->execute();
        $stmt->close();

        // Fetch most recent id
        $list_id = $conn->insert_id;

        //Return the data back to caller
        echo json_encode(array("success" => [$newListInput, $list_id],"error" => false));
    } else {
        //Return the data back to caller
        echo json_encode(array("success" => false,"error" => $newListInputErr));
    }
?>