<?php

session_start();
require_once "../database.php";

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

$message = "";

// CREATE TOURNAMENT
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tournament_name = trim($_POST["tournament_name"]);
    $sport_type = trim($_POST["sport_type"]);
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $venue = trim($_POST["venue"]);
    $description = trim($_POST["description"]);

    if (
        empty($tournament_name) ||
        empty($sport_type) ||
        empty($start_date) ||
        empty($end_date)
    ) {
        $message = "Please fill in all required fields.";
    } elseif ($end_date < $start_date) {
        $message = "End date cannot be before start date.";
    } else {

        $sql = "INSERT INTO tournaments
                (tournament_name, sport_type, start_date, end_date, venue, description)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssss",
            $tournament_name,
            $sport_type,
            $start_date,
            $end_date,
            $venue,
            $description
        );

        if ($stmt->execute()) {
            $message = "Tournament created successfully.";
        } else {
            $message = "Failed to create tournament.";
        }

        $stmt->close();
    }
}

// READ TOURNAMENTS
$sql = "SELECT * FROM tournaments
        ORDER BY start_date ASC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tournaments - SportsHub</title>

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

        <!-- TOPBAR -->
        <div class="topbar">

            <div>

                <h1>Tournament Management</h1>

                <p>Create and manage sports tournaments.</p>

            </div>

            <div>

                <span>
                    Welcome,
                    <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
                </span>

                <a href="../logout.php" class="logout-btn">
                    Logout
                </a>

            </div>

        </div>


        <!-- CREATE TOURNAMENT -->
        <div class="content-box">

            <h2>Create New Tournament</h2>

            <?php if (!empty($message)): ?>

                <p>
                    <?php echo htmlspecialchars($message); ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <div class="form-group">

                    <label>Tournament Name</label>

                    <input
                        type="text"
                        name="tournament_name"
                        placeholder="Enter tournament name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Sport Type</label>

                    <select name="sport_type" required>

                        <option value="">
                            Select Sport
                        </option>

                        <option value="Football">
                            Football
                        </option>

                        <option value="Basketball">
                            Basketball
                        </option>

                        <option value="Cricket">
                            Cricket
                        </option>

                        <option value="Volleyball">
                            Volleyball
                        </option>

                        <option value="Badminton">
                            Badminton
                        </option>

                        <option value="Table Tennis">
                            Table Tennis
                        </option>

                        <option value="Other">
                            Other
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Start Date</label>

                    <input
                        type="date"
                        name="start_date"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>End Date</label>

                    <input
                        type="date"
                        name="end_date"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Venue</label>

                    <input
                        type="text"
                        name="venue"
                        placeholder="Enter tournament venue"
                    >

                </div>


                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Enter tournament description"
                    ></textarea>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Tournament
                </button>

            </form>

        </div>


        <!-- TOURNAMENT LIST -->
        <div class="content-box">

            <h2>All Tournaments</h2>

            <?php if ($result->num_rows > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Tournament</th>

                            <th>Sport</th>

                            <th>Start Date</th>

                            <th>End Date</th>

                            <th>Venue</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($tournament = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $tournament["tournament_name"]
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $tournament["sport_type"]
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $tournament["start_date"]
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $tournament["end_date"]
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $tournament["venue"]
                                    );
                                    ?>
                                </td>


                                <td>

                                    <a
                                        href="edit_tournament.php?id=<?php echo $tournament["tournament_id"]; ?>"
                                        class="btn btn-warning"
                                    >
                                        Edit
                                    </a>


                                    <a
                                        href="delete_tournament.php?id=<?php echo $tournament["tournament_id"]; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this tournament?');"
                                    >
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <p>
                    No tournaments available.
                </p>

            <?php endif; ?>

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