<?php
session_start();
include(__DIR__ . '/../database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


/* BOOK FACILITY */

if (isset($_POST['book_facility'])) {

    $facility_id = $_POST['facility_id'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    /* Check start time is before end time */

    if ($start_time >= $end_time) {

        $error = "End time must be after start time.";

    } else {

        /* Check for overlapping bookings */

        $sql = "SELECT booking_id
                FROM bookings
                WHERE facility_id = ?
                AND booking_date = ?
                AND status != 'rejected'
                AND start_time < ?
                AND end_time > ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "isss",
            $facility_id,
            $booking_date,
            $end_time,
            $start_time
        );

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows > 0) {

            $error = "This facility is already booked during the selected time.";

        } else {

            /* Insert booking */

            $sql = "INSERT INTO bookings
                    (user_id, facility_id, booking_date, start_time, end_time, status)
                    VALUES (?, ?, ?, ?, ?, 'pending')";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "iisss",
                $user_id,
                $facility_id,
                $booking_date,
                $start_time,
                $end_time
            );

            $stmt->execute();

            $success = "Booking request submitted successfully.";
        }
    }
}


/* GET AVAILABLE FACILITIES */

$sql = "SELECT *
        FROM facilities
        WHERE availability_status = 'available'
        ORDER BY facility_name";

$facilities = $conn->query($sql);


/* GET STUDENT BOOKINGS */

$sql = "SELECT
            bookings.*,
            facilities.facility_name
        FROM bookings
        INNER JOIN facilities
            ON bookings.facility_id = facilities.facility_id
        WHERE bookings.user_id = ?
        ORDER BY bookings.booking_date DESC, bookings.start_time DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$my_bookings = $stmt->get_result();

?>

<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book Facility</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    

</head>

<body>

<div class="dashboard-layout">


    <!-- Sidebar -->
    <div class="sidebar">

    <h2><a href="index.php"><font size="6"><b>
        SportsHub</b></font></a>   
    <h2> 

    

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

     <main class="main-content student-background
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
           ">

        <div class="content-box">

    <div class="booking-container">

    

    <h1>Facility Booking</h1>


    <!-- SUCCESS MESSAGE -->

    <?php if (isset($success)) { ?>

        <div class="success">
            <?php echo $success; ?>
        </div>

    <?php } ?>


    <!-- ERROR MESSAGE -->

    <?php if (isset($error)) { ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>


    <!-- BOOKING FORM -->

    <div class="form-box">

        <h2>Book a Facility</h2>

        <form method="POST" onsubmit="return validateBookingForm();">


            <label>Facility</label>

            <select name="facility_id"  id="facility_id" required>

                <option value="">
                    Select Facility
                </option>

                <?php while ($facility = $facilities->fetch_assoc()) { ?>

                    <option value="<?php echo $facility['facility_id']; ?>">

                        <?php
                        echo htmlspecialchars($facility['facility_name']);

                        if (!empty($facility['location'])) {
                            echo " - " . htmlspecialchars($facility['location']);
                        }
                        ?>

                    </option>

                <?php } ?>

            </select>


            <label>Booking Date</label>

            <input
                type="date"
                name="booking_date"
                id="booking_date"
                min="<?php echo date('Y-m-d'); ?>"
                required
            >


            <label>Start Time</label>

            <input
                type="time"
                name="start_time"
                id="start_time"
                required
            >


            <label>End Time</label>

            <input
                type="time"
                name="end_time"
                id="end_time"
                required
            >


            <button type="submit" name="book_facility">
                Submit Booking Request
            </button>

        </form>

    </div>


    <!-- MY BOOKINGS -->
    

    <div>

        <h2>My Bookings</h2>

        <div class="booking-table-wrapper">


        <table>

            <tr>

                <th>Facility</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>

            </tr>


            <?php if ($my_bookings->num_rows > 0) { ?>

                <?php while ($booking = $my_bookings->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $booking['facility_name']
                            );
                            ?>
                        </td>

                        <td>
                            <?php echo $booking['booking_date']; ?>
                        </td>

                        <td>
                            <?php echo $booking['start_time']; ?>
                        </td>

                        <td>
                            <?php echo $booking['end_time']; ?>
                        </td>

                        <td class="<?php echo $booking['status']; ?>">

                            <?php
                            echo ucfirst($booking['status']);
                            ?>

                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>

                    <td colspan="5">
                        You have not made any bookings yet.
                    </td>

                </tr>

            <?php } ?>

        </table>
        </div>


    </div>

</div>

<script src="../js/script.js"></script>
<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>

</html>