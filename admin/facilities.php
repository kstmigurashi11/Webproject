<?php
session_start();
include(__DIR__ . '/../database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* ADD FACILITY */
if (isset($_POST['add_facility'])) {

    $facility_name = $_POST['facility_name'];
    $facility_type = $_POST['facility_type'];
    $location = $_POST['location'];
    $availability_status = $_POST['availability_status'];

    $sql = "INSERT INTO facilities
            (facility_name, facility_type, location, availability_status)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssss",
        $facility_name,
        $facility_type,
        $location,
        $availability_status
    );

    $stmt->execute();

    header("Location: facilities.php");
    exit();
}


/* DELETE FACILITY */
if (isset($_GET['delete'])) {

    $facility_id = $_GET['delete'];

    $sql = "DELETE FROM facilities WHERE facility_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $facility_id);

    $stmt->execute();

    header("Location: facilities.php");
    exit();
}


/* GET ALL FACILITIES */
$sql = "SELECT * FROM facilities ORDER BY facility_id DESC";
$result = $conn->query($sql);

?>



<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Facilities Management - SportsHub Admin</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="dashboard-layout">

    <!-- ADMIN SIDEBAR -->
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

    <a href="facilities.php" class="active">
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
                <h1>Facilities Management</h1>
                <p>Add and manage sports facilities.</p>
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

        <!-- ADD FACILITY FORM -->
        <div class="content-box admin-section">

            <h2>Add New Facility</h2>

            <form method="POST" class="admin-form-grid">

                <!-- FACILITY NAME -->
                <div class="form-group">

                    <label for="facility_name">
                        Facility Name
                    </label>

                    <input
                        type="text"
                        id="facility_name"
                        name="facility_name"
                        placeholder="Example: Basketball Court"
                        required
                    >

                </div>

                <!-- FACILITY TYPE -->
                <div class="form-group">

                    <label for="facility_type">
                        Facility Type
                    </label>

                    <input
                        type="text"
                        id="facility_type"
                        name="facility_type"
                        placeholder="Example: Indoor Court"
                    >

                </div>

                <!-- LOCATION -->
                <div class="form-group">

                    <label for="location">
                        Location
                    </label>

                    <input
                        type="text"
                        id="location"
                        name="location"
                        placeholder="Example: Main Building"
                    >

                </div>

                <!-- AVAILABILITY STATUS -->
                <div class="form-group">

                    <label for="availability_status">
                        Availability Status
                    </label>

                    <select
                        id="availability_status"
                        name="availability_status"
                    >

                        <option value="available">
                            Available
                        </option>

                        <option value="unavailable">
                            Unavailable
                        </option>

                    </select>

                </div>

                <!-- SUBMIT BUTTON -->
                <div class="full-width">

                    <button
                        type="submit"
                        name="add_facility"
                        class="add-facility-btn"
                    >
                        Add Facility
                    </button>

                </div>

            </form>

        </div>

        <!-- ALL FACILITIES -->
        <div class="content-box admin-section">

            <h2>All Facilities</h2>

            <!-- SEARCH BOX -->
            <div class="form-group">

                <input
                    type="text"
                    id="facilitySearch"
                    placeholder="Search facilities..."
                    style="max-width: 400px;"
                >

            </div>

            <!-- FACILITIES TABLE -->
            <div class="admin-table-wrapper">

                <table id="facilitiesTable">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Facility Name</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while ($row = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo (int)$row['facility_id']; ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row['facility_name']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row['facility_type'] ?? ''
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row['location'] ?? ''
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    ucfirst($row['availability_status'])
                                );
                                ?>
                            </td>

                            <td>

                                <div class="admin-actions">

                                    <a
                                        href="edit_facility.php?id=<?php echo (int)$row['facility_id']; ?>"
                                        class="btn btn-warning"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="facilities.php?delete=<?php echo (int)$row['facility_id']; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirmDelete('Are you sure you want to delete this facility?');"
                                    >
                                        Delete
                                    </a>

                                </div>

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
    searchTable("facilitySearch", "facilitiesTable");
</script>
<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>
