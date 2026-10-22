<?php

session_start();
require_once "../database.php";

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Check tournament ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: tournaments.php");
    exit();
}

$tournament_id = intval($_GET["id"]);

// Delete tournament
$sql = "DELETE FROM tournaments WHERE tournament_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $tournament_id);

$stmt->execute();

$stmt->close();

header("Location: tournaments.php");
exit();

?>