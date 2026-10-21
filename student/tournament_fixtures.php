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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tournament Fixtures</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>

<body>

<div class="dashboard-layout">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h2>SportsHub</h2>

        <a href="dashboard.php">Dashboard</a>
        <a href="clubs.php">Sports Clubs</a>
        <a href="my_clubs.php">My Clubs</a>
        <a href="training.php">Training Schedule</a>
        <a href="matches.php">Matches</a>
        <a href="tournament_fixtures.php">Tournaments</a>
        <a href="book_facility.php">Facilities</a>
        <a href="participation_history.php">Participation History</a>

        <a href="../logout.php" class="logout-btn">Logout</a>

    </div>


    <!-- PAGE CONTENT -->
    <div class="main-content">

        <div class="content-box">


  
    <h1>Tournament Fixtures</h1>

    <p>
        View upcoming tournament matches.
    </p>



    <hr>


    <div class="table-container">

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

</body>

</html>