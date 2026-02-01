<?php
session_start();
include("../config/db.php");

if (!in_array($_SESSION['role'], ['CO','ADJQM'])) {
    die("Access Denied");
}

$res = mysqli_query(
    $conn,
    "SELECT status, COUNT(*) c FROM equipment GROUP BY status"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Holding State</title>

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
    padding:25px;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,.2);
}
h2{
    color:#2a5298;
    margin-bottom:20px;
}
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:15px;
}
.box{
    background:#f4f6f9;
    padding:20px;
    border-radius:8px;
    text-align:center;
}
.box h3{
    margin:0;
    font-size:18px;
    color:#333;
}
.box p{
    font-size:28px;
    font-weight:bold;
    margin-top:10px;
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
<h2>Equipment Holding State</h2>

<div class="grid">
<?php
while ($r = mysqli_fetch_assoc($res)) {
    echo "
    <div class='box'>
        <h3>{$r['status']}</h3>
        <p>{$r['c']}</p>
    </div>";
}
?>
</div>

</div>

<div class="back">
<a href="../dashboard.php">⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>
