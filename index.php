<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>IT Asset Lifecycle Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="homepage">

<!-- ================= HEADER ================= -->
<div class="header-banner">
    <img src="images/bannerpic.png" class="banner-img">
    <div class="banner-overlay"></div>

    <div class="banner-content">
        <img src="images/logo.jpg" class="logo">
        <h1>THE INFANTRY SCHOOL MHOW</h1>
        <img src="images/logo.jpg" class="logo">
    </div>
</div>

<!-- ================= MOVING RIBBON ================= -->
<div class="news-ticker">
    <div class="ticker-track">
        🔔 System Maintenance on Sunday 02:00 AM |
        🚀 New Asset Tracking Module Launched |
        🔐 Security Upgrade Completed Successfully |
        📢 Welcome to IT Equipment Lifecycle Management Portal
    </div>
</div>

<!-- ================= NAVBAR ================= -->
<div class="navbar">

    <!-- Home icon -->
    <a href="index.php" class="home-icon">🏠</a>

    <!-- About Us -->
    <div class="dropdown">
        <button class="dropbtn">About Us ▾</button>
        <div class="dropdown-content">
            <a href="https://indianarmy.nic.in/KnowYourArmy/know-your-army-main/history" target="_blank">History</a>
            <a href="https://nda.nic.in/site-page-viewer/21" target="_blank">Mission & Vision</a>
            <a href="https://indianarmy.nic.in/leaders/leaders-site-main/chief-of-the-army-staff-leaders-site-main" target="_blank">Leadership</a>
        </div>
    </div>

    <!-- Know Your Army -->
    <div class="dropdown">
        <button class="dropbtn">Know Your Army ▾</button>
        <div class="dropdown-content">
            <a href="https://indianarmy.nic.in/KnowYourArmy/know-your-army-main/combat-edge" target="_blank">Combat Edge</a>
            <a href="https://indianarmy.nic.in/KnowYourArmy/know-your-army-main/command-and-control" target="_blank">Command & Control</a>
            <a href="https://indianarmy.nic.in/KnowYourArmy/know-your-army-main/leadership" target="_blank">Leadership</a>
            <a href="https://indianarmy.nic.in/KnowYourArmy/know-your-army-main/operations-un-mission" target="_blank">Operations UN Mission</a>
            <a href="https://indianarmy.nic.in/KnowYourArmy/know-your-army-main/afspa" target="_blank">AFSPA</a>
        </div>
    </div>

    <!-- Contact Us -->
    <a href="https://joinindianarmy.nic.in/contact-us.htm" target="_blank">Contact Us</a>

    <!-- LOGIN + SIGNUP (Correct Paths) -->
    <div class="nav-right">
        <a href="auth/login.php" class="login-btn">Login</a>
        <a href="auth/register.php" class="signup-btn">Sign Up</a>
    </div>

</div>

<!-- ================= MAIN SECTION ================= -->
<div class="main-section">

    <div class="slider-container">
        <div class="slider">
            <img src="images/infantry1.jpg" class="slide active">
            <img src="images/infantry2.jpg" class="slide">
            <img src="images/infantry3.jpg" class="slide">

            <button class="prev" onclick="moveSlide(-1)">❮</button>
            <button class="next" onclick="moveSlide(1)">❯</button>
        </div>
    </div>

    <div class="right-panel">

        <div class="info-card">
            <h3>Latest News</h3>
            <ul>
                <li>Annual Tactical Training Exercise completed.</li>
                <li>Modern Warfare Simulation Lab inaugurated.</li>
                <li>Leadership Program launched.</li>
                <li>Joint Exercise scheduled next quarter.</li>
            </ul>
        </div>

        <div class="info-card">
            <h3>Notices / Circulars</h3>
            <ul>
                <li>Update asset inventory before 30th Sept.</li>
                <li>System maintenance this Sunday.</li>
                <li>New IT Asset Policy effective immediately.</li>
                <li>Cyber security training mandatory.</li>
            </ul>
        </div>

        <div class="info-card">
            <h3>Useful Links</h3>
            <ul>
                <li>
                    <a href="https://indianarmy.nic.in/" target="_blank">
                        Indian Army Official Website
                    </a>
                </li>
                <li>
                    <a href="https://www.rashtriyamilitaryschools.edu.in/" target="_blank">
                        Rashtriya Military School
                    </a>
                </li>
                <li>
                    <a href="https://indianarmy.nic.in/Training/training-site-main/training-teams" target="_blank">
                        Training & Doctrine
                    </a>
                </li>
                <li>
                    <a href="https://indianarmy.nic.in/honours/honours-awards-site-main/honorary-commission" target="_blank">
                        Honorary Commissions
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>

<div class="footer">
    © 2026 THE INFANTRY SCHOOL MHOW
</div>

<script src="assets/js/script.js"></script>
<script src="assets/js/slider.js"></script>

</body>
</html>