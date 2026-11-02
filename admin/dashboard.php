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

<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - NSBM SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>

<body>

<div class="dashboard-layout">


    <!-- SIDEBAR -->

<div class="sidebar">

    <h2><a href="index.php"><font size="6"><b>
        SportsHub</b></font></a>
    </h2>

    
    <div class="sidebar-menu">
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

    <a href="members.php">
        <span class="menu-icon">
            <i data-lucide="users"></i>
        </span>
        <span>Members</span>
    </a>

    <a href="training.php">
        <span class="menu-icon">
            <i data-lucide="dumbbell"></i>
        </span>
        <span>Training Sessions</span>
    </a>

    <a href="matches.php">
        <span class="menu-icon">
            <i data-lucide="volleyball"></i>
        </span>
        <span>Matches</span>
    </a>

    <a href="tournaments.php">
        <span class="menu-icon">
            <i data-lucide="trophy"></i>
        </span>
        <span>Tournaments</span>
    </a>

    <a href="fixtures.php">
        <span class="menu-icon">
            <i data-lucide="calendar-days"></i>
        </span>
        <span>Fixtures</span>
    </a>

    <a href="match_results.php">
        <span class="menu-icon">
            <i data-lucide="award"></i>
        </span>
        <span>Match Results</span>
    </a>

    <a href="facilities.php">
        <span class="menu-icon">
            <i data-lucide="building-2"></i>
        </span>
        <span>Facilities</span>
    </a>

    <a href="bookings.php">
        <span class="menu-icon">
            <i data-lucide="clipboard-list"></i>
        </span>
        <span>Bookings</span>
    </a>

    <a href="participation.php">
        <span class="menu-icon">
            <i data-lucide="chart-pie"></i>
        </span>
        <span>Participation Management</span>
    </a>

    <a href="reports.php">
        <span class="menu-icon">
            <i data-lucide="chart-no-axes-combined"></i>
        </span>
        <span>Reports & Statistics</span>
    </a>
</div>
    
    
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




    </div>

</div>

<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>

</html>