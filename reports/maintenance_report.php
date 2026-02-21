<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] == 'CLERK') {
    die("Access Denied");
}

$result = mysqli_query($conn, "
    SELECT e.type, e.make,
           m.maintenance_type, m.start_date,
           m.status, m.remarks
    FROM maintenance m
    JOIN equipment e ON m.equipment_id = e.id
    ORDER BY m.start_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Maintenance Report</title>

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
    max-width:1200px;
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
    margin-bottom:20px;
    font-weight:600;
}

/* EXPORT BUTTON */
.export{
    margin-bottom:20px;
}

.export a{
    text-decoration:none;
    padding:10px 20px;
    background:#d4af37;
    color:#0f223a;
    border-radius:6px;
    font-weight:600;
    transition:.3s;
}

.export a:hover{
    background:#c39c2d;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
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
.status-completed{
    color:#6dd3ce;
    font-weight:600;
}

.status-pending{
    color:#ffd166;
    font-weight:600;
}

.status-critical{
    color:#ff8fa3;
    font-weight:600;
}

/* EMPTY STATE */
.no-data{
    padding:20px;
    background:#1b3557;
    border-radius:6px;
    color:#b8c6db;
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
INF BN – MAINTENANCE REPORT CONTROL
</div>

<div class="container">

<div class="card">
<h2>Maintenance Report</h2>

<div class="export">
<a href="export_maintenance.php">Export CSV</a>
</div>

<?php if (mysqli_num_rows($result) > 0) { ?>

<table>
<tr>
<th>Equipment</th>
<th>Maintenance Type</th>
<th>Date</th>
<th>Status</th>
<th>Remarks</th>
</tr>

<?php
while ($row = mysqli_fetch_assoc($result)) {

$statusClass = "";
if($row['status']=="Completed") $statusClass="status-completed";
if($row['status']=="Pending") $statusClass="status-pending";
if($row['status']=="Critical") $statusClass="status-critical";

echo "<tr>
    <td>{$row['type']} ({$row['make']})</td>
    <td>{$row['maintenance_type']}</td>
    <td>{$row['start_date']}</td>
    <td class='$statusClass'>{$row['status']}</td>
    <td>{$row['remarks']}</td>
</tr>";
}
?>

</table>

<?php } else { ?>

<div class="no-data">
No maintenance records available.
</div>

<?php } ?>

</div>

<div class="back">
<a href="../dashboard.php">Return to Dashboard</a>
</div>

</div>

</body>
</html>
