<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include "../database.php";


/* =========================
   ADMIN DASHBOARD STATISTICS
========================= */

/* 1. TOTAL STUDENTS */

$sql = "SELECT COUNT(*) AS total
        FROM users
        WHERE role = 'student'";

$result = $conn->query($sql);
$total_students = (int)$result->fetch_assoc()['total'];


/* 2. TOTAL SPORTS CLUBS */

$sql = "SELECT COUNT(*) AS total
        FROM sports_clubs";

$result = $conn->query($sql);
$total_clubs = (int)$result->fetch_assoc()['total'];


/* 3. PENDING REQUESTS */

/* Pending club membership requests */
$sql = "SELECT COUNT(*) AS total
        FROM club_members
        WHERE status = 'pending'";

$result = $conn->query($sql);
$pending_memberships = (int)$result->fetch_assoc()['total'];

/* Pending facility booking requests */
$sql = "SELECT COUNT(*) AS total
        FROM bookings
        WHERE status = 'pending'";

$result = $conn->query($sql);
$pending_bookings = (int)$result->fetch_assoc()['total'];

/* Combined pending requests */
$pending_requests = $pending_memberships + $pending_bookings;


/* 4. UPCOMING MATCHES */

$sql = "SELECT COUNT(*) AS total
        FROM fixtures
        WHERE match_date >= CURDATE()";

$result = $conn->query($sql);
$upcoming_matches = (int)$result->fetch_assoc()['total'];


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - NSBM SportsHub</title>

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

        <a href="members.php">
            👥 Members
        </a>

        <a href="training.php">
            🏋 Training Sessions
        </a>

        <a href="matches.php">
            ⚽ Matches
        </a>

        <a href="tournaments.php">
            🏆 Tournaments
        </a>

        <a href="fixtures.php">
            📅 Fixtures
        </a>

        <a href="match_results.php">
            🥇 Match Results
        </a>

        <a href="facilities.php">
            🏟 Facilities
        </a>

        <a href="bookings.php">
            📋 Bookings
        </a>

        

        <a href="participation.php">
            📋 Participation Management
        </a>
        
        <a href="reports.php">
          📊 Reports & Statistics
        </a>

    </div>


    <!-- MAIN CONTENT -->

    <div class="main-content">


        <!-- TOP BAR -->

        <div class="topbar">

            <h1>Admin Dashboard</h1>

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


        <!-- DASHBOARD CARDS -->

        <div class="dashboard-cards">

            <div class="card">

                <h3>Total Students</h3>

                <div class="number">
                     <?php echo $total_students; ?>
                </div>

            </div>


            <div class="card">

                <h3>Sports Clubs</h3>

                <div class="number">
                    <?php echo $total_clubs; ?>
                </div>

            </div>


            <div class="card">

                <h3>Pending Requests</h3>

                <div class="number">
                    <?php echo $pending_requests; ?> 
                </div>

            </div>


            <div class="card">

                <h3>Upcoming Matches</h3>

                <div class="number">
                    <?php echo $upcoming_matches; ?>
                </div>

            </div>

        </div>


        <!-- CONTENT -->

        <div class="content-box">

            <h2>Welcome to NSBM SportsHub</h2>

            <br>

            <p>
                Use the sidebar to manage sports clubs,
                memberships, training sessions, matches,
                tournaments, facilities and reports.
            </p>

        </div>


    </div>

</div>

</body>

</html>