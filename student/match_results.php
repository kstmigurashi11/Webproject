<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . '/../database.php');


$sql = "SELECT
            match_results.result_id,
            match_results.team1_score,
            match_results.team2_score,
            match_results.winner,
            match_results.remarks,

            fixtures.team1,
            fixtures.team2,
            fixtures.match_date,
            fixtures.match_time,
            fixtures.venue,

            tournaments.tournament_name,
            tournaments.sport_type

        FROM match_results

        INNER JOIN fixtures
        ON match_results.fixture_id
           = fixtures.fixture_id

        INNER JOIN tournaments
        ON fixtures.tournament_id
           = tournaments.tournament_id

        ORDER BY fixtures.match_date DESC";


$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Match Results & Scores</title>

    <link rel="stylesheet"
          href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>
<div class="dashboard-layout">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h1 class="sidebar-logo">
            <a href="../index.php"><font size="5"><b> SportsHub</b></font></a>
        </h1>

        <a href="dashboard.php">Dashboard</a>
        <ul class="sidebar-links">
          <li><a href="clubs.php"> Sports Clubs</a></li>
          <li><a href="my_clubs.php">My Clubs</a></li>
          <li><a href="training.php"> Training Schedule</a></li>
          <li><a href="matches.php">  Matches</a></li>
          <li><a href="tournament_fixtures.php">  Tournaments</a></li>
          <li><a href="book_facility.php"> Facilities</a></li>
          <li><a href="participation_history.php">  Participation History</a></li>
          <li><a href="match_results.php" class="active">  Match Results & Scores</a></li>
        </ul>
        <br>

        <a href="../logout.php" class="logout-btn">Logout</a>

    </div>

    
    <main class="main-content">

    

        <div class="topbar">
            <div>
                <h1>Match Results & Scores</h1>
                <p>
                   View tournament match results and scores.
                </p>
            </div>

            <div>
                <span>
                    Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
                </span>

                
            </div>
        </div>
    
    
    

    


    


    <div class="content-box">

        <table>

            <thead>

                <tr>

                    <th>Tournament</th>

                    <th>Sport</th>

                    <th>Match</th>

                    <th>Score</th>

                    <th>Winner</th>

                    <th>Date</th>

                    <th>Venue</th>

                    <th>Remarks</th>

                </tr>

            </thead>


            <tbody>

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($row =
                    mysqli_fetch_assoc($result)) {

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

                        <strong> VS </strong>

                        <?php

                        echo htmlspecialchars(
                            $row['team2']
                        );

                        ?>

                    </td>


                    <td>

                        <strong>

                            <?php

                            echo $row['team1_score'];

                            ?>

                            -

                            <?php

                            echo $row['team2_score'];

                            ?>

                        </strong>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $row['winner']
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

                        echo htmlspecialchars(
                            $row['venue']
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $row['remarks']
                        );

                        ?>

                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="8">

                        No match results available.

                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

    </div>

</div>
<script src="../js/home.js"></script>

</body>

</html>

