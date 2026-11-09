<?php

session_start();
include("../database.php");

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


// Check match ID
if (!isset($_GET["id"])) {
    header("Location: matches.php");
    exit();
}

$match_id = $_GET["id"];


// ========================================
// GET MATCH
// ========================================

$sql = "
    SELECT *
    FROM matches
    WHERE match_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $match_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows == 0) {

    $stmt->close();

    header("Location: matches.php");
    exit();
}

$match = $result->fetch_assoc();

$stmt->close();


// ========================================
// UPDATE MATCH
// ========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $club_id = $_POST["club_id"];
    $opponent = $_POST["opponent"];
    $match_date = $_POST["match_date"];
    $match_time = $_POST["match_time"];
    $venue = $_POST["venue"];
    $status = $_POST["status"];


    if (
        empty($club_id) ||
        empty($opponent) ||
        empty($match_date) ||
        empty($match_time) ||
        empty($venue) ||
        empty($status)
    ) {

        $error = "Please fill in all required fields.";

    } else {

        $update_sql = "
            UPDATE matches
            SET
                club_id = ?,
                opponent = ?,
                match_date = ?,
                match_time = ?,
                venue = ?,
                status = ?

            WHERE match_id = ?
        ";

        $update_stmt = $conn->prepare($update_sql);

        $update_stmt->bind_param(
            "isssssi",
            $club_id,
            $opponent,
            $match_date,
            $match_time,
            $venue,
            $status,
            $match_id
        );


        if ($update_stmt->execute()) {

            $update_stmt->close();

            header("Location: matches.php");
            exit();

        } else {

            $error = "Failed to update match.";

        }

        $update_stmt->close();
    }
}


// ========================================
// GET SPORTS CLUBS
// ========================================

$clubs_sql = "
    SELECT club_id, club_name
    FROM sports_clubs
    ORDER BY club_name ASC
";

$clubs_result = $conn->query($clubs_sql);

?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Match - SportsHub</title>

    <link rel="stylesheet" href="../CSS/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>


<body>

<div class="dashboard-layout">


    <!-- SIDEBAR -->

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

    <a href="matches.php" class="active">
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


        <div class="topbar">

            <div>

                <h1>Edit Match</h1>

                <p>
                    Update match information
                </p>

            </div>

            <div>

                Welcome,
                <?php
                echo htmlspecialchars(
                    $_SESSION["full_name"]
                );
                ?>

            </div>

        </div>


        <div class="content-box">

            <h2>Update Match</h2>

            <br>


            <?php if (isset($error)): ?>

                <p style="color: red;">
                    <?php echo $error; ?>
                </p>

                <br>

            <?php endif; ?>


            <form method="POST">


                <!-- CLUB -->

                <div class="form-group">

                    <label>
                        Sports Club
                    </label>

                    <select
                        name="club_id"
                        required
                    >

                        <?php while (
                            $club = $clubs_result->fetch_assoc()
                        ): ?>

                            <option
                                value="<?php
                                echo $club["club_id"];
                                ?>"

                                <?php
                                if (
                                    $club["club_id"]
                                    == $match["club_id"]
                                ) {
                                    echo "selected";
                                }
                                ?>
                            >

                                <?php
                                echo htmlspecialchars(
                                    $club["club_name"]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- OPPONENT -->

                <div class="form-group">

                    <label>
                        Opponent
                    </label>

                    <input
                        type="text"
                        name="opponent"
                        value="<?php
                        echo htmlspecialchars(
                            $match["opponent"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- DATE -->

                <div class="form-group">

                    <label>
                        Match Date
                    </label>

                    <input
                        type="date"
                        name="match_date"
                        value="<?php
                        echo htmlspecialchars(
                            $match["match_date"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- TIME -->

                <div class="form-group">

                    <label>
                        Match Time
                    </label>

                    <input
                        type="time"
                        name="match_time"
                        value="<?php
                        echo htmlspecialchars(
                            $match["match_time"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- VENUE -->

                <div class="form-group">

                    <label>
                        Venue
                    </label>

                    <input
                        type="text"
                        name="venue"
                        value="<?php
                        echo htmlspecialchars(
                            $match["venue"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- STATUS -->

                <div class="form-group">

                    <label>
                        Status
                    </label>

                    <select
                        name="status"
                        required
                    >

                        <option
                            value="upcoming"
                            <?php
                            if (
                                $match["status"]
                                == "upcoming"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Upcoming
                        </option>


                        <option
                            value="completed"
                            <?php
                            if (
                                $match["status"]
                                == "completed"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Completed
                        </option>


                        <option
                            value="cancelled"
                            <?php
                            if (
                                $match["status"]
                                == "cancelled"
                            ) {
                                echo "selected";
                            }
                            ?>
                        >
                            Cancelled
                        </option>

                    </select>

                </div>


                <button
                    type="submit"
                    class="btn btn-success"
                >
                    Update Match
                </button>


                <a
                    href="matches.php"
                    class="btn btn-warning"
                >
                    Cancel
                </a>

            </form>

        </div>

    </main>

</div>

<script src="../js/home.js"></script>
<script src="../js/script.js"></script>

</body>

</html>

<?php

$conn->close();

?>