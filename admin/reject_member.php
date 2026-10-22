<?php

session_start();
include("../database.php");

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


// Check membership ID
if (!isset($_GET["id"])) {
    header("Location: members.php");
    exit();
}

$membership_id = $_GET["id"];


// Reject membership
$sql = "
    UPDATE club_members
    SET status = 'rejected'
    WHERE membership_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $membership_id
);

$stmt->execute();

$stmt->close();
$conn->close();


// Return to members page
header("Location: members.php");
exit();

?>