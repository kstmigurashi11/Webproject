<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . '/../database.php');

$message = "";
$error = "";


/* =========================
   DELETE RESULT
   ========================= */

if (isset($_GET['delete'])) {

    $result_id = intval($_GET['delete']);

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM match_results
         WHERE result_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $result_id
    );

    if (mysqli_stmt_execute($stmt)) {
        $message = "Match result deleted successfully.";
    } else {
        $error = "Error deleting match result.";
    }

    mysqli_stmt_close($stmt);
}


/* =========================
   ADD RESULT
   ========================= */

if (isset($_POST['add_result'])) {

    $fixture_id = intval($_POST['fixture_id']);
    $team1_score = intval($_POST['team1_score']);
    $team2_score = intval($_POST['team2_score']);
    $remarks = trim($_POST['remarks']);


    /* Check whether result already exists */

    $check_stmt = mysqli_prepare(
        $conn,
        "SELECT result_id
         FROM match_results
         WHERE fixture_id = ?"
    );

    mysqli_stmt_bind_param(
        $check_stmt,
        "i",
        $fixture_id
    );

    mysqli_stmt_execute($check_stmt);

    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {

        $error = "A result already exists for this fixture.";

    } else {

        /*
         * Get the fixture teams
         */

        $fixture_stmt = mysqli_prepare(
            $conn,
            "SELECT team1, team2
             FROM fixtures
             WHERE fixture_id = ?"
        );

        mysqli_stmt_bind_param(
            $fixture_stmt,
            "i",
            $fixture_id
        );

        mysqli_stmt_execute($fixture_stmt);

        $fixture_result =
            mysqli_stmt_get_result($fixture_stmt);

        $fixture =
            mysqli_fetch_assoc($fixture_result);

        mysqli_stmt_close($fixture_stmt);


        if (!$fixture) {

            $error = "Selected fixture does not exist.";

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
             * Insert result
             */

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO match_results
                (fixture_id, team1_score, team2_score, winner, remarks)
                VALUES (?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "iiiss",
                $fixture_id,
                $team1_score,
                $team2_score,
                $winner,
                $remarks
            );


            if (mysqli_stmt_execute($stmt)) {

                $message =
                    "Match result added successfully.";

            } else {

                $error =
                    "Error adding match result.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_stmt_close($check_stmt);
}


/* =========================
   GET FIXTURES
   ========================= */

$fixtures_result = mysqli_query(
    $conn,
    "SELECT
        fixtures.fixture_id,
        fixtures.team1,
        fixtures.team2,
        fixtures.match_date,
        fixtures.match_time,
        tournaments.tournament_name

     FROM fixtures

     INNER JOIN tournaments
     ON fixtures.tournament_id
        = tournaments.tournament_id

     ORDER BY fixtures.match_date ASC"
);


/* =========================
   GET RESULTS
   ========================= */

$results_result = mysqli_query(
    $conn,
    "SELECT
        match_results.result_id,
        match_results.fixture_id,
        match_results.team1_score,
        match_results.team2_score,
        match_results.winner,
        match_results.remarks,

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

     ORDER BY fixtures.match_date DESC"
);

?>



<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Match Results & Scores - SportsHub Admin</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="dashboard-layout">

    <!-- ADMIN SIDEBAR -->
<div class="sidebar">

    <h2><a href="../index.php"><font size="6"><b>
        SportsHub</b></font></a>
    </h2>

    
    <div class="sidebar-menu">
    <a href="dashboard.php" >
        <span class="menu-icon">
            <i data-lucide="gauge"></i>
        </span>
        <span>Dashboard</span>
    </a>

    <a href="clubs.php">
        <span class="menu-icon">
            <i data-lucide="medal"></i>
        </span>
        <span>Sports Clubs</span>
    </a>

    <a href="members.php">
        <span class="menu-icon">
            <i data-lucide="users"></i>
        </span>
        <span>Members</span>
    </a>

    <a href="training.php">
        <span class="menu-icon">
            <i data-lucide="dumbbell"></i>
        </span>
        <span>Training Sessions</span>
    </a>

    <a href="matches.php">
        <span class="menu-icon">
            <i data-lucide="volleyball"></i>
        </span>
        <span>Matches</span>
    </a>

    <a href="tournaments.php">
        <span class="menu-icon">
            <i data-lucide="trophy"></i>
        </span>
        <span>Tournaments</span>
    </a>

    <a href="fixtures.php">
        <span class="menu-icon">
            <i data-lucide="calendar-days"></i>
        </span>
        <span>Fixtures</span>
    </a>

    <a href="match_results.php" class="active">
        <span class="menu-icon">
            <i data-lucide="award"></i>
        </span>
        <span>Match Results</span>
    </a>

    <a href="facilities.php">
        <span class="menu-icon">
            <i data-lucide="building-2"></i>
        </span>
        <span>Facilities</span>
    </a>

    <a href="bookings.php">
        <span class="menu-icon">
            <i data-lucide="clipboard-list"></i>
        </span>
        <span>Bookings</span>
    </a>

    <a href="participation.php">
        <span class="menu-icon">
            <i data-lucide="chart-pie"></i>
        </span>
        <span>Participation Management</span>
    </a>

    <a href="reports.php">
        <span class="menu-icon">
            <i data-lucide="chart-no-axes-combined"></i>
        </span>
        <span>Reports & Statistics</span>
    </a>
</div>
    
    
    <a href="../logout.php" class="logout-btn">Logout</a>


</div>

    <!-- MAIN CONTENT -->
    <div class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">

        <!-- TOP BAR -->
        <div class="topbar">

            <div>
                <h1>Match Results & Scores</h1>
                <p>Add and manage tournament match results.</p>
            </div>

            <div>
                Welcome,
                <strong>
                    <?php
                    echo htmlspecialchars(
                        $_SESSION['full_name'] ?? 'Admin'
                    );
                    ?>
                </strong>
            </div>

        </div>

        <!-- SUCCESS MESSAGE -->
        <?php if ($message != "") { ?>

            <div class="content-box admin-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>

        <!-- ERROR MESSAGE -->
        <?php if ($error != "") { ?>

            <div class="content-box admin-error">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php } ?>

        <!-- ADD MATCH RESULT -->
        <div class="content-box admin-section">

            <h2>Add Match Result</h2>

            <form method="POST" class="admin-form-grid">

                <!-- SELECT FIXTURE -->
                <div class="form-group">

                    <label for="fixture_id">
                        Select Fixture
                    </label>

                    <select id="fixture_id"
                            name="fixture_id"
                            required>

                        <option value="">
                            Select Fixture
                        </option>

                        <?php
                        while ($fixture =
                            mysqli_fetch_assoc($fixtures_result)) {
                        ?>

                            <option value="<?php echo (int)$fixture['fixture_id']; ?>">

                                <?php
                                echo htmlspecialchars(
                                    $fixture['tournament_name']
                                );

                                echo " - ";

                                echo htmlspecialchars(
                                    $fixture['team1']
                                );

                                echo " vs ";

                                echo htmlspecialchars(
                                    $fixture['team2']
                                );

                                echo " - ";

                                echo htmlspecialchars(
                                    $fixture['match_date']
                                );
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- TEAM 1 SCORE -->
                <div class="form-group">

                    <label for="team1_score">
                        Team 1 Score
                    </label>

                    <input
                        type="number"
                        id="team1_score"
                        name="team1_score"
                        min="0"
                        required
                    >

                </div>

                <!-- TEAM 2 SCORE -->
                <div class="form-group">

                    <label for="team2_score">
                        Team 2 Score
                    </label>

                    <input
                        type="number"
                        id="team2_score"
                        name="team2_score"
                        min="0"
                        required
                    >

                </div>

                <!-- REMARKS -->
                <div class="form-group">

                    <label for="remarks">
                        Remarks
                    </label>

                    <textarea
                        id="remarks"
                        name="remarks"
                        rows="4"
                        placeholder="Enter match remarks"
                    ></textarea>

                </div>

                <!-- SUBMIT BUTTON -->
                <div class="full-width">

                    <button
                        type="submit"
                        name="add_result"
                        class="btn btn-primary"
                    >
                        Add Result
                    </button>

                </div>

            </form>

        </div>

        <!-- ALL MATCH RESULTS -->
        <div class="content-box admin-section">

            <h2>All Match Results</h2>

            <div class="admin-table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Tournament</th>
                            <th>Match</th>
                            <th>Score</th>
                            <th>Winner</th>
                            <th>Date</th>
                            <th>Venue</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($results_result) > 0) { ?>

                        <?php while ($row =
                            mysqli_fetch_assoc($results_result)) {
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
                                    echo htmlspecialchars($row['team1']);
                                    ?>

                                    <strong> VS </strong>

                                    <?php
                                    echo htmlspecialchars($row['team2']);
                                    ?>
                                </td>

                                <td>
                                    <strong>
                                        <?php echo (int)$row['team1_score']; ?>
                                        -
                                        <?php echo (int)$row['team2_score']; ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['winner'] ?? 'N/A'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['match_date']
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['venue'] ?? ''
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['remarks'] ?? ''
                                    );
                                    ?>
                                </td>

                                <td>

                                    <div class="admin-actions">

                                        <a
                                            href="edit_result.php?id=<?php echo (int)$row['result_id']; ?>"
                                            class="btn btn-warning"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="match_results.php?delete=<?php echo (int)$row['result_id']; ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this result?');"
                                        >
                                            Delete
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="8">
                                No match results available.
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

<script src="../js/script.js"></script>
<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>
