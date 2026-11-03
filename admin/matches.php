<?php

session_start();
include("../database.php");

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


// ========================================
// ADD MATCH
// ========================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $club_id = $_POST["club_id"];
    $opponent = $_POST["opponent"];
    $match_date = $_POST["match_date"];
    $match_time = $_POST["match_time"];
    $venue = $_POST["venue"];
    $status = $_POST["status"];


    // Validate required fields
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

        $sql = "
            INSERT INTO matches
            (
                club_id,
                opponent,
                match_date,
                match_time,
                venue,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "isssss",
            $club_id,
            $opponent,
            $match_date,
            $match_time,
            $venue,
            $status
        );


        if ($stmt->execute()) {

            $success = "Match added successfully.";

        } else {

            $error = "Failed to add match.";

        }

        $stmt->close();
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


// ========================================
// GET MATCHES
// ========================================

$matches_sql = "
    SELECT
        matches.match_id,
        matches.opponent,
        matches.match_date,
        matches.match_time,
        matches.venue,
        matches.status,
        sports_clubs.club_name

    FROM matches

    INNER JOIN sports_clubs
        ON matches.club_id = sports_clubs.club_id

    ORDER BY
        matches.match_date ASC,
        matches.match_time ASC
";

$matches_result = $conn->query($matches_sql);

?>


<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Matches - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>

<div class="dashboard-layout">


    <!-- ========================================
         SIDEBAR
    ========================================= -->
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


    <!-- ========================================
         MAIN CONTENT
    ========================================= -->

    <div class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">


        <!-- TOPBAR -->

        <div class="topbar">

            <div>

                <h1>Match Management</h1>

                <p>
                    Manage sports club matches
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


        <!-- ========================================
             ADD MATCH FORM
        ========================================= -->

        <div class="content-box">

            <h2>Add New Match</h2>

            <br>


            <?php if (isset($success)): ?>

                <p style="color: green;">
                    <?php echo $success; ?>
                </p>

                <br>

            <?php endif; ?>


            <?php if (isset($error)): ?>

                <p style="color: red;">
                    <?php echo $error; ?>
                </p>

                <br>

            <?php endif; ?>


            <form method="POST">


                <!-- SPORTS CLUB -->

                <div class="form-group">

                    <label>
                        Sports Club
                    </label>

                    <select
                        name="club_id"
                        required
                    >

                        <option value="">
                            Select Sports Club
                        </option>


                        <?php while ($club = $clubs_result->fetch_assoc()): ?>

                            <option
                                value="<?php
                                echo $club["club_id"];
                                ?>"
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
                        placeholder="Example: University of Colombo"
                        required
                    >

                </div>


                <!-- MATCH DATE -->

                <div class="form-group">

                    <label>
                        Match Date
                    </label>

                    <input
                        type="date"
                        name="match_date"
                        required
                    >

                </div>


                <!-- MATCH TIME -->

                <div class="form-group">

                    <label>
                        Match Time
                    </label>

                    <input
                        type="time"
                        name="match_time"
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
                        placeholder="Example: NSBM Sports Ground"
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

                        <option value="">
                            Select Status
                        </option>

                        <option value="upcoming">
                            Upcoming
                        </option>

                        <option value="completed">
                            Completed
                        </option>

                        <option value="cancelled">
                            Cancelled
                        </option>

                    </select>

                </div>


                <!-- SUBMIT -->

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Add Match
                </button>

            </form>

        </div>


        <br>


        <!-- ========================================
             MATCH TABLE
        ========================================= -->

        <div class="content-box">

            <h2>All Matches</h2>

            <br>


            <?php if ($matches_result->num_rows > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Club</th>
                            <th>Opponent</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Venue</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php while (
                        $match = $matches_result->fetch_assoc()
                    ): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $match["club_name"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $match["opponent"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $match["match_date"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $match["match_time"]
                                );
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $match["venue"]
                                );
                                ?>
                            </td>


                            <td>

                                <?php if (
                                    $match["status"] == "upcoming"
                                ): ?>

                                    <span class="btn btn-warning">
                                        Upcoming
                                    </span>


                                <?php elseif (
                                    $match["status"] == "completed"
                                ): ?>

                                    <span class="btn btn-success">
                                        Completed
                                    </span>


                                <?php else: ?>

                                    <span class="btn btn-danger">
                                        Cancelled
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <a
                                    href="edit_match.php?id=<?php
                                    echo $match["match_id"];
                                    ?>"
                                    class="btn btn-success"
                                >
                                    Edit
                                </a>


                                <a
                                    href="delete_match.php?id=<?php
                                    echo $match["match_id"];
                                    ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this match?');"
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
                    No matches have been added yet.
                </p>

            <?php endif; ?>

        </div>


    </main>

</div>

<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>

</html>

<?php

$conn->close();

?>