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
    <img src="images/header-bg.jpg" class="banner-img">
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
            <a href="#">History</a>
            <a href="#">Mission & Vision</a>
            <a href="#">Leadership</a>
        </div>
    </div>

    <!-- Know Your Army -->
    <div class="dropdown">
        <button class="dropbtn">Know Your Army ▾</button>
        <div class="dropdown-content">
            <a href="#">Infantry</a>
            <a href="#">Artillery</a>
            <a href="#">Armoured Corps</a>
        </div>
    </div>

    <a href="#">Contact Us</a>

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
                <li><a href="#">Indian Army Official Website</a></li>
                <li><a href="#">Internal Asset Portal</a></li>
                <li><a href="#">Training & Doctrine</a></li>
                <li><a href="#">Help Desk & Support</a></li>
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