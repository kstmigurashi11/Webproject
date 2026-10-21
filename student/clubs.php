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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Clubs - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<div class="dashboard-layout">

    <!-- Sidebar -->
    <aside class="sidebar">

        <h2>SportsHub</h2>

        
            <a href="dashboard.php">Dashboard</a>
            <a href="clubs.php" class="active">Sports Clubs</a>
            <a href="my_clubs.php">My Clubs</a>
            <a href="training.php">Training Schedule</a>
            <a href="matches.php">Matches</a>
            <a href="tournament_fixtures.php">Tournaments</a>
            <a href="book_facility.php">Facilities</a>
            <a href="participation_history.php">Participation History</a>
        

            <a href="../logout.php" class="logout-btn">Logout</a>

    </aside>


    <!-- Main Content -->
    <main class="main-content">

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
        <div class="content-box">

            <h2>Available Sports Clubs</h2>

            <br>

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

        </div>

    </main>

</div>

</body>
</html>