<?php

session_start();
include("../database.php");

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


// -----------------------------
// ADD TRAINING SESSION
// -----------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $club_id = $_POST["club_id"];
    $training_date = $_POST["training_date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $location = $_POST["location"];
    $description = $_POST["description"];


    // Validate fields
    if (
        empty($club_id) ||
        empty($training_date) ||
        empty($start_time) ||
        empty($end_time) ||
        empty($location)
    ) {

        $error = "Please fill in all required fields.";

    } else {

        // Insert training session
        $sql = "
            INSERT INTO training_sessions
            (
                club_id,
                training_date,
                start_time,
                end_time,
                location,
                description
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "isssss",
            $club_id,
            $training_date,
            $start_time,
            $end_time,
            $location,
            $description
        );

        if ($stmt->execute()) {

            $success = "Training session added successfully.";

        } else {

            $error = "Failed to add training session.";

        }

        $stmt->close();
    }
}


// -----------------------------
// GET SPORTS CLUBS
// -----------------------------

$clubs_sql = "
    SELECT club_id, club_name
    FROM sports_clubs
    ORDER BY club_name ASC
";

$clubs_result = $conn->query($clubs_sql);


// -----------------------------
// GET TRAINING SESSIONS
// -----------------------------

$training_sql = "
    SELECT
        training_sessions.training_id,
        training_sessions.training_date,
        training_sessions.start_time,
        training_sessions.end_time,
        training_sessions.location,
        training_sessions.description,
        sports_clubs.club_name

    FROM training_sessions

    INNER JOIN sports_clubs
        ON training_sessions.club_id = sports_clubs.club_id

    ORDER BY
        training_sessions.training_date ASC,
        training_sessions.start_time ASC
";

$training_result = $conn->query($training_sql);

?>


<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Training Sessions - SportsHub</title>

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

    <a href="members.php">
        <span class="menu-icon">
            <i data-lucide="users"></i>
        </span>
        <span>Members</span>
    </a>

    <a href="training.php" class="active">
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


    <!-- =========================
         MAIN CONTENT
    ========================== -->

    <div class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">


        <!-- TOPBAR -->

        <div class="topbar">

            <div>

                <h1>Training Sessions</h1>

                <p>
                    Manage sports club training sessions
                </p>

            </div>

            <div>

                Welcome,
                <?php
                echo htmlspecialchars($_SESSION["full_name"]);
                ?>

            </div>

        </div>


        <!-- =========================
             ADD TRAINING FORM
        ========================== -->

        <div class="content-box">

            <h2>Add Training Session</h2>

            <br>


            <?php if (isset($success)): ?>

                <p style="color: green;">
                    <?php echo $success; ?>
                </p>

                <br>

            <?php endif; ?>


            <?php if (isset($error)): ?>

                <p style="color: red;">
                    <?php echo $error; ?>
                </p>

                <br>

            <?php endif; ?>


            <form method="POST">


                <!-- Club -->

                <div class="form-group">

                    <label>
                        Sports Club
                    </label>

                    <select name="club_id" required>

                        <option value="">
                            Select Sports Club
                        </option>

                        <?php while ($club = $clubs_result->fetch_assoc()): ?>

                            <option
                                value="<?php echo $club["club_id"]; ?>"
                            >
                                <?php
                                echo htmlspecialchars(
                                    $club["club_name"]
                                );
                                ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- Date -->

                <div class="form-group">

                    <label>
                        Training Date
                    </label>

                    <input
                        type="date"
                        name="training_date"
                        required
                    >

                </div>


                <!-- Start Time -->

                <div class="form-group">

                    <label>
                        Start Time
                    </label>

                    <input
                        type="time"
                        name="start_time"
                        required
                    >

                </div>


                <!-- End Time -->

                <div class="form-group">

                    <label>
                        End Time
                    </label>

                    <input
                        type="time"
                        name="end_time"
                        required
                    >

                </div>


                <!-- Location -->

                <div class="form-group">

                    <label>
                        Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        placeholder="Example: NSBM Sports Ground"
                        required
                    >

                </div>


                <!-- Description -->

                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Enter training session details"
                    ></textarea>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Add Training Session
                </button>

            </form>

        </div>


        <br>


        <!-- =========================
             TRAINING TABLE
        ========================== -->

        <div class="content-box">

            <h2>Training Sessions</h2>

            <br>


            <?php if ($training_result->num_rows > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Club</th>
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Location</th>
                            <th>Description</th>
                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php while ($training = $training_result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["club_name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["training_date"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["start_time"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["end_time"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["location"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $training["description"]
                                );
                                ?>
                            </td>

                            <td>

                                <a
                                  href="edit_training.php?id=<?php echo $training["training_id"]; ?>"
                                  class="btn btn-success"
                                >
                                  Edit
                                </a>

                                <a
                                  href="delete_training.php?id=<?php echo $training["training_id"]; ?>"
                                  class="btn btn-danger"
                                  onclick="return confirm('Are you sure you want to delete this training session?');"
                                >
                                  Delete
                                </a>

                            </td>



                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <p>
                    No training sessions have been added yet.
                </p>

            <?php endif; ?>

        </div>


    </main>

</div>

<script src="../js/script.js"></script>

<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>

</html>

<?php

$conn->close();

?>