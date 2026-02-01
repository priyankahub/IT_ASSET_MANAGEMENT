<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADJQM') {
    die("Access Denied");
}

if (isset($_POST['condemn']) && !empty($_POST['equipment_ids'])) {
    foreach ($_POST['equipment_ids'] as $eid) {
        mysqli_query($conn,"UPDATE equipment SET status='Condemned' WHERE id='$eid'");
        mysqli_query($conn,"
            INSERT INTO disposal (equipment_id, disposal_date, reason)
            VALUES ('$eid',CURDATE(),'Condemned due to expiry / condition')
        ");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Condemnation</title>
<script src="../assets/js/script.js"></script>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:95%;
    max-width:1100px;
    margin:30px auto;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,.2);
    margin-bottom:25px;
}
h2,h3{color:#2a5298;}
select,button{
    padding:8px;
    border-radius:5px;
}
button{
    background:#2a5298;
    color:#fff;
    border:none;
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:10px;
    border-bottom:1px solid #ddd;
}
th{background:#f4f6f9;}
.back{text-align:center;}
.back a{color:#fff;text-decoration:none;}
</style>
</head>

<body>

<div class="container">

<div class="card">
<h2>Condemnation & Disposal</h2>

<form method="GET">
<select name="status">
<option value="">All</option>
<option>Serviceable</option>
<option>Under Repair</option>
<option>Unserviceable</option>
</select>
<button type="submit">Filter</button>
</form>
</div>

<div class="card">
<h3>Equipment Near Expiry</h3>

<form method="POST" onsubmit="return confirmAction('Condemn selected equipment?')">
<table>
<tr>
<th>Select</th><th>ID</th><th>Type</th><th>Make</th><th>Expiry</th><th>Status</th>
</tr>

<?php
$where="WHERE status!='Condemned'";
if(!empty($_GET['status'])){
$where="WHERE status='".$_GET['status']."'";
}

$res=mysqli_query($conn,"
SELECT * FROM equipment $where ORDER BY warranty_end ASC
");

while($r=mysqli_fetch_assoc($res)){
echo "<tr>
<td><input type='checkbox' name='equipment_ids[]' value='{$r['id']}'></td>
<td>{$r['id']}</td>
<td>{$r['type']}</td>
<td>{$r['make']}</td>
<td>{$r['warranty_end']}</td>
<td>{$r['status']}</td>
</tr>";
}
?>
</table>
<br>
<button name="condemn">Condemn Selected</button>
</form>
</div>

<div class="card">
<h3>Condemned Equipment History</h3>

<table>
<tr>
<th>ID</th><th>Type</th><th>Make</th><th>Date</th><th>Reason</th>
</tr>

<?php
$hist=mysqli_query($conn,"
SELECT e.id,e.type,e.make,d.disposal_date,d.reason
FROM disposal d JOIN equipment e ON d.equipment_id=e.id
ORDER BY d.disposal_date DESC
");

while($h=mysqli_fetch_assoc($hist)){
echo "<tr>
<td>{$h['id']}</td>
<td>{$h['type']}</td>
<td>{$h['make']}</td>
<td>{$h['disposal_date']}</td>
<td>{$h['reason']}</td>
</tr>";
}
?>
</table>
</div>

<div class="back">
<a href="../dashboard.php">⬅ Back to Dashboard</a>
</div>

</div>
</body>
</html>
