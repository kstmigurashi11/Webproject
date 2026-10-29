<?php
// sportshub/index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportsHub - Sports Management Platform</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="CSS/home.css">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Top Announcement Bar -->
    <div class="top-bar">
        <p>SportsHub & PlayMetrics: A new era for youth sports! <a href="register.php">Read more →</a></p>
    </div>

    <!-- Header / Navigation Bar -->
    <header class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="logo">
                <i class="fa-solid fa-futbol"></i> <span class="logo-text">SPORTS<strong>HUB</strong></span>
            </a>
            
            <nav class="nav-links">
                <a href="index.php" class="active">Sports</a>
                <a href="#features">Who We Serve</a>
                <a href="student/clubs.php">Clubs</a>
                <a href="#support">Support</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="admin/dashboard.php">Admin Panel</a>
                    <?php else: ?>
                        <a href="student/dashboard.php">My Dashboard</a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>

            <div class="nav-actions">
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" class="theme-toggle-btn" title="Toggle Light/Dark Mode">
                    <i class="fa-solid fa-moon"></i>
                </button>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="btn btn-outline">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-login">Login</a>
                    <a href="register.php" class="btn btn-primary">GET STARTED</a>
                <?php endif; ?>
            </div>

            <div class="menu-toggle" id="mobile-menu">
                <i class="fa-solid fa-bars"></i>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>PLAY → MANAGE → WIN</h1>
            <p>Everything you needs to play, compete, and succeed.</p>
            
            <div class="hero-buttons">
                <?php 
                    $demo_link = (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) ? 'student/book_facility.php' : 'register.php';
                    $learn_link = (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) ? 'student/clubs.php' : 'login.php';
                ?>
                <a href="<?php echo $demo_link; ?>" class="btn btn-primary btn-lg">SCHEDULE A DEMO</a>
                <a href="<?php echo $learn_link; ?>" class="btn btn-outline btn-lg">LEARN MORE</a>
            </div>
        </div>
    </section>

    <!-- Trusted Partners Section -->
    <section class="trusted-section">
        <h3>SUPPORTING EVERY PART OF THE SPORTING JOURNEY</h3>
        <div class="trusted-logos">
            <a href="student/clubs.php" class="sport-link">
                <i class="fas fa-shield-alt"></i>
                <span>Clubs</span>
            </a>

            <a href="student/training.php" class="sport-link">
                <i class="fas fa-users"></i>
                <span>SCHEDULE</span>
            </a>

            <a href="student/book_facility.php" class="sport-link">
                <i class="fas fa-calendar-alt"></i>
                <span>Facilities</span>
            </a>

            <a href="student/tournament_fixtures.php" class="sport-link">
                <i class="fas fa-trophy"></i>
                <span>Tournaments</span>
            </a>

            <a href="student/matches.php" class="sport-link">
                <i class="fas fa-futbol"></i>
                <span>Matches</span>
            </a>
        </div>
    </section>

    <!-- Core Features Section -->
    <section id="features" class="features">
        <h2>Everything Your Team Needs to Succeed</h2>
        <p class="section-desc">From managing your club to organizing matches and booking facilities, SportsHub brings your sports operations together in one place.</p>
        
        <div class="features-grid">
            <?php 
                $f1 = (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) ? 'student/clubs.php' : 'register.php';
                $f2 = (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) ? 'student/book_facility.php' : 'login.php';
                $f3 = (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) ? 'student/tournament_fixtures.php' : 'register.php';
            ?>
            <div class="feature-box" onclick="location.href='<?php echo $f1; ?>'">
                <i class="fa-solid fa-users-rectangle"></i>
                <h3>Club Management</h3>
                <p>Register sports clubs, review student membership requests, and control activities smoothly[cite: 1].</p>
            </div>
            <div class="feature-box" onclick="location.href='<?php echo $f2; ?>'">
                <i class="fa-solid fa-calendar-days"></i>
                <h3>Facility Bookings</h3>
                <p>Schedule training grounds, indoor courts, and equipment slots without booking conflicts[cite: 1].</p>
            </div>
            <div class="feature-box" onclick="location.href='<?php echo $f3; ?>'">
                <i class="fa-solid fa-trophy"></i>
                <h3>Tournaments & Fixtures</h3>
                <p>Generate tournament brackets, track match scores in real time, and publish leaderboards[cite: 1].</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-col">
                <div class="logo">
                    <i class="fa-solid fa-futbol"></i> <span class="logo-text">SPORTS<strong>HUB</strong></span>
                </div>
                <p>The ultimate software platform for sports administration, clubs, and facility operations.</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <a href="index.php">Home</a>
                <a href="#features">Platform</a>
                <a href="login.php">Login</a>
            </div>
            <div class="footer-col">
                <h4>Portals</h4>
                <a href="register.php">Register Account</a>
                <a href="login.php">Admin Sign In</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> SportsHub. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript File -->
    <script src="js/home.js"></script>
</body>
</html>