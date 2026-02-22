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
        📚 2026 New batch enrolled for Infantry School, MHOW | 
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

    <!-- Gallery -->
    <div class="dropdown">
        <button class="dropbtn">Gallery ▾</button>
        <div class="dropdown-content">
            <a href="https://indianarmy.nic.in/Media/" target="_blank">Photos</a>
            <a href="https://indianarmy.nic.in/Media/Videos" target="_blank">Videos</a>
        </div>
    </div>

    <!-- External Links -->
    <div class="dropdown">
        <button class="dropbtn">External Links ▾</button>
        <div class="dropdown-content">
            <a href="https://www.mod.gov.in/" target="_blank">Ministry of Defence</a>
            <a href="https://indianarmy.nic.in/Home/Index" target="_blank">Indian Army</a>
            <a href="https://indiannavy.nic.in/" target="_blank">Indian Navy</a>
            <a href="https://indianairforce.nic.in/" target="_blank">Indian Airforce</a>
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

    <!-- LEFT SIDE (Slider + Commands stacked) -->
    <div class="left-panel">

        <div class="slider-container">
            <div class="slider">
                <img src="images/infantry1.jpg" class="slide active">
                <img src="images/infantry2.jpg" class="slide">
                <img src="images/infantry3.jpg" class="slide">

                <button class="prev" onclick="moveSlide(-1)">❮</button>
                <button class="next" onclick="moveSlide(1)">❯</button>
            </div>
        </div>

        <!-- ================= INDIAN ARMY COMMAND PANEL ================= -->
        <div class="commands-panel">
            <h3>Indian Army Commands</h3>

            <div class="commands-row">

                <a href="https://indianarmy.nic.in/command/command/western-command-commands-site-main" target="_blank" class="command-item">
                    <img src="images/Western.png" alt="Western Command">
                    <span>Western</span>
                </a>

                <a href="https://indianarmy.nic.in/command/command/southern-command-commands-site-main" target="_blank" class="command-item">
                    <img src="images/Southern.png" alt="Southern Command">
                    <span>Southern</span>
                </a>

                <a href="https://indianarmy.nic.in/command/command/northern-command-commands-site-main" target="_blank" class="command-item">
                    <img src="images/Northen.png" alt="Northern Command">
                    <span>Northern</span>
                </a>

                <a href="https://indianarmy.nic.in/command/command/eastern-command-commands-site-main" target="_blank" class="command-item">
                    <img src="images/Eastern.png" alt="Eastern Command">
                    <span>Eastern</span>
                </a>

                <a href="https://indianarmy.nic.in/command/command/central-command-commands-site-main" target="_blank" class="command-item">
                    <img src="images/Central.png" alt="Central Command">
                    <span>Central</span>
                </a>

                <a href="https://indianarmy.nic.in/command/command/artrac-commands-site-main" target="_blank" class="command-item">
                    <img src="images/Arctrac.png" alt="ARTRAC">
                    <span>ARTRAC</span>
                </a>

                <a href="https://indianarmy.nic.in/command/command/south-western-command-commands-site-main" target="_blank" class="command-item">
                    <img src="images/Southern-Western.png" alt="South Western Command">
                    <span>South Western</span>
                </a>

            </div>
        </div>

    </div>

    <!-- RIGHT SIDE PANEL -->
    <div class="right-panel">

        <div class="info-card">
            <h3>Latest News</h3>
            <div class="news-scroll">
                <ul>
                    <li>Annual Tactical Training Exercise completed successfully.</li>
                    <li>Modern Warfare Simulation Lab inaugurated at Mhow.</li>
                    <li>Young Officers Leadership Capsule launched.</li>
                    <li>Joint Indo-Foreign Military Exercise concluded.</li>
                    <li>Cyber Warfare Awareness Workshop conducted.</li>
                    <li>Infantry Tactical Innovation Challenge announced.</li>
                    <li>High Altitude Survival Training completed.</li>
                    <li>Advanced Drone Combat Training integrated.</li>
                    <li>Army Day Parade rehearsals underway.</li>
                    <li>New IT Infrastructure Modernization Phase approved.</li>
                </ul>
            </div>
        </div>

        <div class="info-card">
            <h3>Notices / Circulars</h3>
            <div class="notice-scroll">
                <ul>
                    <li>Update asset inventory before 30th Sept.</li>
                    <li>System maintenance scheduled Sunday 02:00 AM.</li>
                    <li>New IT Asset Policy effective immediately.</li>
                    <li>Cyber security awareness training mandatory.</li>
                    <li>Quarterly Audit documentation submission due.</li>
                    <li>All units to verify asset tagging compliance.</li>
                    <li>Password reset advisory issued for all users.</li>
                    <li>Annual hardware verification drive initiated.</li>
                    <li>Procurement guidelines updated as per MoD directive.</li>
                    <li>Data backup validation exercise to commence next week.</li>
                </ul>
            </div>
        </div>

        <div class="info-card">
            <h3>Useful Links</h3>
            <ul>
                <li><a href="https://indianarmy.nic.in/" target="_blank">Indian Army Official Website</a></li>
                <li><a href="https://www.rashtriyamilitaryschools.edu.in/" target="_blank">Rashtriya Military School</a></li>
                <li><a href="https://indianarmy.nic.in/Training/training-site-main/training-teams" target="_blank">Training & Doctrine</a></li>
                <li><a href="https://indianarmy.nic.in/honours/honours-awards-site-main/honorary-commission" target="_blank">Honorary Commissions</a></li>
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