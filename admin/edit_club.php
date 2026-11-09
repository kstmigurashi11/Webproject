<?php

session_start();


// Check login
if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}


// Check admin
if ($_SESSION["role"] != "admin") {

    header("Location: ../login.php");
    exit();

}


include "../database.php";


// Check whether ID exists
if (!isset($_GET["id"])) {

    header("Location: clubs.php");
    exit();

}


$club_id = $_GET["id"];


// Get existing club
$stmt = $conn->prepare(
    "SELECT * FROM sports_clubs WHERE club_id = ?"
);

$stmt->bind_param("i", $club_id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows != 1) {

    header("Location: clubs.php");
    exit();

}


$club = $result->fetch_assoc();

$stmt->close();


$message = "";


// =========================
// UPDATE CLUB
// =========================

if (isset($_POST["update_club"])) {

    $club_name = trim($_POST["club_name"]);
    $description = trim($_POST["description"]);
    $sport_type = trim($_POST["sport_type"]);
    $coach_name = trim($_POST["coach_name"]);
    $contact_email = trim($_POST["contact_email"]);


    if (
        empty($club_name) ||
        empty($sport_type)
    ) {

        $message = "Club name and sport type are required.";

    } else {

        $stmt = $conn->prepare(
            "UPDATE sports_clubs
             SET club_name = ?,
                 description = ?,
                 sport_type = ?,
                 coach_name = ?,
                 contact_email = ?
             WHERE club_id = ?"
        );


        $stmt->bind_param(
            "sssssi",
            $club_name,
            $description,
            $sport_type,
            $coach_name,
            $contact_email,
            $club_id
        );


        if ($stmt->execute()) {

            header("Location: clubs.php");
            exit();

        } else {

            $message = "Error updating club.";

        }


        $stmt->close();
    }
}

?>


<!DOCTYPE html>

<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Sports Club</title>

    <link rel="stylesheet"
          href="../CSS/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>


<body>


<div class="dashboard-layout">


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

    <a href="clubs.php" class="active">
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

    <div class="main-content">


        <div class="topbar">

            <h1>Edit Sports Club</h1>

            <a href="../logout.php"
               class="logout-btn">

                Logout

            </a>

        </div>


        <div class="content-box">


            <h2>Update Club Information</h2>

            <br>


            <?php if (!empty($message)): ?>

                <div class="message">

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php endif; ?>


            <form method="POST">


                <!-- CLUB NAME -->

                <div class="form-group">

                    <label>
                        Club Name
                    </label>

                    <input
                        type="text"
                        name="club_name"
                        value="<?php
                        echo htmlspecialchars(
                            $club["club_name"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- SPORT TYPE -->

                <div class="form-group">

                    <label>
                        Sport Type
                    </label>

                    <input
                        type="text"
                        name="sport_type"
                        value="<?php
                        echo htmlspecialchars(
                            $club["sport_type"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- COACH -->

                <div class="form-group">

                    <label>
                        Coach Name
                    </label>

                    <input
                        type="text"
                        name="coach_name"
                        value="<?php
                        echo htmlspecialchars(
                            $club["coach_name"]
                        );
                        ?>"
                    >

                </div>


                <!-- EMAIL -->

                <div class="form-group">

                    <label>
                        Contact Email
                    </label>

                    <input
                        type="email"
                        name="contact_email"
                        value="<?php
                        echo htmlspecialchars(
                            $club["contact_email"]
                        );
                        ?>"
                    >

                </div>


                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                    ><?php
                    echo htmlspecialchars(
                        $club["description"]
                    );
                    ?></textarea>

                </div>


                <button
                    type="submit"
                    name="update_club"
                    class="btn btn-primary">

                    Update Club

                </button>


                <a
                    href="clubs.php"
                    class="btn btn-danger">

                    Cancel

                </a>


            </form>


        </div>


    </div>

</div>

<script src="../js/home.js"></script>
<script src="../js/script.js"></script>

</body>

</html>