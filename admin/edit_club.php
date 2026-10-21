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

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Sports Club</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>


<body>


<div class="dashboard-layout">


    <!-- SIDEBAR -->

    <div class="sidebar">

        <h2>🏆 SportsHub</h2>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="clubs.php">
            🏅 Sports Clubs
        </a>

        <a href="#">
            👥 Members
        </a>

        <a href="#">
            🏋 Training
        </a>

        <a href="#">
            ⚽ Matches
        </a>

        <a href="#">
            🏆 Tournaments
        </a>

        <a href="#">
            📅 Fixtures
        </a>

        <a href="#">
            🥇 Results
        </a>

        <a href="#">
            🏟 Facilities
        </a>

        <a href="#">
            📋 Bookings
        </a>

        <a href="#">
            📊 Reports
        </a>

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


</body>

</html>