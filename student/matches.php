<?php

session_start();
require_once "../database.php";

// Check student login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    header("Location: ../login.php");
    exit();
}

// Get all matches
$sql = "SELECT 
            matches.match_id,
            sports_clubs.club_name,
            sports_clubs.sport_type,
            matches.opponent,
            matches.match_date,
            matches.match_time,
            matches.venue,
            matches.status
        FROM matches
        INNER JOIN sports_clubs
        ON matches.club_id = sports_clubs.club_id
        ORDER BY matches.match_date ASC, matches.match_time ASC";

$result = $conn->query($sql);

?>


<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Fixtures - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="dashboard-layout">

    <!-- Sidebar -->
    <div class="sidebar">

    <h2><a href="../index.php"><font size="6"><b>
        SportsHub</b></font></a>
    </h2>

    <a href="dashboard.php">
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

    <a href="matches.php" class="active">
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


    <!-- Main Content -->
    <main class="main-content student-background
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
           ">

        <!-- Topbar -->
        <div class="topbar">

            <div>
                <h1>Match Fixtures</h1>
                <p>View upcoming and completed matches.</p>
            </div>

            <div>
                <span>
                    Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
                </span>

                
            </div>

        </div>


        <!-- Match Table -->
        <div class="content-box">

            <h2>Sports Match Fixtures</h2>

            <?php if ($result->num_rows > 0): ?>

                <table>

                    <thead>
                        <tr>
                            <th>Club</th>
                            <th>Sport</th>
                            <th>Opponent</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Venue</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($match = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php echo htmlspecialchars($match["club_name"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($match["sport_type"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($match["opponent"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($match["match_date"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($match["match_time"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($match["venue"]); ?>
                                </td>

                                <td>

                                    <?php
                                    if ($match["status"] === "upcoming") {
                                        echo '<span class="btn btn-primary">Upcoming</span>';
                                    } elseif ($match["status"] === "completed") {
                                        echo '<span class="btn btn-success">Completed</span>';
                                    } else {
                                        echo '<span class="btn btn-danger">Cancelled</span>';
                                    }
                                    ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <p>No match fixtures available at the moment.</p>

            <?php endif; ?>

        </div>

    </main>

</div>
<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>