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
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:90%;
    max-width:900px;
    margin:40px auto;
}
.card{
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,.2);
}
h2{
    color:#2a5298;
    margin-bottom:25px;
}
.timeline{
    display:flex;
    flex-wrap:wrap;
    justify-content:space-between;
}
.step{
    background:#f4f6f9;
    padding:15px;
    margin:5px;
    border-radius:8px;
    flex:1;
    min-width:120px;
    text-align:center;
    font-weight:bold;
}
.arrow{
    font-size:22px;
    margin:0 5px;
    color:#2a5298;
}
.back{
    text-align:center;
    margin-top:25px;
}
.back a{
    color:#fff;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="container">

<div class="card">
<h2>IT Equipment Life Cycle</h2>

<div class="timeline">
    <div class="step">Procurement</div>
    <div class="arrow">→</div>
    <div class="step">Issue</div>
    <div class="arrow">→</div>
    <div class="step">Use</div>
    <div class="arrow">→</div>
    <div class="step">Maintenance</div>
    <div class="arrow">→</div>
    <div class="step">Repair</div>
    <div class="arrow">→</div>
    <div class="step">Disposal</div>
</div>

</div>

<div class="back">
<a href="../dashboard.php">⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>
