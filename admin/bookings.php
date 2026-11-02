<?php
session_start();
include(__DIR__ . '/../database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}


/* UPDATE BOOKING STATUS */

if (isset($_GET['action']) && isset($_GET['id'])) {

    $booking_id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {

        $status = 'approved';

    } elseif ($action == 'reject') {

        $status = 'rejected';

    } elseif ($action == 'pending') {

        $status = 'pending';

    } else {

        header("Location: bookings.php");
        exit();
    }


    /* Update status */

    $sql = "UPDATE bookings
            SET status = ?
            WHERE booking_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "si",
        $status,
        $booking_id
    );

    $stmt->execute();


    header("Location: bookings.php");
    exit();
}


/* GET ALL BOOKINGS */

$sql = "SELECT
            bookings.booking_id,
            bookings.booking_date,
            bookings.start_time,
            bookings.end_time,
            bookings.status,

            facilities.facility_name,
            facilities.location,

            users.user_id,
            users.full_name

        FROM bookings

        INNER JOIN facilities
            ON bookings.facility_id = facilities.facility_id

        INNER JOIN users
            ON bookings.user_id = users.user_id

        ORDER BY bookings.booking_date DESC,
                 bookings.start_time DESC";

$result = $conn->query($sql);

?>



<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Booking Management - SportsHub Admin</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="dashboard-layout">

    <!-- ADMIN SIDEBAR -->
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


    <div class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">

        <!-- TOP BAR -->
        <div class="topbar">

            <div>
                <h1>Facility Booking Management</h1>
                <p>Manage student facility booking requests.</p>
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

        <!-- BOOKINGS TABLE -->
        <div class="content-box admin-section">

            <h2>All Facility Bookings</h2>

            <!-- SEARCH -->
            <div class="form-group">

                <input
                    type="text"
                    id="bookingSearch"
                    placeholder="Search bookings..."
                    style="max-width: 400px;"
                >

            </div>

            <div class="admin-table-wrapper">

                <table id="bookingTable">

                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Student</th>
                            <th>Facility</th>
                            <th>Location</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($result->num_rows > 0) { ?>

                        <?php while ($row = $result->fetch_assoc()) { ?>

                            <tr>

                                <td>
                                    <?php echo (int)$row['booking_id']; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['full_name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['facility_name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['location'] ?? ''); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['booking_date']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['start_time']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row['end_time']); ?>
                                </td>

                                <td>
                                    <span class="booking-status <?php echo htmlspecialchars($row['status']); ?>">
                                        <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                    </span>
                                </td>

                                <td>

                                    <div class="admin-actions">

                                        <a
                                            href="bookings.php?action=approve&amp;id=<?php echo (int)$row['booking_id']; ?>"
                                            class="btn btn-success"
                                            onclick="return confirm('Approve this booking?');"
                                        >
                                            Approve
                                        </a>

                                        <a
                                            href="bookings.php?action=reject&amp;id=<?php echo (int)$row['booking_id']; ?>"
                                            class="btn btn-danger"
                                            onclick="return confirm('Reject this booking?');"
                                        >
                                            Reject
                                        </a>

                                        <a
                                            href="bookings.php?action=pending&amp;id=<?php echo (int)$row['booking_id']; ?>"
                                            class="btn btn-warning"
                                            onclick="return confirm('Set this booking back to pending?');"
                                        >
                                            Pending
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="9">
                                No facility bookings found.
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

<script>
    searchTable("bookingSearch", "bookingTable");
</script>
<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>
</body>
</html>
