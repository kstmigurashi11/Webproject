<?php

session_start();
include("../database.php");

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


// Check training ID
if (!isset($_GET["id"])) {
    header("Location: training.php");
    exit();
}

$training_id = $_GET["id"];


// Delete training session
$sql = "
    DELETE FROM training_sessions
    WHERE training_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $training_id
);

$stmt->execute();

$stmt->close();
$conn->close();


// Return to training page
header("Location: training.php");
exit();

?>