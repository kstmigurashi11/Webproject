<?php

session_start();
include("../database.php");

// Check student login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: ../login.php");
    exit();
}


// --------------------------------
// GET TRAINING SESSIONS
// --------------------------------

$sql = "
    SELECT
        training_sessions.training_id,
        training_sessions.training_date,
        training_sessions.start_time,
        training_sessions.end_time,
        training_sessions.location,
        training_sessions.description,
        sports_clubs.club_name,
        sports_clubs.sport_type

    FROM training_sessions

    INNER JOIN sports_clubs
        ON training_sessions.club_id = sports_clubs.club_id

    ORDER BY
        training_sessions.training_date ASC,
        training_sessions.start_time ASC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Training Schedule - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">

</head>


<body>

<div class="dashboard-layout">


    <!-- =========================
         SIDEBAR
    ========================== -->

    <aside class="sidebar">

        <h2>SportsHub</h2>

        

            
                <a href="dashboard.php">
                    Dashboard
                </a>
            

            
                <a href="clubs.php">
                    Sports Clubs
                </a>
            

            
                <a href="my_clubs.php">
                    My Clubs
                </a>
            

            
                <a href="training.php" class="active">
                    Training Schedule
                </a>
            

            
                <a href="matches.php">
                    Matches
                </a>
            

            
                <a href="tournament_fixtures.php">
                    Tournaments
                </a>
            

           
                <a href="book_facility.php">
                    Facilities
                </a>
           

            
            
                <a href="participation_history.php">
                    Participation History
                </a>
            

        


        <a href="../logout.php" class="logout-btn">
            Logout
        </a>

    </aside>


    <!-- =========================
         MAIN CONTENT
    ========================== -->

    <main class="main-content">


        <!-- TOPBAR -->

        <div class="topbar">

            <div>

                <h1>Training Schedule</h1>

                <p>
                    View upcoming sports training sessions
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
             TRAINING SCHEDULE
        ========================== -->

        <div class="content-box">

            <h2>Upcoming Training Sessions</h2>

            <br>


            <?php if ($result->num_rows > 0): ?>

                <table>

                    <thead>

                        <tr>

                            <th>Sports Club</th>
                            <th>Sport</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Location</th>
                            <th>Description</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php while ($training = $result->fetch_assoc()): ?>

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
                                    $training["sport_type"]
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

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>


            <?php else: ?>

                <p>
                    No training sessions are currently available.
                </p>

            <?php endif; ?>

        </div>


    </main>

</div>

</body>

</html>

<?php

$conn->close();

?>