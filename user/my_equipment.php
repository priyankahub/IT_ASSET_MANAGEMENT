<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'USER') {
    die("Access Denied");
}

$username = $_SESSION['name'];

/* Fetch allocated equipment for this user */
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
    margin-bottom:10px;
}
p{
    color:#555;
}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th,td{
    padding:10px;
    border-bottom:1px solid #ddd;
    text-align:left;
}
th{
    background:#f4f6f9;
}
.no-data{
    margin-top:20px;
    padding:15px;
    background:#f4f6f9;
    border-radius:5px;
    color:#555;
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
<h2>My Allocated Equipment</h2>
<p>Welcome, <b><?php echo htmlspecialchars($username); ?></b></p>

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
    echo "<tr>
        <td>{$row['type']}</td>
        <td>{$row['make']}</td>
        <td>{$row['model']}</td>
        <td>{$row['serial_no']}</td>
        <td>{$row['issue_date']}</td>
        <td>{$row['status']}</td>
    </tr>";
}
?>

</table>

<?php } else { ?>

<div class="no-data">
    ✅ No equipment is currently allocated to you.
</div>

<?php } ?>

</div>

<div class="back">
<a href="../dashboard.php">⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>
