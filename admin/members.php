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

<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Club Members - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">

     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

    <a href="members.php" class="active">
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


    <!-- Main Content -->

     <div class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">


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

<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>

</html>

<?php
$conn->close();
?>