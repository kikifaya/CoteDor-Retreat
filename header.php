<?php
if (!isset($pageTitle)) {
    $pageTitle = "Côte d'Or Retreat";
}
if (!isset($solidNav)) {
    $solidNav = false;
}
if (!isset($currentPage)) {
    $currentPage = "";
}
$isLoggedIn = isset($_SESSION["user_id"]);
$isAdmin = $isLoggedIn && $_SESSION["role"] == "admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> | Côte d'Or Retreat</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top site-nav <?php if ($solidNav) { echo "nav-solid scrolled"; } ?>" id="siteNav">
        <div class="container-fluid px-3 px-lg-5">
            <a class="navbar-brand brand" href="index.html">
                Côte d'Or
                <span>Retreat &middot; Praslin</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-lines"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav nav-left">
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentPage == "rooms") { echo "active"; } ?>" href="rooms.php">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentPage == "experiences") { echo "active"; } ?>" href="experiences.html">Experiences</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if ($currentPage == "contact") { echo "active"; } ?>" href="contact.html">Contact</a>
                    </li>
                </ul>

                <ul class="navbar-nav nav-right align-items-lg-center">
                    <?php if ($isAdmin) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($currentPage == "admin") { echo "active"; } ?>" href="admin_dashboard.php">Admin</a>
                        </li>
                    <?php } elseif ($isLoggedIn) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($currentPage == "my_bookings") { echo "active"; } ?>" href="my_bookings.php">My bookings</a>
                        </li>
                    <?php } ?>

                    <?php if ($isLoggedIn) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Log out</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($currentPage == "login") { echo "active"; } ?>" href="login.php">Log in</a>
                        </li>
                    <?php } ?>

                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-lux btn-nav" href="booking.php">Book now</a>
                    </li>
                    <li class="nav-item d-none d-lg-block ms-lg-3">
                        <button type="button" class="menu-btn" onclick="openMenu()">
                            Menu <span class="menu-lines"></span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="menu-overlay" id="menuOverlay">
        <div class="menu-bg" id="menuBg"></div>
        <div class="menu-shade"></div>

        <button type="button" class="menu-close" onclick="closeMenu()">Close <span>&times;</span></button>

        <ul class="menu-links">
            <li><a href="index.html" data-image="hero.jpg" onmouseover="changeMenuBackground(this)" onmouseout="resetMenuBackground(this)">Home</a></li>
            <li><a href="rooms.php" data-image="room-double.jpg" onmouseover="changeMenuBackground(this)" onmouseout="resetMenuBackground(this)">Rooms</a></li>
            <li><a href="experiences.html" data-image="spa.jpg" onmouseover="changeMenuBackground(this)" onmouseout="resetMenuBackground(this)">Experiences</a></li>
            <li><a href="booking.php" data-image="pool.jpg" onmouseover="changeMenuBackground(this)" onmouseout="resetMenuBackground(this)">Book a stay</a></li>
            <li><a href="contact.html" data-image="beach.jpg" onmouseover="changeMenuBackground(this)" onmouseout="resetMenuBackground(this)">Contact</a></li>
        </ul>

        <div class="menu-info">
            <span>Anse Volbert, Praslin, Seychelles</span>
            <span>+248 4 000 000</span>
            <span>stay@cotedorretreat.com</span>
        </div>
    </div>
