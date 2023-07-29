<?php
require_once "configuration.php";
require_once "backend.php";

session_start();


if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}



// Check if the form was submitted and the action parameter is set
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];
    $user_id = getUserId($connection, $_SESSION["username"]);
    $pet = fetchPetData($connection, $user_id);

    switch ($action) {
        case "feed":
            updatePetStatus($connection, $pet["id"], 10, 0, 0);
            break;
        case "play":
            updatePetStatus($connection, $pet["id"], 0, 10, 0);
            break;
        case "clean":
            updatePetStatus($connection, $pet["id"], 0, 0, 10);
            break;
        case "restart":
            updatePetStatus($connection, $pet["id"], 100, 100, 100);
            break;
        default:
            break;
    }

    header("Location: backend.php"); // Redirect back to the main page
    exit;
}
?>
