<?php
include "database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check empty fields
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {

        $message = "Please fill in all fields.";

    // Check password
    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";

    } else {

        // Check whether email already exists
        $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "This email is already registered.";

        } else {

            // Encrypt password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert student
            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, email, password, role)
                 VALUES (?, ?, ?, 'student')"
            );

            $stmt->bind_param(
                "sss",
                $full_name,
                $email,
                $hashed_password
            );

            if ($stmt->execute()) {

                $message = "Registration successful! You can now login.";

            } else {

                $message = "Registration failed. Please try again.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration - NSBM SportsHub</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="register-container">

    <h1>NSBM SportsHub</h1>

    <h2>Student Registration</h2>

    <?php if (!empty($message)): ?>

        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST" onsubmit="return validateRegisterForm();" action="register.php">

        <label>Full Name</label>

        <input
            type="text"
            name="full_name"
            id="full_name"
            placeholder="Enter your full name"
            required
        >


        <label>Email</label>

        <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            required
        >


        <label>Password</label>

        <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter password"
            required
        >
        <button
            type="button"
            onclick="togglePassword('password')"
        >
            Show / Hide Password
        </button>


        <label>Confirm Password</label>

        <input
            type="password"
            name="confirm_password"
            placeholder="Confirm password"
            required
        >


        <button type="submit">
            Register
        </button>

    </form>


    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</div>

<script src="js/script.js"></script>

</body>

</html>