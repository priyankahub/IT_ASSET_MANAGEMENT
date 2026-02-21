<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'USER') {
    die("Access Denied");
}

$username = $_SESSION['username']; // correct mapping

$result = mysqli_query($conn, "
    SELECT e.type, e.make, e.model, e.serial_no,
           a.issue_date, a.status
    FROM allocation a
    JOIN equipment e ON a.equipment_id = e.id
    WHERE a.allotted_to = '$username'
      AND a.status = 'Issued'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Equipment</title>

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
    max-width:1000px;
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

/* Highlight sweep animation */
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
    table-layout:fixed;
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
    text-align:left;
    word-wrap:break-word;
}

tr:hover{
    background:#1a355a;
}

/* STATUS COLOR */
.status-serviceable{
    color:#6dd3ce;
    font-weight:600;
}

.status-condemned{
    color:#ff8fa3;
    font-weight:600;
}

/* EMPTY STATE */
.no-data{
    margin-top:20px;
    padding:20px;
    background:#1b3557;
    border-radius:6px;
    color:#b8c6db;
    text-align:center;
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
INF BN – MY ALLOCATED EQUIPMENT
</div>

<div class="container">

<div class="card">
<h2>My Allocated Equipment</h2>

<?php if (mysqli_num_rows($result) > 0) { ?>

<table>
<tr>
    <th>Type</th>
    <th>Make</th>
    <th>Model</th>
    <th>Serial No</th>
    <th>Issue Date</th>
    <th>Status</th>
</tr>

<?php
while ($row = mysqli_fetch_assoc($result)) {

$statusClass = ($row['status']=='Serviceable')
               ? "status-serviceable"
               : "status-condemned";

echo "<tr>
    <td>{$row['type']}</td>
    <td>{$row['make']}</td>
    <td>{$row['model']}</td>
    <td>{$row['serial_no']}</td>
    <td>{$row['issue_date']}</td>
    <td class='$statusClass'>{$row['status']}</td>
</tr>";
}
?>

</table>

<?php } else { ?>

<div class="no-data">
No equipment is currently allocated to you.
</div>

<?php } ?>

</div>

<div class="back">
<a href="../dashboard.php">Return to Dashboard</a>
</div>

</div>

</body>
</html>
