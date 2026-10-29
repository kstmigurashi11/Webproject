<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . '/../database.php');

if (!isset($_GET['id'])) {
    header("Location: match_results.php");
    exit();
}

$result_id = intval($_GET['id']);

$error = "";


/* =========================
   UPDATE RESULT
   ========================= */

if (isset($_POST['update_result'])) {

    $team1_score = intval($_POST['team1_score']);
    $team2_score = intval($_POST['team2_score']);
    $remarks = trim($_POST['remarks']);


    /*
     * Get fixture information
     */

    $fixture_stmt = mysqli_prepare(
        $conn,
        "SELECT
            fixtures.team1,
            fixtures.team2

         FROM match_results

         INNER JOIN fixtures
         ON match_results.fixture_id
            = fixtures.fixture_id

         WHERE match_results.result_id = ?"
    );

    mysqli_stmt_bind_param(
        $fixture_stmt,
        "i",
        $result_id
    );

    mysqli_stmt_execute($fixture_stmt);

    $fixture_result =
        mysqli_stmt_get_result($fixture_stmt);

    $fixture =
        mysqli_fetch_assoc($fixture_result);

    mysqli_stmt_close($fixture_stmt);


    if (!$fixture) {

        $error = "Fixture not found.";

    } else {


        /*
         * Determine winner
         */

        if ($team1_score > $team2_score) {

            $winner = $fixture['team1'];

        } elseif ($team2_score > $team1_score) {

            $winner = $fixture['team2'];

        } else {

            $winner = "Draw";
        }


        /*
         * Update result
         */

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE match_results

             SET team1_score = ?,
                 team2_score = ?,
                 winner = ?,
                 remarks = ?

             WHERE result_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iissi",
            $team1_score,
            $team2_score,
            $winner,
            $remarks,
            $result_id
        );


        if (mysqli_stmt_execute($stmt)) {

            header("Location: match_results.php");
            exit();

        } else {

            $error = "Error updating result.";
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================
   GET CURRENT RESULT
   ========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        match_results.*,
        fixtures.team1,
        fixtures.team2,
        fixtures.match_date,
        fixtures.match_time,
        fixtures.venue,
        tournaments.tournament_name

     FROM match_results

     INNER JOIN fixtures
     ON match_results.fixture_id
        = fixtures.fixture_id

     INNER JOIN tournaments
     ON fixtures.tournament_id
        = tournaments.tournament_id

     WHERE match_results.result_id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $result_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$match_result =
    mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$match_result) {

    header("Location: match_results.php");
    exit();

}

?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Match Result</title>

    <link rel="stylesheet"
          href="../css/style.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>

<div class="dashboard-container">

    <h1>Edit Match Result</h1>


    <p>

        <?php

        echo htmlspecialchars(
            $match_result['tournament_name']
        );

        ?>

    </p>


    <h3>

        <?php

        echo htmlspecialchars(
            $match_result['team1']
        );

        ?>

        VS

        <?php

        echo htmlspecialchars(
            $match_result['team2']
        );

        ?>

    </h3>


    <?php if ($error != "") { ?>

        <p class="error-message">

            <?php

            echo htmlspecialchars($error);

            ?>

        </p>

    <?php } ?>


    <form method="POST">

        <label>Team 1 Score</label>

        <input
            type="number"
            name="team1_score"
            min="0"
            value="<?php
            echo $match_result['team1_score'];
            ?>"
            required
        >


        <label>Team 2 Score</label>

        <input
            type="number"
            name="team2_score"
            min="0"
            value="<?php
            echo $match_result['team2_score'];
            ?>"
            required
        >


        <label>Remarks</label>

        <textarea
            name="remarks"
            rows="4"
        ><?php

        echo htmlspecialchars(
            $match_result['remarks']
        );

        ?></textarea>


        <button
            type="submit"
            name="update_result"
            class="btn"
        >
            Update Result
        </button>


        <a
            href="match_results.php"
            class="btn"
        >
            Cancel
        </a>

    </form>

</div>

<script src="../js/home.js"></script>

</body>

</html>