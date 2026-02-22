<?php
session_start();
include("../config/db.php");

if (!in_array($_SESSION['role'], ['CO','ADMIN'])) {
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
    padding:30px;
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
    margin-bottom:25px;
    font-weight:600;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

/* STATUS BOX */
.box{
    background:#1b3557;
    padding:25px;
    border-radius:6px;
    text-align:center;
    transition:.3s;
    border-top:3px solid #d4af37;
}

.box:hover{
    background:#1f3d65;
    transform:translateY(-3px);
}

.box h3{
    margin:0;
    font-size:16px;
    font-weight:600;
    color:#b8c6db;
}

.box p{
    font-size:32px;
    font-weight:700;
    margin-top:10px;
    color:#6dd3ce;
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
INF BN – EQUIPMENT HOLDING STATE
</div>

<div class="container">

<div class="card">
<h2>Equipment Holding Overview</h2>

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