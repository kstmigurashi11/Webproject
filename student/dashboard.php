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
<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - NSBM SportsHub</title>

    <link rel="stylesheet" href="../CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="dark-mode">

<div class="dashboard-layout">


    <!-- STUDENT SIDEBAR -->

<div class="sidebar">

    <h2><a href="index.php"><font size="6"><b>
        SportsHub</b></font></a>
    </h2>

    <a href="dashboard.php" class="active">
        <span class="menu-icon">
            <i data-lucide="gauge"></i>
        </span>
        <span>Dashboard</span>
    </a>

    <a href="clubs.php">
        <span class="menu-icon">
            <i data-lucide="medal"></i>
        </span>
        <span>Sports Clubs</span>
    </a>

    <a href="my_clubs.php">
        <span class="menu-icon">
            <i data-lucide="users"></i>
        </span>
        <span>My Clubs</span>
    </a>

    <a href="training.php">
        <span class="menu-icon">
            <i data-lucide="dumbbell"></i>
        </span>
        <span>Training Schedule</span>
    </a>

    <a href="matches.php">
        <span class="menu-icon">
            <i data-lucide="volleyball"></i>
        </span>
        <span>Matches</span>
    </a>

    <a href="tournament_fixtures.php">
        <span class="menu-icon">
            <i data-lucide="trophy"></i>
        </span>
        <span>Tournaments</span>
    </a>

    <a href="book_facility.php">
        <span class="menu-icon">
            <i data-lucide="calendar-days"></i>
        </span>
        <span>Facility Bookings</span>
    </a>

    <a href="participation_history.php">
        <span class="menu-icon">
            <i data-lucide="chart-no-axes-combined"></i>
        </span>
        <span>Participation History</span>
    </a>

    <a href="match_results.php">
        <span class="menu-icon">
            <i data-lucide="award"></i>
        </span>
        <span>Match Results & Scores</span>
    </a>



        <a href="../logout.php" class="logout-btn">Logout</a>

    </div>


    <!-- MAIN CONTENT -->

    
    
    <div class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">


    

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



       

    </div>

</div>

<script src="../js/script.js"></script>
<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>

</html>