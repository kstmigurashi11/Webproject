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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Participation History</title>

    <link rel="stylesheet"
          href="../css/style.css">

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #007bff;
        }

        h1 {
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .empty {
            text-align: center;
            padding: 25px;
            background-color: white;
        }

    </style>

</head>

<body>

<div class="dashboard-layout">

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

     <div class="main-content">

        <div class="content-box">

    <div class="container">

    <a href="dashboard.php" class="back-btn">
        ← Back to Dashboard
    </a>

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

</body>

</html>