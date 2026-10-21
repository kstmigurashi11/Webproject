
<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? '';

$dashboardUrl = ($role === 'admin')
    ? 'admin/dashboard.php'
    : 'student/dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NSBM SportsHub — discover sports clubs, book facilities, follow tournaments and connect with the campus sports community.">

    <title>NSBM SportsHub | Play. Connect. Compete.</title>

    <link rel="stylesheet" href="css/home.css">
    <script src="js/home.js" defer></script>
</head>
<body>

<header class="navbar">
    <a class="brand" href="index.php" aria-label="SportsHub home">
        <span class="brand-mark">S<span>+</span></span>
        <span class="brand-name">NSBM <strong>SPORTSHUB</strong></span>
    </a>

    <button class="menu-toggle" id="menuToggle"
            aria-label="Open navigation" aria-expanded="false"
            aria-controls="navLinks">☰</button>

    <nav class="nav-links" id="navLinks">
        <a class="active" href="#home">Home</a>
        <a href="#features">Explore</a>
        <a href="#about">About</a>
    </nav>

    <div class="nav-actions">
        <?php if ($isLoggedIn): ?>
            <a class="login-link" href="logout.php">Log out</a>
            <a class="btn btn-primary" href="<?= htmlspecialchars($dashboardUrl) ?>">
                Dashboard <span>↗</span>
            </a>
        <?php else: ?>
            <a class="login-link" href="login.php">Log in</a>
            <a class="btn btn-primary" href="register.php">
                Join SportsHub <span>↗</span>
            </a>
        <?php endif; ?>
    </div>
</header>

<main>
    <section class="hero" id="home">
        <div class="hero-bg" aria-hidden="true"></div>

        <div class="hero-content">
            <div class="eyebrow">
                <span class="live-dot"></span>
                THE HOME OF NSBM SPORTS
            </div>

            <h1>YOUR GAME.<br>YOUR <span>COMMUNITY.</span></h1>

            <p class="hero-copy">
                Discover your passion, connect with your team, and take
                your campus sports experience to the next level.
            </p>

            <div class="hero-buttons">
                <?php if ($isLoggedIn): ?>
                    <a class="btn btn-primary btn-large"
                       href="<?= htmlspecialchars($dashboardUrl) ?>">
                        Go to dashboard <span>↗</span>
                    </a>
                <?php else: ?>
                    <a class="btn btn-primary btn-large" href="register.php">
                        Get started <span>↗</span>
                    </a>
                <?php endif; ?>

                <a class="btn btn-glass btn-large" href="#features">
                    <span class="play-icon">▶</span>
                    Explore SportsHub
                </a>
            </div>

            <div class="hero-note">
                <span class="note-line"></span>
                ONE CAMPUS. COUNTLESS WAYS TO PLAY.
            </div>
        </div>

        <div class="hero-tag">
            <span>01</span> BUILT FOR THE LOVE OF SPORT
        </div>

        <div class="hero-bottom">
            <div><strong>01</strong><span>FIND YOUR SPORT</span></div>
            <div><strong>02</strong><span>MEET YOUR TEAM</span></div>
            <div><strong>03</strong><span>MAKE YOUR MOVE</span></div>
        </div>
    </section>

    <section class="intro section-wrap" id="about">
        <div>
            <p class="section-kicker">MORE THAN A GAME</p>
            <h2>Make your campus<br>experience <span>count.</span></h2>
        </div>

        <p class="intro-text">
            From joining a club to booking a facility, SportsHub brings
            your sports life together in one simple place. Find your
            people, build your skills, and create your next great
            campus memory.
        </p>
    </section>

    <section class="features section-wrap" id="features">
        <div class="section-heading">
            <div>
                <p class="section-kicker">WHAT YOU CAN DO</p>
                <h2>Find your <span>next move.</span></h2>
            </div>
            <p>Everything you need to get involved<br>and stay in the game.</p>
        </div>

        <div class="feature-grid">
            <article class="feature-card feature-purple">
                <div class="feature-top">
                    <span class="feature-icon">◎</span>
                    <span class="feature-number">01 / CLUBS</span>
                </div>
                <div class="feature-art art-clubs" aria-hidden="true">
                    <span class="orbit orbit-one"></span>
                    <span class="orbit orbit-two"></span>
                    <span class="art-ball">●</span>
                </div>
                <h3>Find your people.</h3>
                <p>Explore sports clubs, discover shared interests, and become part of a team.</p>
                <a href="login.php" class="feature-link">Explore clubs <span>↗</span></a>
            </article>

            <article class="feature-card feature-blue">
                <div class="feature-top">
                    <span class="feature-icon">▦</span>
                    <span class="feature-number">02 / FACILITIES</span>
                </div>
                <div class="feature-art art-facility" aria-hidden="true">
                    <span class="court-line"></span>
                    <span class="court-circle"></span>
                </div>
                <h3>Your space to play.</h3>
                <p>Check out sports facilities and book a space for your next session.</p>
                <a href="login.php" class="feature-link">View facilities <span>↗</span></a>
            </article>

            <article class="feature-card feature-orange">
                <div class="feature-top">
                    <span class="feature-icon">↗</span>
                    <span class="feature-number">03 / TOURNAMENTS</span>
                </div>
                <div class="feature-art art-trophy" aria-hidden="true">
                    <span>★</span>
                </div>
                <h3>Bring your A-game.</h3>
                <p>Keep up with tournaments, fixtures, and the moments that matter.</p>
                <a href="login.php" class="feature-link">See competitions <span>↗</span></a>
            </article>

            <article class="feature-card feature-green">
                <div class="feature-top">
                    <span class="feature-icon">↗</span>
                    <span class="feature-number">04 / TRAINING</span>
                </div>
                <div class="feature-art art-training" aria-hidden="true">
                    <span class="training-bar"></span>
                    <span class="training-dot"></span>
                </div>
                <h3>Get better every day.</h3>
                <p>Find training opportunities and keep moving towards your goals.</p>
                <a href="login.php" class="feature-link">Find training <span>↗</span></a>
            </article>
        </div>
    </section>

    <section class="cta section-wrap">
        <div class="cta-content">
            <p class="section-kicker">YOUR CAMPUS. YOUR TEAM.</p>
            <h2>Ready to get<br>in the <span>game?</span></h2>
            <p>Create your account and start exploring everything NSBM SportsHub has to offer.</p>

            <a class="btn btn-light btn-large" href="register.php">
                Join the community <span>↗</span>
            </a>
        </div>

        <div class="cta-decoration" aria-hidden="true">
            <div class="big-circle">S<span>+</span></div>
            <span class="cta-caption">PLAY WITH PURPOSE.</span>
        </div>
    </section>
</main>

<footer class="footer">
    <a class="brand footer-brand" href="index.php">
        <span class="brand-mark">S<span>+</span></span>
        <span class="brand-name">NSBM <strong>SPORTSHUB</strong></span>
    </a>

    <p>Made for the NSBM sports community.</p>
    <span class="copyright">© <?= date('Y') ?> NSBM SportsHub</span>
</footer>

</body>
</html>
