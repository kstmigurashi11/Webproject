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
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Match Results & Scores</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>

<body>

<div class="dashboard-container">

    <h1>Match Results & Scores</h1>

    <p>
        View tournament match results and scores.
    </p>


    <a
        href="dashboard.php"
        class="btn"
    >
        Back to Dashboard
    </a>


    <hr>


    <div class="table-container">

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

</body>

</html>