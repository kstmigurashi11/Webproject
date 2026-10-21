<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}

include "../database.php";

$message = "";


/* =========================
   CREATE CLUB
========================= */

if (isset($_POST["add_club"])) {

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
            "INSERT INTO sports_clubs
            (club_name, description, sport_type, coach_name, contact_email)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssss",
            $club_name,
            $description,
            $sport_type,
            $coach_name,
            $contact_email
        );

        if ($stmt->execute()) {

            $message = "Sports club added successfully.";

        } else {

            $message = "Error adding club.";
        }

        $stmt->close();
    }
}


/* =========================
   READ CLUBS
========================= */

$result = $conn->query(
    "SELECT * FROM sports_clubs
     ORDER BY club_id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Sports Clubs</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>

<body>


<div class="dashboard-layout">


    <!-- SIDEBAR -->

    <div class="sidebar">

        <h2> SportsHub</h2>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="clubs.php">
             Sports Clubs
        </a>

        <a href="members.php">
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

         <a href="../logout.php" class="logout-btn">Logout</a>

    </div>


    <!-- MAIN -->

    <div class="main-content">


        <div class="topbar">

            <h1>Manage Sports Clubs</h1>

        </div>

        


        <?php if (!empty($message)): ?>

            <div class="message">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>


        <!-- ADD CLUB FORM -->

        <div class="content-box">

            <h2>Add New Sports Club</h2>

            <br>

            <form method="POST">


                <div class="form-group">

                    <label>
                        Club Name
                    </label>

                    <input
                        type="text"
                        name="club_name"
                        placeholder="Example: NSBM Football Club"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Sport Type
                    </label>

                    <input
                        type="text"
                        name="sport_type"
                        placeholder="Example: Football"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        Coach Name
                    </label>

                    <input
                        type="text"
                        name="coach_name"
                        placeholder="Coach name"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Contact Email
                    </label>

                    <input
                        type="email"
                        name="contact_email"
                        placeholder="club@example.com"
                    >

                </div>


                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Club description"
                    ></textarea>

                </div>


                <button
                    type="submit"
                    name="add_club"
                    class="btn btn-primary">

                    Add Club

                </button>

            </form>

        </div>


        <br><br>


        <!-- CLUB LIST -->

        <div class="content-box">

            <h2>Sports Clubs</h2>


            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Club Name</th>

                        <th>Sport</th>

                        <th>Coach</th>

                        <th>Email</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                if ($result->num_rows > 0) {

                    while ($club = $result->fetch_assoc()) {

                ?>

                    <tr>

                        <td>
                            <?php
                            echo $club["club_id"];
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $club["club_name"]
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $club["sport_type"]
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $club["coach_name"]
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $club["contact_email"]
                            );
                            ?>
                        </td>

                        <td>

                            <a
                                href="edit_club.php?id=<?php echo $club['club_id']; ?>"
                                class="btn btn-warning">

                                Edit

                            </a>


                            <a
                                href="delete_club.php?id=<?php echo $club['club_id']; ?>"
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this club?');">

                                Delete

                            </a>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="6">
                            No sports clubs found.
                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>


    </div>

</div>

</body>

</html>