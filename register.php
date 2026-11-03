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
<html lang="en" data-theme="dark">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration - NSBM SportsHub</title>

    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

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
        <br><br>


        <label>Email</label>

        <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            required
        >
        <br><br>



        <label for="password">Password</label>
        <div class="password-wrapper" style="position: relative; width: 100%;">

        <input
            type="password"
            name="password"
            id="password"
            placeholder="Enter password"
            autocomplete="new-password"
            required
        >
        <button
            type="button"
            class="password-toggle"
            onclick="togglePassword('password')"
            aria-label="Show password"
            aria-pressed="false"
             style="
                position: absolute;
                top: 50%;
                right: 10px;
                transform: translateY(-50%);
                width: 36px;
                height: 36px;
                margin: 0;
                padding: 0;
                background: transparent;
                color: #555;
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 10;
            "
        >
            <svg
                class="eye-icon"
                xmlns="http://www.w3.org/2000/svg"
                width="22"
                height="22"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
           >
                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            
        </button>
    </div>
    <br>


        <label for="confirm_password">Confirm Password</label>

        <div class="password-wrapper" style="position: relative; width: 100%;">
     
        <input
            type="password"
            name="confirm_password"
            id="confirm_password"
            placeholder="Confirm password"
            autocomplete="new-password"
            required
        >
        <button
            type="button"
            class="password-toggle"
            onclick="togglePassword('password')"
            aria-label="Show password"
            aria-pressed="false"
            style="
                position: absolute;
                top: 50%;
                right: 10px;
                transform: translateY(-50%);
                width: 36px;
                height: 36px;
                margin: 0;
                padding: 0;
                background: transparent;
                color: #555;
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 10;
            "
       >
            <svg
                class="eye-icon"
                xmlns="http://www.w3.org/2000/svg"
                width="22"
                height="22"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
           >
                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </button>
    </div><br>

        <button type="submit">
            Register
        </button> 

    </form><br>


    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</div>

<script src="js/script.js"></script>
<script src="js/home.js"></script>

</body>

</html>