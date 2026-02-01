<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ITJCO') {
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
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:95%;
    max-width:1000px;
    margin:40px auto;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,.2);
}
h2{color:#2a5298;}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th,td{
    padding:10px;
    border-bottom:1px solid #ddd;
}
th{background:#f4f6f9;}
.expired{color:red;font-weight:bold;}
.warning{color:#e67e22;font-weight:bold;}
.ok{color:green;}
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
<a href="../dashboard.php">⬅ Back to Dashboard</a>
</div>

</div>
</body>
</html>
