<?php
session_start();
include(__DIR__ . '/../database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}


/* ADD PARTICIPATION */

if (isset($_POST['add_participation'])) {

    $user_id = $_POST['user_id'];
    $match_id = !empty($_POST['match_id']) ? $_POST['match_id'] : NULL;
    $tournament_id = !empty($_POST['tournament_id']) ? $_POST['tournament_id'] : NULL;
    $participation_date = $_POST['participation_date'];
    $participation_type = $_POST['participation_type'];
    $performance_notes = $_POST['performance_notes'];


    $sql = "INSERT INTO participation
            (
                user_id,
                match_id,
                tournament_id,
                participation_date,
                participation_type,
                performance_notes
            )
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iiisss",
        $user_id,
        $match_id,
        $tournament_id,
        $participation_date,
        $participation_type,
        $performance_notes
    );

    $stmt->execute();

    header("Location: participation.php");
    exit();
}


/* DELETE PARTICIPATION */

if (isset($_GET['delete'])) {

    $participation_id = $_GET['delete'];

    $sql = "DELETE FROM participation
            WHERE participation_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $participation_id
    );

    $stmt->execute();

    header("Location: participation.php");
    exit();
}


/* GET STUDENTS */

$sql = "SELECT user_id, full_name
        FROM users
        WHERE role = 'student'
        ORDER BY full_name";

$students = $conn->query($sql);


/* GET TOURNAMENTS */

$sql = "SELECT tournament_id, tournament_name
        FROM tournaments
        ORDER BY tournament_name";

$tournaments = $conn->query($sql);


/* GET PARTICIPATION RECORDS */

$sql = "SELECT
            participation.participation_id,
            participation.participation_date,
            participation.participation_type,
            participation.performance_notes,

            users.full_name,

            tournaments.tournament_name,
            tournaments.sport_type

        FROM participation

        INNER JOIN users
            ON participation.user_id = users.user_id

        LEFT JOIN tournaments
            ON participation.tournament_id =
               tournaments.tournament_id

        ORDER BY participation.participation_date DESC";

$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Participation Management - SportsHub Admin</title>

    <link rel="stylesheet" href="../css/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
 </head>
</head>

<body>

<div class="dashboard-layout">

    <!-- ADMIN SIDEBAR -->
    <aside class="sidebar">

        <h2>🏆 SportsHub Admin</h2>

        <a href="dashboard.php">Dashboard</a>
        <a href="clubs.php">Sports Clubs</a>
        <a href="members.php">Membership Approvals</a>
        <a href="training.php">Training Sessions</a>
        <a href="matches.php">Matches</a>
        <a href="tournaments.php">Tournaments</a>
        <a href="fixtures.php">Tournament Fixtures</a>
        <a href="match_results.php">Match Results</a>
        <a href="facilities.php">Facilities</a>
        <a href="bookings.php">Booking Requests</a>
        <a href="participation.php" class="active">Participation</a>
        <a href="reports.php">Reports & Statistics</a>

        <a href="../logout.php" class="logout-btn">
            Logout
        </a>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- TOP BAR -->
        <div class="topbar">

            <div>
                <h1>Participation Management</h1>
                <p>Manage student sports participation records.</p>
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

        <!-- ADD PARTICIPATION FORM -->
        <div class="content-box admin-section">

            <h2>Add Participation Record</h2>

            <form method="POST" class="admin-form-grid">

                <!-- STUDENT -->
                <div class="form-group">

                    <label for="user_id">Student</label>

                    <select id="user_id"
                            name="user_id"
                            required>

                        <option value="">
                            Select Student
                        </option>

                        <?php while ($student = $students->fetch_assoc()) { ?>

                            <option value="<?php echo (int)$student['user_id']; ?>">
                                <?php
                                echo htmlspecialchars(
                                    $student['full_name']
                                );
                                ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- TOURNAMENT -->
                <div class="form-group">

                    <label for="tournament_id">Tournament</label>

                    <select id="tournament_id"
                            name="tournament_id">

                        <option value="">
                            Select Tournament
                        </option>

                        <?php while ($tournament = $tournaments->fetch_assoc()) { ?>

                            <option value="<?php echo (int)$tournament['tournament_id']; ?>">
                                <?php
                                echo htmlspecialchars(
                                    $tournament['tournament_name']
                                );
                                ?>
                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- MATCH ID -->
                <div class="form-group">

                    <label for="match_id">Match ID (Optional)</label>

                    <input
                        type="number"
                        id="match_id"
                        name="match_id"
                        placeholder="Enter match ID"
                        min="1"
                    >

                </div>

                <!-- PARTICIPATION DATE -->
                <div class="form-group">

                    <label for="participation_date">
                        Participation Date
                    </label>

                    <input
                        type="date"
                        id="participation_date"
                        name="participation_date"
                        required
                    >

                </div>

                <!-- PARTICIPATION TYPE -->
                <div class="form-group">

                    <label for="participation_type">
                        Participation Type
                    </label>

                    <input
                        type="text"
                        id="participation_type"
                        name="participation_type"
                        placeholder="Example: Player"
                    >

                </div>

                <!-- PERFORMANCE NOTES -->
                <div class="form-group">

                    <label for="performance_notes">
                        Performance Notes
                    </label>

                    <textarea
                        id="performance_notes"
                        name="performance_notes"
                        rows="4"
                        placeholder="Enter performance details..."
                    ></textarea>

                </div>

                <!-- SUBMIT BUTTON -->
                <div class="full-width">

                    <button
                        type="submit"
                        name="add_participation"
                        class="btn btn-primary"
                    >
                        Add Participation
                    </button>

                </div>

            </form>

        </div>

        <!-- PARTICIPATION RECORDS -->
        <div class="content-box admin-section">

            <h2>All Participation Records</h2>

            <!-- SEARCH -->
            <div class="form-group">

                <input
                    type="text"
                    id="participationSearch"
                    placeholder="Search participation..."
                    style="max-width: 400px;"
                >

            </div>

            <!-- TABLE -->
            <div class="admin-table-wrapper">

                <table id="participationTable">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Tournament</th>
                            <th>Sport</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Performance Notes</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($result->num_rows > 0) { ?>

                        <?php while ($row = $result->fetch_assoc()) { ?>

                            <tr>

                                <td>
                                    <?php echo (int)$row['participation_id']; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['full_name']); ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['tournament_name'] ?? 'N/A'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['sport_type'] ?? 'N/A'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['participation_date']
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['participation_type'] ?: 'N/A'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['performance_notes'] ?: 'No notes'
                                    );
                                    ?>
                                </td>

                                <td>

                                    <a
                                        href="participation.php?delete=<?php echo (int)$row['participation_id']; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this participation record?');"
                                    >
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="8">
                                No participation records found.
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
    searchTable("participationSearch", "participationTable");
</script>

</body>
</html>
