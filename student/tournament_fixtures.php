<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . '/../database.php');


$sql = "SELECT
            fixtures.fixture_id,
            fixtures.team1,
            fixtures.team2,
            fixtures.match_date,
            fixtures.match_time,
            fixtures.venue,
            tournaments.tournament_name,
            tournaments.sport_type

        FROM fixtures

        INNER JOIN tournaments

        ON fixtures.tournament_id
        = tournaments.tournament_id

        ORDER BY
            fixtures.match_date ASC,
            fixtures.match_time ASC";


$result = mysqli_query($conn, $sql);

?>


<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tournament Fixtures</title>

    <link rel="stylesheet"
          href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="dashboard-layout">

    <!-- SIDEBAR -->
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

    <a href="clubs.php" >
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

    <a href="tournament_fixtures.php" class="active">
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

    <!-- PAGE CONTENT -->
    <main class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">

        <div class="topbar">
            <div>
                <h1>Tournament Fixtures</h1>
                <p>View upcoming and completed matches.</p>
            </div>

             <div>
                <span>
                    Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
                </span>

                
            </div>
        </div>

        <!-- Table -->
        <div class="content-box">

        

        <table>

            <thead>

                <tr>

                    <th>Tournament</th>

                    <th>Sport</th>

                    <th>Team 1</th>

                    <th>Team 2</th>

                    <th>Date</th>

                    <th>Time</th>

                    <th>Venue</th>

                </tr>

            </thead>


            <tbody>

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {

            ?>

                <tr>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['tournament_name']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['sport_type']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['team1']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['team2']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $row['match_date'];
                        ?>
                    </td>

                    <td>
                        <?php
                        echo $row['match_time'];
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $row['venue']
                        );
                        ?>
                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="7">
                        No tournament fixtures available.
                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

    </div>

</div>

<script src="../js/script.js"></script>
<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>


</body>

</html>