<?php

session_start();
include("../database.php");

// Check student login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: ../login.php");
    exit();
}


// Check club ID
if (!isset($_GET["id"])) {
    header("Location: clubs.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$club_id = $_GET["id"];


// Check if club exists
$club_sql = "SELECT club_id FROM sports_clubs WHERE club_id = ?";

$club_stmt = $conn->prepare($club_sql);
$club_stmt->bind_param("i", $club_id);
$club_stmt->execute();

$club_result = $club_stmt->get_result();

if ($club_result->num_rows == 0) {
    $club_stmt->close();

    header("Location: clubs.php");
    exit();
}

$club_stmt->close();


// Check existing membership
$check_sql = "
    SELECT membership_id, status
    FROM club_members
    WHERE user_id = ? AND club_id = ?
";

$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $user_id, $club_id);
$check_stmt->execute();

$check_result = $check_stmt->get_result();


// If membership already exists
if ($check_result->num_rows > 0) {

    $membership = $check_result->fetch_assoc();

    // If rejected, allow applying again
    if ($membership["status"] == "rejected") {

        $update_sql = "
            UPDATE club_members
            SET status = 'pending',
                joined_at = CURRENT_TIMESTAMP
            WHERE membership_id = ?
        ";

        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param(
            "i",
            $membership["membership_id"]
        );

        $update_stmt->execute();
        $update_stmt->close();

    }

    $check_stmt->close();

    header("Location: clubs.php");
    exit();
}

$check_stmt->close();


// Insert new membership
$insert_sql = "
    INSERT INTO club_members
    (user_id, club_id, status)
    VALUES (?, ?, 'pending')
";

$insert_stmt = $conn->prepare($insert_sql);

$insert_stmt->bind_param(
    "ii",
    $user_id,
    $club_id
);

$insert_stmt->execute();

$insert_stmt->close();


// Go back to clubs page
header("Location: clubs.php");
exit();

?>