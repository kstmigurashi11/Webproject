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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book Facility</title>

    <link rel="stylesheet"
          href="../css/style.css">

    <style>
        
    /* Facility Booking page-specific styles */

    .booking-container {
        width: 100%;
        max-width: none;
        margin: 0;
    }

    .back-btn {
        display: inline-block;
        margin-bottom: 20px;
        text-decoration: none;
        color: #2563eb;
    }

    .form-box {
        background: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .form-box h2 {
        margin-top: 0;
        margin-bottom: 20px;
    }

    .booking-container label {
        display: block;
        margin-top: 15px;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .booking-container input,
    .booking-container select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .booking-container button {
        margin-top: 20px;
        padding: 12px 20px;
        background-color: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .booking-container button:hover {
        background-color: #1d4ed8;
    }

    .success,
    .error {
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .booking-container .pending {
        color: #856404;
        font-weight: bold;
    }

    .booking-container .approved {
        color: #155724;
        font-weight: bold;
    }

    .booking-container .rejected {
        color: #721c24;
        font-weight: bold;
    }

    .booking-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .booking-table-wrapper table {
        min-width: 600px;
    }




    </style>

</head>

<body>

<div class="dashboard-layout">

    <div class="sidebar">

        <h2>SportsHub</h2>

        <a href="dashboard.php">Dashboard</a>
        <a href="clubs.php">Sports Clubs</a>
        <a href="my_clubs.php">My Clubs</a>
        <a href="training.php">Training Schedule</a>
        <a href="matches.php">Matches</a>
        <a href="tournament_fixtures.php">Tournaments</a>
        <a href="book_facility.php">Facilities</a>
        <a href="participation_history.php">Participation History</a>

        <a href="../logout.php" class="logout-btn">Logout</a>

    </div>

     <div class="main-content">

        <div class="content-box">

    <div class="booking-container">

    <a href="dashboard.php" class="back-btn">
        ← Back to Dashboard
    </a>

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

</body>

</html>