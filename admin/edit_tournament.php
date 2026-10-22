<?php

session_start();
require_once "../database.php";

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Get tournament ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: tournaments.php");
    exit();
}

$tournament_id = intval($_GET["id"]);

// Get tournament details
$sql = "SELECT * FROM tournaments WHERE tournament_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tournament_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: tournaments.php");
    exit();
}

$tournament = $result->fetch_assoc();

$stmt->close();


// UPDATE TOURNAMENT
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tournament_name = trim($_POST["tournament_name"]);
    $sport_type = trim($_POST["sport_type"]);
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $venue = trim($_POST["venue"]);
    $description = trim($_POST["description"]);

    if ($end_date < $start_date) {

        $error = "End date cannot be before start date.";

    } else {

        $sql = "UPDATE tournaments
                SET tournament_name = ?,
                    sport_type = ?,
                    start_date = ?,
                    end_date = ?,
                    venue = ?,
                    description = ?
                WHERE tournament_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssssi",
            $tournament_name,
            $sport_type,
            $start_date,
            $end_date,
            $venue,
            $description,
            $tournament_id
        );

        if ($stmt->execute()) {

            header("Location: tournaments.php");
            exit();

        } else {

            $error = "Failed to update tournament.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Tournament - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="dashboard-layout">

    <aside class="sidebar">

        <h2>SportsHub</h2>

        <ul>

            <li><a href="dashboard.php">Dashboard</a></li>

            <li><a href="clubs.php">Sports Clubs</a></li>

            <li><a href="members.php">Members</a></li>

            <li><a href="training.php">Training</a></li>

            <li><a href="matches.php">Matches</a></li>

            <li>
                <a href="tournaments.php" class="active">
                    Tournaments
                </a>
            </li>

            <li><a href="#">Fixtures</a></li>

            <li><a href="#">Results</a></li>

            <li><a href="#">Facilities</a></li>

            <li><a href="#">Bookings</a></li>

            <li><a href="#">Reports</a></li>

        </ul>

    </aside>


    <main class="main-content">

        <div class="topbar">

            <div>

                <h1>Edit Tournament</h1>

                <p>Update tournament information.</p>

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


        <div class="content-box">

            <h2>Edit Tournament</h2>

            <?php if (isset($error)): ?>

                <p>
                    <?php echo htmlspecialchars($error); ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <div class="form-group">

                    <label>Tournament Name</label>

                    <input
                        type="text"
                        name="tournament_name"
                        value="<?php echo htmlspecialchars($tournament["tournament_name"]); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Sport Type</label>

                    <select name="sport_type" required>

                        <option value="Football"
                            <?php if ($tournament["sport_type"] === "Football") echo "selected"; ?>>
                            Football
                        </option>

                        <option value="Basketball"
                            <?php if ($tournament["sport_type"] === "Basketball") echo "selected"; ?>>
                            Basketball
                        </option>

                        <option value="Cricket"
                            <?php if ($tournament["sport_type"] === "Cricket") echo "selected"; ?>>
                            Cricket
                        </option>

                        <option value="Volleyball"
                            <?php if ($tournament["sport_type"] === "Volleyball") echo "selected"; ?>>
                            Volleyball
                        </option>

                        <option value="Badminton"
                            <?php if ($tournament["sport_type"] === "Badminton") echo "selected"; ?>>
                            Badminton
                        </option>

                        <option value="Table Tennis"
                            <?php if ($tournament["sport_type"] === "Table Tennis") echo "selected"; ?>>
                            Table Tennis
                        </option>

                        <option value="Other"
                            <?php if ($tournament["sport_type"] === "Other") echo "selected"; ?>>
                            Other
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label>Start Date</label>

                    <input
                        type="date"
                        name="start_date"
                        value="<?php echo htmlspecialchars($tournament["start_date"]); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>End Date</label>

                    <input
                        type="date"
                        name="end_date"
                        value="<?php echo htmlspecialchars($tournament["end_date"]); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Venue</label>

                    <input
                        type="text"
                        name="venue"
                        value="<?php echo htmlspecialchars($tournament["venue"]); ?>"
                    >

                </div>


                <div class="form-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="4"
                    ><?php echo htmlspecialchars($tournament["description"]); ?></textarea>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Tournament
                </button>


                <a
                    href="tournaments.php"
                    class="btn"
                >
                    Cancel
                </a>

            </form>

        </div>

    </main>

</div>

</body>

</html>