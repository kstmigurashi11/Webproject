<?php
session_start();
include(__DIR__ . '/../database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}


/* =========================
   TOTAL STUDENTS
========================= */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM users
    WHERE role = 'student'
");

$total_students = $result->fetch_assoc()['total'];


/* =========================
   TOTAL FACILITIES
========================= */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM facilities
");

$total_facilities = $result->fetch_assoc()['total'];


/* =========================
   TOTAL TOURNAMENTS
========================= */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM tournaments
");

$total_tournaments = $result->fetch_assoc()['total'];


/* =========================
   TOTAL FIXTURES
========================= */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM fixtures
");

$total_fixtures = $result->fetch_assoc()['total'];


/* =========================
   TOTAL MATCH RESULTS
========================= */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM match_results
");

$total_results = $result->fetch_assoc()['total'];


/* =========================
   TOTAL PARTICIPATION
========================= */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM participation
");

$total_participation = $result->fetch_assoc()['total'];


/* =========================
   BOOKING STATISTICS
========================= */

$result = $conn->query("
    SELECT
        SUM(status = 'pending') AS pending,
        SUM(status = 'approved') AS approved,
        SUM(status = 'rejected') AS rejected
    FROM bookings
");

$booking_stats = $result->fetch_assoc();


/* =========================
   PARTICIPATION BY TOURNAMENT
========================= */

$participation_by_tournament = $conn->query("
    SELECT
        t.tournament_name,
        COUNT(p.participation_id) AS total_participants
    FROM tournaments t
    LEFT JOIN participation p
        ON t.tournament_id = p.tournament_id
    GROUP BY t.tournament_id, t.tournament_name
    ORDER BY total_participants DESC
");
/* =========================
   FACILITY AVAILABILITY
========================= */

$result = $conn->query("
    SELECT
        SUM(availability_status = 'available') AS available,
        SUM(availability_status = 'unavailable') AS unavailable
    FROM facilities
");

$facility_stats = $result->fetch_assoc();

/* =========================
   BOOKINGS BY FACILITY
========================= */

$bookings_by_facility = $conn->query("
    SELECT
        f.facility_name,
        COUNT(b.booking_id) AS total_bookings
    FROM facilities f
    LEFT JOIN bookings b
        ON f.facility_id = b.facility_id
    GROUP BY f.facility_id, f.facility_name
    ORDER BY total_bookings DESC
");

/* =========================
   PARTICIPATION TYPES
========================= */

$participation_types = $conn->query("
    SELECT
        participation_type,
        COUNT(*) AS total
    FROM participation
    WHERE participation_type IS NOT NULL
    AND participation_type != ''
    GROUP BY participation_type
    ORDER BY total DESC
");
?>


<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reports & Statistics</title>

    <link rel="stylesheet" href="../css/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


</head>

<body>

<div class="container">

    <a href="dashboard.php" class="back">
        ← Back to Dashboard
    </a>

    <h1>Reports & Statistics</h1>

    <p class="subtitle">
        Overview of the SportsHub system
    </p>


    <!-- =========================
         SYSTEM STATISTICS
    ========================== -->

    <div class="stats">

        <div class="card">
            <h3>Total Students</h3>
            <div class="number">
                <?= $total_students ?>
            </div>
        </div>


        <div class="card">
            <h3>Total Facilities</h3>
            <div class="number">
                <?= $total_facilities ?>
            </div>
        </div>


        <div class="card">
            <h3>Total Tournaments</h3>
            <div class="number">
                <?= $total_tournaments ?>
            </div>
        </div>


        <div class="card">
            <h3>Total Fixtures</h3>
            <div class="number">
                <?= $total_fixtures ?>
            </div>
        </div>


        <div class="card">
            <h3>Match Results</h3>
            <div class="number">
                <?= $total_results ?>
            </div>
        </div>


        <div class="card">
            <h3>Total Participation</h3>
            <div class="number">
                <?= $total_participation ?>
            </div>
        </div>

    </div>


    <!-- =========================
         BOOKING STATISTICS
    ========================== -->

    <div class="section">

        <h2>Facility Booking Statistics</h2>

        <div class="booking-grid">

            <div class="booking-card">

                <h3>Pending</h3>

                <div class="number">
                    <?= $booking_stats['pending'] ?? 0 ?>
                </div>

            </div>


            <div class="booking-card">

                <h3>Approved</h3>

                <div class="number">
                    <?= $booking_stats['approved'] ?? 0 ?>
                </div>

            </div>


            <div class="booking-card">

                <h3>Rejected</h3>

                <div class="number">
                    <?= $booking_stats['rejected'] ?? 0 ?>
                </div>

            </div>

        </div>

    </div>

    <!-- =========================
     BOOKING STATUS CHART
     ========================== -->

    <div class="section">

        <h2>Booking Status Chart</h2>

        <canvas
            id="bookingChart"
            width="600"
            height="300"
        ></canvas>

    </div>

    
    <!-- =========================
        FACILITY AVAILABILITY
    ========================== -->

    <div class="section">

        <h2>Facility Availability</h2>

        <div class="booking-grid">

            <div class="booking-card">

                <h3>Available</h3>

                <div class="number">
                    <?= $facility_stats['available'] ?? 0 ?>
                </div>

            </div>


            <div class="booking-card">

                <h3>Unavailable</h3>

                <div class="number">
                    <?= $facility_stats['unavailable'] ?? 0 ?>
                </div>

            </div>

        </div>

    </div>
     
    <!-- =========================
       BOOKINGS BY FACILITY
    ========================== -->

    <div class="section">

        <h2>Bookings by Facility</h2>

        <table>

            <tr>
                <th>Facility</th>
                <th>Total Bookings</th>
            </tr>

            <?php if ($bookings_by_facility->num_rows > 0): ?>

                <?php while ($row = $bookings_by_facility->fetch_assoc()): ?>

                    <tr>

                        <td>
                           <?= htmlspecialchars($row['facility_name']) ?>
                        </td>

                        <td>
                           <?= $row['total_bookings'] ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                   <td colspan="2">
                       No booking data available.
                    </td>

                </tr>

            <?php endif; ?>

        </table>

    </div>

    
    <!-- =========================
         PARTICIPATION REPORT
    ========================== -->

    <div class="section">

        <h2>Participation by Tournament</h2>

        <table>

            <tr>
                <th>Tournament</th>
                <th>Total Participants</th>
            </tr>

            <?php if ($participation_by_tournament->num_rows > 0): ?>

                <?php while ($row = $participation_by_tournament->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($row['tournament_name']) ?>
                        </td>

                        <td>
                            <?= $row['total_participants'] ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="2">
                        No participation data available.
                    </td>

                </tr>

            <?php endif; ?>

        </table>

    </div>

    <!-- =========================
         PARTICIPATION TYPES
    ========================== -->

    <div class="section">

        <h2>Participation Types</h2>

        <table>

            <tr>
               <th>Participation Type</th>
               <th>Total</th>
            </tr>

            <?php if ($participation_types->num_rows > 0): ?>

                <?php while ($row = $participation_types->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($row['participation_type']) ?>
                        </td>

                        <td>
                            <?= $row['total'] ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="2">
                        No participation data available.
                    </td>

                </tr>

            <?php endif; ?>

        </table>

    </div>



</div>

<script>
    const bookingData = {
        pending: <?= (int)($booking_stats['pending'] ?? 0) ?>,
        approved: <?= (int)($booking_stats['approved'] ?? 0) ?>,
        rejected: <?= (int)($booking_stats['rejected'] ?? 0) ?>
    };
</script>

<script src="../js/script.js"></script>

<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>

</html>