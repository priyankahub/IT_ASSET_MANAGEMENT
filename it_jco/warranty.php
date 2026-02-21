<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'ITJCO') {
    die("Access Denied");
}

$today = date('Y-m-d');

$result = mysqli_query($conn, "
    SELECT type, make, model, serial_no, warranty_end
    FROM equipment
    WHERE status != 'Condemned'
    ORDER BY warranty_end ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Warranty Tracking</title>

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

/* Subtle sweep animation */
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
    margin-bottom:20px;
    font-weight:600;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#122944;
    padding:12px;
    text-align:left;
    font-weight:600;
}

td{
    padding:12px;
    border-bottom:1px solid #2c4c73;
}

tr:hover{
    background:#1a355a;
}

/* STATUS COLORS */
.expired{
    color:#ff8fa3;
    font-weight:600;
}

.warning{
    color:#ffd166;
    font-weight:600;
}

.ok{
    color:#6dd3ce;
    font-weight:600;
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
INF BN – WARRANTY MONITORING CONTROL
</div>

<div class="container">

<div class="card">

<h2>Warranty Tracking</h2>

<table>
<tr>
<th>Equipment</th>
<th>Serial No</th>
<th>Warranty End</th>
<th>Status</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result)){
    $statusClass="ok";
    $statusText="Valid";

    if($row['warranty_end'] < $today){
        $statusClass="expired";
        $statusText="Expired";
    } elseif($row['warranty_end'] <= date('Y-m-d', strtotime('+30 days'))){
        $statusClass="warning";
        $statusText="Near Expiry";
    }

    echo "<tr>
        <td>{$row['type']} - {$row['make']} {$row['model']}</td>
        <td>{$row['serial_no']}</td>
        <td>{$row['warranty_end']}</td>
        <td class='$statusClass'>$statusText</td>
    </tr>";
}
?>
</table>

</div>

<div class="back">
<a href="../dashboard.php">Return to Dashboard</a>
</div>

</div>
</body>
</html>