<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION["role"] != "student") {
    header("Location: ../login.php");
    exit();
}

include "../database.php";

$user_id = $_SESSION["user_id"];


/* =========================
   MY CLUBS
========================= */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM club_members
    WHERE user_id = ?
    AND status = 'approved'
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$my_clubs = $result->fetch_assoc()["total"];


/* =========================
   UPCOMING TRAINING
========================= */

$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT ts.training_id) AS total
    FROM training_sessions ts
    INNER JOIN club_members cm
        ON ts.club_id = cm.club_id
    WHERE cm.user_id = ?
    AND cm.status = 'approved'
    AND ts.training_date >= CURDATE()
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$upcoming_training = $result->fetch_assoc()["total"];


/* =========================
   UPCOMING MATCHES
========================= */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM fixtures
    WHERE match_date >= CURDATE()
");

$stmt->execute();

$result = $stmt->get_result();

$upcoming_matches = $result->fetch_assoc()["total"];


/* =========================
   MY PARTICIPATION
========================= */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM participation
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$my_participation = $result->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - NSBM SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="dashboard-layout">


    <!-- SIDEBAR -->

    <div class="sidebar">

        <h2>🏆 SportsHub</h2>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="clubs.php">
            🏅 Sports Clubs
        </a>

        <a href="my_clubs.php">
            👥 My Clubs
        </a>

        <a href="training.php">
            🏋 Training Schedule
        </a>

        <a href="matches.php">
            ⚽ Matches
        </a>

        <a href="tournament_fixtures.php">
            🏆 Tournaments
        </a>

        <a href="book_facility.php">
            📋 Facility Bookings
        </a>

        <a href="participation_history.php">
            📊 Participation History
        </a>

        <a href="match_results.php">
            🥇 Match Results & Scores
        </a>

    </div>


    <!-- MAIN CONTENT -->

    <div class="main-content">


        <!-- TOP BAR -->

        <div class="topbar">

            <h1>Student Dashboard</h1>

            <div>

                Welcome,
                <strong>
                    <?php
                    echo htmlspecialchars($_SESSION["full_name"]);
                    ?>
                </strong>

                &nbsp;

                <a href="../logout.php"
                   class="logout-btn">
                    Logout
                </a>

            </div>

        </div>


        <!-- CARDS -->

        <div class="dashboard-cards">

    <!-- MY CLUBS -->

    <a href="my_clubs.php" class="dashboard-card-link">

        <div class="card">

            <h3>My Clubs</h3>

            <div class="number">
                <?php echo $my_clubs; ?>
            </div>

            <p>View your clubs</p>

        </div>

    </a>


    <!-- UPCOMING TRAINING -->

    <a href="training.php" class="dashboard-card-link">

        <div class="card">

            <h3>Upcoming Training</h3>

            <div class="number">
                <?php echo $upcoming_training; ?>
            </div>

            <p>View training schedule</p>

        </div>

    </a>


    <!-- UPCOMING MATCHES -->

    <a href="matches.php" class="dashboard-card-link">

        <div class="card">

            <h3>Upcoming Matches</h3>

            <div class="number">
                <?php echo $upcoming_matches; ?>
            </div>

            <p>View upcoming matches</p>

        </div>

    </a>


    <!-- MY PARTICIPATION -->

    <a href="participation_history.php" class="dashboard-card-link">

        <div class="card">

            <h3>My Participation</h3>

            <div class="number">
                <?php echo $my_participation; ?>
            </div>

            <p>View participation history</p>

        </div>

    </a>

</div>



        <!-- WELCOME -->

        <div class="content-box">

            <h2>Welcome to NSBM SportsHub! 🏆</h2>

            <br>

            <p>
                Browse sports clubs, join your favourite clubs,
                view training schedules, register for tournaments
                and track your sports participation.
            </p>

        </div>


    </div>

</div>

<script src="../js/script.js"></script>

</body>

</html>