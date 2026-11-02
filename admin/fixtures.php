<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include(__DIR__ . '/../database.php');

$message = "";


/* =========================
   DELETE FIXTURE
   ========================= */

if (isset($_GET['delete'])) {

    $fixture_id = intval($_GET['delete']);

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM fixtures WHERE fixture_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $fixture_id
    );

    if (mysqli_stmt_execute($stmt)) {
        $message = "Fixture deleted successfully.";
    } else {
        $message = "Error deleting fixture.";
    }

    mysqli_stmt_close($stmt);
}


/* =========================
   ADD FIXTURE
   ========================= */

if (isset($_POST['add_fixture'])) {

    $tournament_id = intval($_POST['tournament_id']);
    $team1 = trim($_POST['team1']);
    $team2 = trim($_POST['team2']);
    $match_date = $_POST['match_date'];
    $match_time = $_POST['match_time'];
    $venue = trim($_POST['venue']);

    if ($team1 === $team2) {

        $message = "Team 1 and Team 2 cannot be the same.";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO fixtures
            (tournament_id, team1, team2, match_date, match_time, venue)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "isssss",
            $tournament_id,
            $team1,
            $team2,
            $match_date,
            $match_time,
            $venue
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Fixture added successfully.";
        } else {
            $message = "Error adding fixture.";
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================
   GET TOURNAMENTS
   ========================= */

$tournaments_result = mysqli_query(
    $conn,
    "SELECT tournament_id, tournament_name
     FROM tournaments
     ORDER BY start_date ASC"
);


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
        fixtures.venue,
        tournaments.tournament_name

     FROM fixtures

     INNER JOIN tournaments
     ON fixtures.tournament_id = tournaments.tournament_id

     ORDER BY fixtures.match_date ASC,
              fixtures.match_time ASC"
);

?>


<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tournament Fixtures - SportsHub Admin</title>

    <link rel="stylesheet" href="../css/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="dashboard-layout">

   <!-- SIDEBAR -->

<div class="sidebar">

    <h2><a href="index.php"><font size="6"><b>
        SportsHub</b></font></a>
    </h2>

    
    <div class="sidebar-menu">
    <a href="dashboard.php" class="active">
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

    <a href="match_results.php">
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
    <main class="main-content">

        <!-- TOP BAR -->
        <div class="topbar">

            <div>
                <h1>Tournament Fixtures</h1>
                <p>Create and manage tournament fixtures</p>
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

        <!-- MESSAGE -->
        <?php if ($message != "") { ?>

            <div class="content-box admin-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>

        <!-- ADD FIXTURE -->
        <div class="content-box admin-section">

            <h2>Add Tournament Fixture</h2>

            <form method="POST" class="admin-form-grid">

                <div class="form-group">
                    <label for="tournament_id">Tournament</label>

                    <select id="tournament_id"
                            name="tournament_id"
                            required>

                        <option value="">
                            Select Tournament
                        </option>

                        <?php
                        while ($tournament = mysqli_fetch_assoc($tournaments_result)) {
                        ?>

                            <option value="<?php echo $tournament['tournament_id']; ?>">
                                <?php
                                echo htmlspecialchars(
                                    $tournament['tournament_name']
                                );
                                ?>
                            </option>

                        <?php } ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="team1">Team 1</label>

                    <input type="text"
                           id="team1"
                           name="team1"
                           placeholder="Enter first team"
                           required>
                </div>

                <div class="form-group">
                    <label for="team2">Team 2</label>

                    <input type="text"
                           id="team2"
                           name="team2"
                           placeholder="Enter second team"
                           required>
                </div>

                <div class="form-group">
                    <label for="match_date">Match Date</label>

                    <input type="date"
                           id="match_date"
                           name="match_date"
                           required>
                </div>

                <div class="form-group">
                    <label for="match_time">Match Time</label>

                    <input type="time"
                           id="match_time"
                           name="match_time"
                           required>
                </div>

                <div class="form-group">
                    <label for="venue">Venue</label>

                    <input type="text"
                           id="venue"
                           name="venue"
                           placeholder="Enter venue">
                </div>

                <div class="full-width">
                    <button type="submit"
                            name="add_fixture"
                            class="btn btn-primary">
                        Add Fixture
                    </button>
                </div>

            </form>

        </div>

        <!-- FIXTURE LIST -->
        <div class="content-box admin-section">

            <h2>All Tournament Fixtures</h2>

            <div class="admin-table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tournament</th>
                            <th>Team 1</th>
                            <th>Team 2</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Venue</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($fixtures_result) > 0) { ?>

                        <?php while ($row = mysqli_fetch_assoc($fixtures_result)) { ?>

                            <tr>
                                <td>
                                    <?php echo (int)$row['fixture_id']; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['tournament_name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['team1']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['team2']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['match_date']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['match_time']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['venue'] ?? ''); ?>
                                </td>

                                <td>
                                    <div class="admin-actions">

                                        <a href="edit_fixture.php?id=<?php echo (int)$row['fixture_id']; ?>"
                                           class="btn btn-warning">
                                            Edit
                                        </a>

                                        <a href="fixtures.php?delete=<?php echo (int)$row['fixture_id']; ?>"
                                           class="btn btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this fixture?');">
                                            Delete
                                        </a>

                                    </div>
                                </td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="8">
                                No tournament fixtures available.
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
