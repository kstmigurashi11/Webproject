<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . '/../database.php');

if (!isset($_GET['id'])) {
    header("Location: fixtures.php");
    exit();
}

$fixture_id = intval($_GET['id']);

$error = "";


/* UPDATE FIXTURE */

if (isset($_POST['update_fixture'])) {

    $tournament_id = intval($_POST['tournament_id']);
    $team1 = trim($_POST['team1']);
    $team2 = trim($_POST['team2']);
    $match_date = $_POST['match_date'];
    $match_time = $_POST['match_time'];
    $venue = trim($_POST['venue']);


    if ($team1 === $team2) {

        $error = "Team 1 and Team 2 cannot be the same.";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE fixtures

             SET tournament_id = ?,
                 team1 = ?,
                 team2 = ?,
                 match_date = ?,
                 match_time = ?,
                 venue = ?

             WHERE fixture_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "isssssi",
            $tournament_id,
            $team1,
            $team2,
            $match_date,
            $match_time,
            $venue,
            $fixture_id
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: fixtures.php");
            exit();

        } else {

            $error = "Error updating fixture.";
        }

        mysqli_stmt_close($stmt);
    }
}


/* GET FIXTURE */

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM fixtures
     WHERE fixture_id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $fixture_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$fixture = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$fixture) {

    header("Location: fixtures.php");
    exit();

}


/* GET TOURNAMENTS */

$tournaments_result = mysqli_query(
    $conn,
    "SELECT tournament_id, tournament_name
     FROM tournaments
     ORDER BY start_date ASC"
);

?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Fixture</title>

    <link rel="stylesheet"
          href="../css/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>

<div class="dashboard-container">

    <h1>Edit Tournament Fixture</h1>


    <?php if ($error != "") { ?>

        <p class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php } ?>


    <form method="POST">

        <label>Tournament</label>

        <select name="tournament_id" required>

            <?php

            while ($tournament = mysqli_fetch_assoc($tournaments_result)) {

            ?>

                <option
                    value="<?php echo $tournament['tournament_id']; ?>"

                    <?php

                    if (
                        $tournament['tournament_id']
                        == $fixture['tournament_id']
                    ) {
                        echo "selected";
                    }

                    ?>
                >

                    <?php
                    echo htmlspecialchars(
                        $tournament['tournament_name']
                    );
                    ?>

                </option>

            <?php

            }

            ?>

        </select>


        <label>Team 1</label>

        <input
            type="text"
            name="team1"
            value="<?php echo htmlspecialchars($fixture['team1']); ?>"
            required
        >


        <label>Team 2</label>

        <input
            type="text"
            name="team2"
            value="<?php echo htmlspecialchars($fixture['team2']); ?>"
            required
        >


        <label>Match Date</label>

        <input
            type="date"
            name="match_date"
            value="<?php echo $fixture['match_date']; ?>"
            required
        >


        <label>Match Time</label>

        <input
            type="time"
            name="match_time"
            value="<?php echo $fixture['match_time']; ?>"
            required
        >


        <label>Venue</label>

        <input
            type="text"
            name="venue"
            value="<?php echo htmlspecialchars($fixture['venue']); ?>"
        >


        <button
            type="submit"
            name="update_fixture"
            class="btn"
        >
            Update Fixture
        </button>


        <a
            href="fixtures.php"
            class="btn"
        >
            Cancel
        </a>

    </form>

</div>
<script src="../js/home.js"></script>

</body>

</html>