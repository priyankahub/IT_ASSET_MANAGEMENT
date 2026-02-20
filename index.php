<!DOCTYPE html>
<html>
<head>
<title>THE INFANTRY SCHOOL MHOW</title>

<style>
body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#f5f7fa;
}

/* ===== HEADER ===== */
.header{
    background:#1e3c72;
    color:#fff;
    padding:20px;
    text-align:center;
    font-size:28px;
    font-weight:bold;
}

/* ===== NAVBAR ===== */
.navbar{
    background:#2a5298;
    display:flex;
    justify-content:center;
    gap:40px;
    padding:12px;
}
.navbar a{
    color:white;
    text-decoration:none;
    font-weight:bold;
}
.navbar a:hover{
    text-decoration:underline;
}

/* ===== CONTAINER ===== */
.container{
    width:95%;
    max-width:1200px;
    margin:20px auto;
}

/* ===== SLIDER ===== */
.slider-container{
    position:relative;
    overflow:hidden;
    width:100%;
    height:400px;
    border-radius:10px;
}

.slider{
    display:flex;
    width:300%;
    animation:slide 15s infinite;
}

.slider img{
    width:100%;
    height:400px;
    object-fit:cover;
}

@keyframes slide{
    0% {margin-left:0%;}
    33% {margin-left:0%;}
    36% {margin-left:-100%;}
    66% {margin-left:-100%;}
    69% {margin-left:-200%;}
    100% {margin-left:-200%;}
}

/* ===== NEWS SECTION ===== */
.news-section{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:20px;
    margin-top:20px;
}

.card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

.card h3{
    margin-top:0;
    color:#1e3c72;
}

/* ===== FOOTER ===== */
.footer{
    background:#1e3c72;
    color:white;
    padding:20px;
    text-align:center;
    margin-top:30px;
}
.footer a{
    color:#ffd700;
    text-decoration:none;
}
.footer a:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
THE INFANTRY SCHOOL MHOW
</div>

<!-- NAVBAR -->
<div class="navbar">
<a href="#">Home</a>
<a href="#">About Us</a>
<a href="#">Administration</a>
<a href="#">Contact Us</a>
<a href="auth/login.php">Login</a>
<a href="auth/register.php">Sign Up</a>
</div>

<div class="container">

<!-- SLIDER -->
<div class="slider-container">
<div class="slider">
<img src="images/infantry1.jpg">
<img src="images/infantry2.jpg">
<img src="images/infantry3.jpg">
</div>
</div>

<!-- NEWS & NOTICES -->
<div class="news-section">

<div class="card">
<h3>Latest News</h3>
<ul>
<li>Annual Tactical Training Exercise successfully completed.</li>
<li>New Infantry Leadership Development Program launched.</li>
<li>Modernized Simulation Lab inaugurated at Mhow Campus.</li>
<li>Joint Military Training Program scheduled for next quarter.</li>
</ul>
</div>

<div class="card">
<h3>Notices / Circulars</h3>
<ul>
<li>All personnel must update asset inventory by 30th September.</li>
<li>System maintenance scheduled this Sunday (02:00–04:00 hrs).</li>
<li>New IT Asset Allocation Policy effective immediately.</li>
<li>Security awareness training mandatory for all staff.</li>
</ul>
</div>

</div>

<!-- USEFUL LINKS -->
<div class="card" style="margin-top:20px;">
<h3>Useful Links</h3>
<ul>
<li><a href="https://indianarmy.nic.in" target="_blank">Indian Army Official Website</a></li>
<li><a href="#">Training & Doctrine Resources</a></li>
<li><a href="#">Internal Asset Management Portal</a></li>
<li><a href="#">Help Desk & Support</a></li>
</ul>
</div>

</div>

<!-- FOOTER -->
<div class="footer">
&copy; 2026 THE INFANTRY SCHOOL MHOW | IT Asset Management System
</div>

</body>
</html>