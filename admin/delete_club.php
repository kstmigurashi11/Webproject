<?php

session_start();


// Check login
if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}


// Check admin
if ($_SESSION["role"] != "admin") {

    header("Location: ../login.php");
    exit();

}


include "../database.php";


// Check ID
if (!isset($_GET["id"])) {

    header("Location: clubs.php");
    exit();

}


$club_id = $_GET["id"];


// Delete club
$stmt = $conn->prepare(
    "DELETE FROM sports_clubs
     WHERE club_id = ?"
);


$stmt->bind_param(
    "i",
    $club_id
);


$stmt->execute();

$stmt->close();


// Go back to clubs
header("Location: clubs.php");

exit();

?>