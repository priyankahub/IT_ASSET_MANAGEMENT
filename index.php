<!DOCTYPE html>
<html>
<head>
<title>THE INFANTRY SCHOOL MHOW</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="assets/js/slider.js" defer></script>
</head>

<body>

<!-- ================= HEADER BANNER ================= -->
<div class="header-banner">
    <img src="images/header-bg.jpg" class="banner-img">

    <div class="banner-overlay"></div>

    <div class="banner-content">
        <img src="images/logo.jpg" class="logo">
        <h1>THE INFANTRY SCHOOL MHOW</h1>
    </div>
</div>

<!-- ================= NAVIGATION ================= -->
<nav class="navbar">

<a href="index.php" class="home-icon">
<i class="fa fa-home"></i>
</a>

<div class="dropdown">
    <button class="dropbtn">About Us <i class="fa fa-caret-down"></i></button>
    <div class="dropdown-content">
        <a href="about/departments.php">Departments</a>
        <a href="about/structure.php">Army Structure</a>
        <a href="about/leadership.php">Leadership</a>
    </div>
</div>

<div class="dropdown">
    <button class="dropbtn">Know Your Army <i class="fa fa-caret-down"></i></button>
    <div class="dropdown-content">
        <a href="army/history.php">History</a>
        <a href="army/role.php">Role</a>
        <a href="army/structure.php">Structure</a>
        <a href="army/rank-badges.php">Rank Badges</a>
        <a href="army/war-memorial.php">War Memorial</a>
    </div>
</div>

<a href="contact.php">Contact Us</a>

<div class="nav-right">
<a href="auth/login.php">Login</a>
<a href="auth/register.php">Sign Up</a>
</div>

</nav>

<!-- ================= MAIN CONTENT ================= -->
<div class="main-section">

<div class="slider-container">
    <button class="prev" onclick="moveSlide(-1)">&#10094;</button>

    <div class="slider">
        <img src="images/infantry1.jpg" class="slide active">
        <img src="images/infantry2.jpg" class="slide">
        <img src="images/infantry3.jpg" class="slide">
    </div>

    <button class="next" onclick="moveSlide(1)">&#10095;</button>
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

<div class="info-card no-bullets">
<h3>Useful Links</h3>
<ul>
<li><a href="https://indianarmy.nic.in" target="_blank">Indian Army Official Website</a></li>
<li><a href="#">Internal Asset Portal</a></li>
<li><a href="#">Training & Doctrine</a></li>
<li><a href="#">Help Desk & Support</a></li>
</ul>
</div>

</div>

</div>

<!-- FOOTER -->
<div class="footer">
© 2026 THE INFANTRY SCHOOL MHOW
</div>

</body>
</html>