<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'CO') {
    die("Access Denied");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Equipment Life Cycle</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#0f223a;
    color:#f5f7fa;
}

/* HEADER */
.header{
    padding:25px 0;
    font-size:22px;
    font-weight:600;
    background:#122944;
    border-bottom:3px solid #d4af37;
    text-align:center;
}

/* CONTAINER */
.container{
    width:95%;
    max-width:1100px;
    margin:50px auto;
}

/* CARD */
.card{
    background:#162f4f;
    padding:40px;
    border-radius:6px;
    border-left:4px solid #d4af37;
    transition:all 0.35s ease;
    position:relative;
    overflow:hidden;
}

/* Lift + Glow */
.card:hover{
    transform:translateY(-10px) scale(1.01);
    background:#1d3a5c;
    box-shadow:
        0 0 25px rgba(212,175,55,0.6),
        0 20px 40px rgba(0,0,0,0.75);
}

/* Light sweep animation */
.card::before{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:100%;
    background:linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.08),
        transparent
    );
    transition:0.6s;
}

.card:hover::before{
    left:100%;
}

/* TITLE */
h2{
    margin-bottom:35px;
    font-weight:600;
    text-align:center;
}

/* TIMELINE */
.timeline{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    align-items:center;
    gap:15px;
}

/* STEP BOX */
.step{
    background:#1b3557;
    padding:18px 22px;
    border-radius:6px;
    min-width:150px;
    text-align:center;
    font-weight:600;
    border-top:3px solid #d4af37;
    transition:all 0.3s ease;
    position:relative;
}

.step:hover{
    background:#1f3d65;
    transform:translateY(-6px);
    box-shadow:0 0 15px rgba(212,175,55,0.5);
}

/* ARROW */
.arrow{
    font-size:22px;
    color:#d4af37;
    font-weight:bold;
}

/* FOOTER */
.back{
    text-align:center;
    margin-top:40px;
}

.back a{
    text-decoration:none;
    padding:12px 24px;
    background:#d4af37;
    color:#0f223a;
    border-radius:6px;
    font-weight:600;
}

.back a:hover{
    background:#c39c2d;
}
</style>
</head>

<body>

<div class="header">
INF BN – IT EQUIPMENT LIFE CYCLE CONTROL
</div>

<div class="container">

<div class="card">
<h2>IT Equipment Life Cycle Flow</h2>

<div class="timeline">
    <div class="step">Procurement</div>
    <div class="arrow">➜</div>
    <div class="step">Issue</div>
    <div class="arrow">➜</div>
    <div class="step">Use</div>
    <div class="arrow">➜</div>
    <div class="step">Maintenance</div>
    <div class="arrow">➜</div>
    <div class="step">Repair</div>
    <div class="arrow">➜</div>
    <div class="step">Disposal</div>
</div>

</div>

<div class="back">
<a href="../dashboard.php">Return to Dashboard</a>
</div>

</div>

</body>
</html>