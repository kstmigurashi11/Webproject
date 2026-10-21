<?php

session_start();
include("../database.php");

// Check admin login
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit();
}


// Check training ID
if (!isset($_GET["id"])) {
    header("Location: training.php");
    exit();
}

$training_id = $_GET["id"];


// --------------------------------
// GET TRAINING SESSION
// --------------------------------

$sql = "
    SELECT *
    FROM training_sessions
    WHERE training_id = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $training_id
);

$stmt->execute();

$result = $stmt->get_result();


// Check whether training exists
if ($result->num_rows == 0) {

    $stmt->close();

    header("Location: training.php");
    exit();
}

$training = $result->fetch_assoc();

$stmt->close();


// --------------------------------
// UPDATE TRAINING SESSION
// --------------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $club_id = $_POST["club_id"];
    $training_date = $_POST["training_date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $location = $_POST["location"];
    $description = $_POST["description"];


    if (
        empty($club_id) ||
        empty($training_date) ||
        empty($start_time) ||
        empty($end_time) ||
        empty($location)
    ) {

        $error = "Please fill in all required fields.";

    } else {

        $update_sql = "
            UPDATE training_sessions
            SET
                club_id = ?,
                training_date = ?,
                start_time = ?,
                end_time = ?,
                location = ?,
                description = ?

            WHERE training_id = ?
        ";

        $update_stmt = $conn->prepare($update_sql);

        $update_stmt->bind_param(
            "isssssi",
            $club_id,
            $training_date,
            $start_time,
            $end_time,
            $location,
            $description,
            $training_id
        );


        if ($update_stmt->execute()) {

            $update_stmt->close();

            header("Location: training.php");
            exit();

        } else {

            $error = "Failed to update training session.";

        }

        $update_stmt->close();
    }
}


// --------------------------------
// GET SPORTS CLUBS
// --------------------------------

$clubs_sql = "
    SELECT club_id, club_name
    FROM sports_clubs
    ORDER BY club_name ASC
";

$clubs_result = $conn->query($clubs_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Training - SportsHub</title>

    <link rel="stylesheet" href="../css/style.css">

</head>


<body>

<div class="dashboard-layout">


    <!-- SIDEBAR -->

    <aside class="sidebar">

        <h2>SportsHub</h2>

        <ul>

            <li>
                <a href="dashboard.php">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="clubs.php">
                    Sports Clubs
                </a>
            </li>

            <li>
                <a href="members.php">
                    Members
                </a>
            </li>

            <li>
                <a href="training.php" class="active">
                    Training
                </a>
            </li>

            <li>
                <a href="#">
                    Matches
                </a>
            </li>

            <li>
                <a href="#">
                    Tournaments
                </a>
            </li>

            <li>
                <a href="#">
                    Fixtures
                </a>
            </li>

            <li>
                <a href="#">
                    Results
                </a>
            </li>

            <li>
                <a href="#">
                    Facilities
                </a>
            </li>

            <li>
                <a href="#">
                    Bookings
                </a>
            </li>

            <li>
                <a href="#">
                    Reports
                </a>
            </li>

        </ul>


        <a href="../logout.php" class="logout-btn">
            Logout
        </a>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main-content">


        <!-- TOPBAR -->

        <div class="topbar">

            <div>

                <h1>Edit Training Session</h1>

                <p>
                    Update training session details
                </p>

            </div>

            <div>

                Welcome,
                <?php
                echo htmlspecialchars($_SESSION["full_name"]);
                ?>

            </div>

        </div>


        <!-- FORM -->

        <div class="content-box">

            <h2>Update Training Session</h2>

            <br>


            <?php if (isset($error)): ?>

                <p style="color: red;">
                    <?php echo $error; ?>
                </p>

                <br>

            <?php endif; ?>


            <form method="POST">


                <!-- CLUB -->

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

                                <?php
                                if (
                                    $club["club_id"]
                                    == $training["club_id"]
                                ) {
                                    echo "selected";
                                }
                                ?>
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


                <!-- DATE -->

                <div class="form-group">

                    <label>
                        Training Date
                    </label>

                    <input
                        type="date"
                        name="training_date"
                        value="<?php
                        echo htmlspecialchars(
                            $training["training_date"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- START TIME -->

                <div class="form-group">

                    <label>
                        Start Time
                    </label>

                    <input
                        type="time"
                        name="start_time"
                        value="<?php
                        echo htmlspecialchars(
                            $training["start_time"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- END TIME -->

                <div class="form-group">

                    <label>
                        End Time
                    </label>

                    <input
                        type="time"
                        name="end_time"
                        value="<?php
                        echo htmlspecialchars(
                            $training["end_time"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- LOCATION -->

                <div class="form-group">

                    <label>
                        Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        value="<?php
                        echo htmlspecialchars(
                            $training["location"]
                        );
                        ?>"
                        required
                    >

                </div>


                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                    ><?php
                    echo htmlspecialchars(
                        $training["description"]
                    );
                    ?></textarea>

                </div>


                <button
                    type="submit"
                    class="btn btn-success"
                >
                    Update Training
                </button>


                <a
                    href="training.php"
                    class="btn btn-warning"
                >
                    Cancel
                </a>

            </form>

        </div>

    </main>

</div>

</body>

</html>

<?php

$conn->close();

?>