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

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Fixtures - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>

<div class="dashboard-layout">

    <!-- Sidebar -->
    <aside class="sidebar">

        <h1 class="sidebar-logo">
            <a href="../index.php"><font size="5"><b>SportsHub</b></font></a>
        </h1>

        
            <a href="dashboard.php">Dashboard</a>
            <ul class="sidebar-links">
              <li><a href="clubs.php">Sports Clubs</a></li>
              <li><a href="my_clubs.php">My Clubs</a></li>
              <li><a href="training.php">Training Schedule</a></li>
              <li><a href="matches.php" class="active">Matches</a></li>
              <li><a href="tournament_fixtures.php">Tournaments</a></li>
              <li><a href="book_facility.php">Facilities</a></li>
              <li><a href="participation_history.php">Participation History</a></li>
              <li><a href="match_results.php">Match Results & Scores</a></li>
            </ul>
           

        <a href="../logout.php" class="logout-btn">Logout</a>

    

    </aside>


    <!-- Main Content -->
    <main class="main-content">

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

</body>
</html>