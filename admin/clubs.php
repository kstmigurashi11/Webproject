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

<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Sports Clubs</title>

    <link rel="stylesheet"
          href="../css/style.css">

     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>


<div class="dashboard-layout">


    <!-- SIDEBAR -->

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




    <!-- MAIN -->

     <div class="main-content student-background"
        style="
           background-image: url('/sportshub/images/new.jpg.') !important;
           background-size: cover !important;
           background-position: center !important;
           background-repeat: no-repeat !important;
           min-height: 100vh;
        ">

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

<script src="../js/home.js"></script>
<script>
    lucide.createIcons();
</script>

</body>

</html>