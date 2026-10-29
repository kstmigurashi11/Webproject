<?php

session_start();
include("../database.php");

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


// Check match ID
if (!isset($_GET["id"])) {
    header("Location: matches.php");
    exit();
}

$match_id = $_GET["id"];


// Delete match
$sql = "
    DELETE FROM matches
    WHERE match_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $match_id
);

$stmt->execute();

$stmt->close();
$conn->close();


// Return to matches page
header("Location: matches.php");
exit();

?>