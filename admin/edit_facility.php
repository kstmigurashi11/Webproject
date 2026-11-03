<?php
session_start();
include(__DIR__ . '/../database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}


/* GET FACILITY ID */

if (!isset($_GET['id'])) {
    header("Location: facilities.php");
    exit();
}

$facility_id = $_GET['id'];


/* GET EXISTING FACILITY */

$sql = "SELECT * FROM facilities WHERE facility_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $facility_id);
$stmt->execute();

$result = $stmt->get_result();
$facility = $result->fetch_assoc();


if (!$facility) {
    header("Location: facilities.php");
    exit();
}


/* UPDATE FACILITY */

if (isset($_POST['update_facility'])) {

    $facility_name = $_POST['facility_name'];
    $facility_type = $_POST['facility_type'];
    $location = $_POST['location'];
    $availability_status = $_POST['availability_status'];


    $sql = "UPDATE facilities
            SET facility_name = ?,
                facility_type = ?,
                location = ?,
                availability_status = ?
            WHERE facility_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssi",
        $facility_name,
        $facility_type,
        $location,
        $availability_status,
        $facility_id
    );

    $stmt->execute();


    header("Location: facilities.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>

    <link rel="stylesheet" href="../CSS/style.css">

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Facility</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">


</head>


<body>

<div class="container">

    <a href="facilities.php" class="back-btn">
        ← Back to Facilities
    </a>


    <div class="form-box">

        <h1>Edit Facility</h1>


        <form method="POST">


            <label>Facility Name</label>

            <input
                type="text"
                name="facility_name"
                value="<?php echo htmlspecialchars($facility['facility_name']); ?>"
                required
            >


            <label>Facility Type</label>

            <input
                type="text"
                name="facility_type"
                value="<?php echo htmlspecialchars($facility['facility_type']); ?>"
            >


            <label>Location</label>

            <input
                type="text"
                name="location"
                value="<?php echo htmlspecialchars($facility['location']); ?>"
            >


            <label>Availability Status</label>

            <select name="availability_status">

                <option
                    value="available"
                    <?php
                    if ($facility['availability_status'] == 'available') {
                        echo 'selected';
                    }
                    ?>
                >
                    Available
                </option>


                <option
                    value="unavailable"
                    <?php
                    if ($facility['availability_status'] == 'unavailable') {
                        echo 'selected';
                    }
                    ?>
                >
                    Unavailable
                </option>

            </select>


            <button type="submit" name="update_facility">
                Update Facility
            </button>

        </form>

    </div>

</div>

<script src="../js/home.js"></script>
<script src="../js/script.js"></script>

</body>

</html>