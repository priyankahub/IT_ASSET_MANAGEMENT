<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] == 'CLERK') {
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
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:90%;
    max-width:1100px;
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
}
.export{
    margin-bottom:15px;
}
.export a{
    background:#2a5298;
    color:#fff;
    padding:8px 15px;
    border-radius:5px;
    text-decoration:none;
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:10px;
    border-bottom:1px solid #ddd;
}
th{
    background:#f4f6f9;
}
.no-data{
    padding:15px;
    background:#f4f6f9;
    border-radius:5px;
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
<h2>Maintenance Report</h2>

<div class="export">
<a href="export_maintenance.php">⬇ Export CSV</a>
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
    echo "<tr>
        <td>{$row['type']} ({$row['make']})</td>
        <td>{$row['maintenance_type']}</td>
        <td>{$row['start_date']}</td>
        <td>{$row['status']}</td>
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
<a href="../dashboard.php">⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>
