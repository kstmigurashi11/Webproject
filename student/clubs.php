<?php

session_start();
include("../database.php");

// Check student login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get all sports clubs
$sql = "SELECT * FROM sports_clubs ORDER BY club_name ASC";
$result = $conn->query($sql);

?>


<html lang="en" data-theme="dark" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Clubs - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="dashboard-layout">

    <!-- Sidebar -->
    <div class="sidebar">
    <h2><a href="../index.php"><font size="6"><b>
        SportsHub</b></font></a>
    </h2>

    <a href="dashboard.php">
        <span class="menu-icon">
            <i data-lucide="gauge"></i>
        </span>
        <span>Dashboard</span>
    </a>

    <a href="clubs.php" class="active">
        <span class="menu-icon">
            <i data-lucide="medal"></i>
        </span>
        <span>Sports Clubs</span>
    </a>

    <a href="my_clubs.php">
        <span class="menu-icon">
            <i data-lucide="users"></i>
        </span>
        <span>My Clubs</span>
    </a>

    <a href="training.php">
        <span class="menu-icon">
            <i data-lucide="dumbbell"></i>
        </span>
        <span>Training Schedule</span>
    </a>

    <a href="matches.php">
        <span class="menu-icon">
            <i data-lucide="volleyball"></i>
        </span>
        <span>Matches</span>
    </a>

    <a href="tournament_fixtures.php">
        <span class="menu-icon">
            <i data-lucide="trophy"></i>
        </span>
        <span>Tournaments</span>
    </a>

    <a href="book_facility.php">
        <span class="menu-icon">
            <i data-lucide="calendar-days"></i>
        </span>
        <span>Facility Bookings</span>
    </a>

    <a href="participation_history.php">
        <span class="menu-icon">
            <i data-lucide="chart-no-axes-combined"></i>
        </span>
        <span>Participation History</span>
    </a>

    <a href="match_results.php">
        <span class="menu-icon">
            <i data-lucide="award"></i>
        </span>
        <span>Match Results & Scores</span>
    </a>

    <a href="../logout.php" class="logout-btn">Logout</a>

    </div>


    <!-- Main Content -->
    <main class="main-content student-background
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h1>Sports Clubs</h1>
                <p>Browse and join sports clubs</p>
            </div>

            <div>
                Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
            </div>
        </div>


        <!-- Clubs -->
        
            <?php if ($result->num_rows > 0): ?>

                <div class="dashboard-cards">

                    <?php while ($club = $result->fetch_assoc()): ?>

                        <?php

                        // Check whether student already joined this club
                        $check_sql = "
                            SELECT status
                            FROM club_members
                            WHERE user_id = ? AND club_id = ?
                        ";

                        $check_stmt = $conn->prepare($check_sql);
                        $check_stmt->bind_param(
                            "ii",
                            $user_id,
                            $club["club_id"]
                        );

                        $check_stmt->execute();

                        $membership_result = $check_stmt->get_result();

                        $membership_status = null;

                        if ($membership_result->num_rows > 0) {
                            $membership = $membership_result->fetch_assoc();
                            $membership_status = $membership["status"];
                        }

                        $check_stmt->close();

                        ?>

                        <div class="card">

                            <h3>
                                <?php echo htmlspecialchars($club["club_name"]); ?>
                            </h3>

                            <p>
                                <strong>Sport:</strong>
                                <?php echo htmlspecialchars($club["sport_type"]); ?>
                            </p>

                            <p>
                                <strong>Coach:</strong>
                                <?php echo htmlspecialchars($club["coach_name"]); ?>
                            </p>

                            <p>
                                <strong>Email:</strong>
                                <?php echo htmlspecialchars($club["contact_email"]); ?>
                            </p>

                            <br>

                            <p>
                                <?php echo htmlspecialchars($club["description"]); ?>
                            </p>

                            <br>


                            <?php if ($membership_status == null): ?>

                                <a
                                    href="join_club.php?id=<?php echo $club["club_id"]; ?>"
                                    class="btn btn-primary"
                                >
                                    Join Club
                                </a>


                            <?php elseif ($membership_status == "pending"): ?>

                                <button class="btn btn-warning" disabled>
                                    Pending Approval
                                </button>


                            <?php elseif ($membership_status == "approved"): ?>

                                <button class="btn btn-success" disabled>
                                    Already Joined
                                </button>


                            <?php elseif ($membership_status == "rejected"): ?>

                                <a
                                    href="join_club.php?id=<?php echo $club["club_id"]; ?>"
                                    class="btn btn-primary"
                                >
                                    Apply Again
                                </a>

                            <?php endif; ?>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <p>No sports clubs are currently available.</p>

            <?php endif; ?>

        

    </main>

</div>
<script src="../js/script.js"></script>
<script src="../js/script.js"></script>
<script>
    lucide.createIcons();
</script>
</body>
</html>