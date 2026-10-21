<?php

session_start();
include("../database.php");

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


// Get all membership requests
$sql = "
    SELECT
        club_members.membership_id,
        club_members.status,
        club_members.joined_at,
        users.full_name,
        users.email,
        sports_clubs.club_name,
        sports_clubs.sport_type

    FROM club_members

    INNER JOIN users
        ON club_members.user_id = users.user_id

    INNER JOIN sports_clubs
        ON club_members.club_id = sports_clubs.club_id

    ORDER BY club_members.joined_at DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Club Members - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="dashboard-layout">

    <!-- Sidebar -->

    <aside class="sidebar">

        <h2>SportsHub</h2>

        

            
                <a href="dashboard.php">
                    Dashboard
                </a>
            

            
                <a href="clubs.php">
                    Sports Clubs
                </a>
            

            
                <a href="members.php" class="active">
                    Members
                </a>
            

            
                <a href="training.php">
                    Training
                </a>
            

            
                <a href="matches.php">
                    Matches
                </a>
            

            
                <a href="tournaments.php">
                    Tournaments
                </a>
            

            
                <a href="fixtures.php">
                    Fixtures
                </a>
            

            
                <a href="match_results.php">
                    Results
                </a>
            

            
                <a href="facilities.php">
                    Facilities
                </a>
            

            
                <a href="bookings.php">
                    Bookings
                </a>
            

            
                <a href="reports.php">
                    Reports
                </a>
            

        

        <a href="../logout.php" class="logout-btn">
            Logout
        </a>

    </aside>


    <!-- Main Content -->

    <main class="main-content">

        <!-- Topbar -->

        <div class="topbar">

            <div>

                <h1>Club Members</h1>

                <p>
                    Manage student club membership requests
                </p>

            </div>

            <div>

                Welcome,
                <?php echo htmlspecialchars($_SESSION["full_name"]); ?>

            </div>

        </div>


        <!-- Membership Table -->

        <div class="content-box">

            <h2>Membership Requests</h2>

            <br>

            <?php if ($result->num_rows > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Student</th>
                            <th>Email</th>
                            <th>Club</th>
                            <th>Sport</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php while ($member = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $member["full_name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $member["email"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $member["club_name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $member["sport_type"]
                                );
                                ?>
                            </td>

                            <td>

                                <?php if ($member["status"] == "pending"): ?>

                                    <span class="btn btn-warning">
                                        Pending
                                    </span>

                                <?php elseif ($member["status"] == "approved"): ?>

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
                                    $member["joined_at"]
                                );
                                ?>
                            </td>

                            <td>

                                <?php if ($member["status"] == "pending"): ?>

                                    <a
                                        href="approve_member.php?id=<?php echo $member["membership_id"]; ?>"
                                        class="btn btn-success"
                                        onclick="return confirm('Approve this membership request?');"
                                    >
                                        Approve
                                    </a>

                                    <a
                                        href="reject_member.php?id=<?php echo $member["membership_id"]; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('Reject this membership request?');"
                                    >
                                        Reject
                                    </a>

                                <?php else: ?>

                                    <span>
                                        No Action
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <p>
                    No membership requests found.
                </p>

            <?php endif; ?>

        </div>

    </main>

</div>

</body>

</html>

<?php
$conn->close();
?>