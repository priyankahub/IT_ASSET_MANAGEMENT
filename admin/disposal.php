<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'ADMIN') {
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
<title>Condemnation Control</title>
<script src="../assets/js/script.js"></script>

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
    max-width:1300px;
    margin:50px auto;
}

/* CARD */
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    margin-bottom:40px;
    border-left:4px solid #d4af37;
}

/* TITLES */
h2,h3{
    margin-bottom:20px;
    font-weight:600;
    color:#ffffff;
}

/* FORM CONTROLS */
select{
    padding:8px 10px;
    border-radius:4px;
    border:1px solid #2c4c73;
    background:#0f223a;
    color:#ffffff;
    margin-right:10px;
}

button{
    padding:10px 18px;
    border:none;
    border-radius:4px;
    background:#d4af37;
    color:#0f223a;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#c39c2d;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

th{
    background:#122944;
    padding:12px;
    text-align:left;
    font-weight:600;
    color:#f5f7fa;
}

td{
    padding:12px;
    border-bottom:1px solid #2c4c73;
}

tr:hover{
    background:#1a355a;
}

input[type="checkbox"]{
    transform:scale(1.2);
}

/* BACK BUTTON */
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
INF BN – CONDEMNATION & DISPOSAL CONTROL
</div>

<div class="container">

<!-- FILTER CARD -->
<div class="card">
<h2>Condemnation & Disposal</h2>

<form method="GET">

<select name="status">
<option value="">All Status</option>

<option value="Serviceable"
<?php if(isset($_GET['status']) && $_GET['status']=="Serviceable") echo "selected"; ?>>
Serviceable
</option>

<option value="Under Repair"
<?php if(isset($_GET['status']) && $_GET['status']=="Under Repair") echo "selected"; ?>>
Under Repair
</option>

<option value="Unserviceable"
<?php if(isset($_GET['status']) && $_GET['status']=="Unserviceable") echo "selected"; ?>>
Unserviceable
</option>

</select>

<button type="submit">Filter</button>

</form>

</div>

<!-- NEAR EXPIRY / SELECTION -->
<div class="card">
<h3>Equipment Near Expiry / Pending Disposal</h3>

<form method="POST" onsubmit="return confirmAction('Condemn selected equipment?')">
<table>
<tr>
<th>Select</th>
<th>ID</th>
<th>Type</th>
<th>Make</th>
<th>Expiry</th>
<th>Status</th>
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

<!-- HISTORY -->
<div class="card">
<h3>Condemned Equipment History</h3>

<table>
<tr>
<th>ID</th>
<th>Type</th>
<th>Make</th>
<th>Disposal Date</th>
<th>Reason</th>
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
<a href="../dashboard.php">Return to Dashboard</a>
</div>

</div>

</body>
</html>