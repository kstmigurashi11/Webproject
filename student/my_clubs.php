<?php

session_start();
include("../database.php");

// Check student login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION["user_id"];


$sql = "
    SELECT
        club_members.membership_id,
        club_members.status,
        club_members.joined_at,
        sports_clubs.club_name,
        sports_clubs.sport_type,
        sports_clubs.coach_name,
        sports_clubs.description

    FROM club_members

    INNER JOIN sports_clubs
        ON club_members.club_id = sports_clubs.club_id

    WHERE club_members.user_id = ?

    ORDER BY club_members.joined_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>


<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Clubs - SportsHub</title>

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

    <a href="clubs.php">
        <span class="menu-icon">
            <i data-lucide="medal"></i>
        </span>
        <span>Sports Clubs</span>
    </a>

    <a href="my_clubs.php" class="active">
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

    <main class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">

        <div class="topbar">

            <div>

                <h1>My Clubs</h1>

                <p>
                    View your sports club memberships
                </p>

            </div>

            <div>

                Welcome,
                <?php echo htmlspecialchars($_SESSION["full_name"]); ?>

            </div>

        </div>


        <div class="content-box">

            <h2>My Club Applications</h2>

            <br>


            <?php if ($result->num_rows > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Club Name</th>
                            <th>Sport</th>
                            <th>Coach</th>
                            <th>Status</th>
                            <th>Applied Date</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php while ($club = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $club["club_name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $club["sport_type"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $club["coach_name"]
                                );
                                ?>
                            </td>

                            <td>

                                <?php if ($club["status"] == "pending"): ?>

                                    <span class="btn btn-warning">
                                        Pending
                                    </span>

                                <?php elseif ($club["status"] == "approved"): ?>

                                    <span class="btn btn-success">
                                        Approved
                                    </span>

                                <?php else: ?>

                                    <span class="btn btn-danger">
                                        Rejected
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $club["joined_at"]
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <p>
                    You have not joined any sports clubs yet.
                </p>

                <br>

                <a href="clubs.php" class="btn btn-primary">
                    Browse Sports Clubs
                </a>

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
$stmt->close();
$conn->close();
?>