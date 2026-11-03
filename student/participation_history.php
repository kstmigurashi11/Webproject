<?php
session_start();
include(__DIR__ . '/../database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


/* GET PARTICIPATION HISTORY */

$sql = "SELECT
            participation.participation_id,
            participation.participation_date,
            participation.participation_type,
            participation.performance_notes,

            tournaments.tournament_name,
            tournaments.sport_type,

            matches.match_id

        FROM participation

        LEFT JOIN tournaments
            ON participation.tournament_id = tournaments.tournament_id

        LEFT JOIN matches
            ON participation.match_id = matches.match_id

        WHERE participation.user_id = ?

        ORDER BY participation.participation_date DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

?>

<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Participation History</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="dashboard-layout">

    <div class="sidebar">

    <h2><a href="index.php"><font size="6"><b>
        SportsHub</b></font></a>
    </h2>

    <a href="dashboard.php">
        <span class="menu-icon">
            <i data-lucide="gauge"></i>
        </span>
        <span>Dashboard</span>
    </a>

    <a href="clubs.php" class="active">
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

    <main class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">

        <div class="content-box">

    <div class="container">

   
    <h1>My Participation History</h1>


    <table>

        <tr>

            <th>Date</th>

            <th>Tournament</th>

            <th>Sport</th>

            <th>Match ID</th>

            <th>Participation Type</th>

            <th>Performance Notes</th>

        </tr>


        <?php if ($result->num_rows > 0) { ?>

            <?php while ($row = $result->fetch_assoc()) { ?>

                <tr>

                    <td>
                        <?php
                        echo $row['participation_date'];
                        ?>
                    </td>


                    <td>

                        <?php

                        if ($row['tournament_name']) {

                            echo htmlspecialchars(
                                $row['tournament_name']
                            );

                        } else {

                            echo "N/A";

                        }

                        ?>

                    </td>


                    <td>

                        <?php

                        if ($row['sport_type']) {

                            echo htmlspecialchars(
                                $row['sport_type']
                            );

                        } else {

                            echo "N/A";

                        }

                        ?>

                    </td>


                    <td>

                        <?php

                        if ($row['match_id']) {

                            echo $row['match_id'];

                        } else {

                            echo "N/A";

                        }

                        ?>

                    </td>


                    <td>

                        <?php

                        if ($row['participation_type']) {

                            echo htmlspecialchars(
                                $row['participation_type']
                            );

                        } else {

                            echo "N/A";

                        }

                        ?>

                    </td>


                    <td>

                        <?php

                        if ($row['performance_notes']) {

                            echo htmlspecialchars(
                                $row['performance_notes']
                            );

                        } else {

                            echo "No notes";

                        }

                        ?>

                    </td>

                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>

                <td colspan="6" class="empty">

                    You have no participation records yet.

                </td>

            </tr>

        <?php } ?>

    </table>

</div>

<script src="../js/script.js"></script>
<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>

</html>