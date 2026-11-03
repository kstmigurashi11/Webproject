<?php

session_start();
include("../database.php");

// Check student login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: ../login.php");
    exit();
}


// --------------------------------
// GET TRAINING SESSIONS
// --------------------------------

$sql = "
    SELECT
        training_sessions.training_id,
        training_sessions.training_date,
        training_sessions.start_time,
        training_sessions.end_time,
        training_sessions.location,
        training_sessions.description,
        sports_clubs.club_name,
        sports_clubs.sport_type

    FROM training_sessions

    INNER JOIN sports_clubs
        ON training_sessions.club_id = sports_clubs.club_id

    ORDER BY
        training_sessions.training_date ASC,
        training_sessions.start_time ASC
";

$result = $conn->query($sql);

?>


<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Training Schedule - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>

<div class="dashboard-layout">


    <!-- =========================
         SIDEBAR
    ========================== -->

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

    <a href="training.php" class="active">
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

    <!-- =========================
         MAIN CONTENT
    ========================== -->

    <main class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">


        <!-- TOPBAR -->

        <div class="topbar">

            <div>

                <h1>Training Schedule</h1>

                <p>
                    View upcoming sports training sessions
                </p>

            </div>

            <div>

                Welcome,
                <?php
                echo htmlspecialchars($_SESSION["full_name"]);
                ?>

            </div>

        </div>


        <!-- =========================
             TRAINING SCHEDULE
        ========================== -->

        <div class="content-box">

            <h2>Upcoming Training Sessions</h2>

            <br>


            <?php if ($result->num_rows > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Sports Club</th>
                            <th>Sport</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Location</th>
                            <th>Description</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php while ($training = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["club_name"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["sport_type"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["training_date"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["start_time"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["end_time"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["location"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["description"]
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>


            <?php else: ?>

                <p>
                    No training sessions are currently available.
                </p>

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

<?php

$conn->close();

?>