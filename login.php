<?php

session_start();

include "database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {

        $message = "Please enter your email and password.";

    } else {

        // Find user by email
        $stmt = $conn->prepare(
            "SELECT user_id, full_name, email, password, role
             FROM users
             WHERE email = ?"
        );

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();
        
        

           

            // Check password
            if (password_verify($password, $user["password"])) {

                // Store user information in session
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["full_name"] = $user["full_name"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                // Redirect according to role
                if ($user["role"] == "admin") {

                    header("Location: admin/dashboard.php");
                    exit();

                } else {

                    header("Location: student/dashboard.php");
                    exit();
                }

            } else {

                $message = "Invalid email or password.";
            }

        } else {

            $message = "Invalid email or password.";
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

    <title>Login - NSBM SportsHub</title>

    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>

<div class="register-container">

    <h1>NSBM SportsHub</h1>

    <h2>Login</h2>

    <?php if (!empty($message)): ?>

        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form action="login.php" method="POST" onsubmit="return validateLoginForm();" >

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
                placeholder="Enter your password"
                autocomplete="current-password"
                required
            >

            <button
                type="button"
                class="password-toggle"
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
                onclick="togglePassword('password')"
                aria-label="Show password"
                aria-pressed="false"
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
            Login
        </button>


       

    </form>
    <br>


    <p>
        Don't have an account?

        <a href="register.php">
            Register
        </a>
    </p>

</div>

<script src="js/script.js"></script>
<script src="js/home.js"></script>

</body>

</html>

