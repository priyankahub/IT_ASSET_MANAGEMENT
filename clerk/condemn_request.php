<?php
session_start();
include("../config/db.php");

if ($_SESSION['rank'] != 'CLERK') die("Access Denied");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['submit']) && !empty($_POST['equipment_ids'])){

foreach($_POST['equipment_ids'] as $eid){

$eid = intval($eid); // safety

// Start transaction
mysqli_begin_transaction($conn);

try {

// Check if already pending
$check = mysqli_query($conn,"
SELECT id FROM condemnation_requests 
WHERE equipment_id='$eid' AND status='Pending'
");

if(mysqli_num_rows($check) == 0){

// Insert request
mysqli_query($conn,"
INSERT INTO condemnation_requests
(equipment_id, requested_by, request_date, status)
VALUES
('$eid','{$_SESSION['username']}',CURDATE(),'Pending')
");

// Update equipment status
mysqli_query($conn,"
UPDATE equipment 
SET status='Pending for Approval'
WHERE id='$eid'
");

}

// Commit
mysqli_commit($conn);

} catch (Exception $e) {

mysqli_rollback($conn);
echo "Error: " . $e->getMessage();

}

}

// FORCE PAGE REFRESH
header("Location: condemn_request.php");
exit;

}
?>
<!DOCTYPE html>
<html>
<head>
<title>Raise Condemnation Request</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#0f223a;
    color:#f5f7fa;
}
.header{
    padding:25px 0;
    font-size:22px;
    font-weight:600;
    background:#122944;
    border-bottom:3px solid #d4af37;
    text-align:center;
}
.container{
    width:95%;
    max-width:1200px;
    margin:40px auto;
}
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    border-left:4px solid #d4af37;
    margin-bottom:40px;
}
.card:hover{
    transform:translateY(-5px);
    background:#1d3a5c;
    box-shadow:0 0 20px rgba(212,175,55,0.5);
}
h2{
    margin-bottom:20px;
}
.checkbox-item{
    padding:8px 0;
    border-bottom:1px solid #2c4c73;
}
button{
    margin-top:20px;
    padding:12px;
    width:100%;
    background:#d4af37;
    color:#0f223a;
    border:none;
    border-radius:6px;
    font-weight:600;
    cursor:pointer;
}
button:hover{
    background:#c39c2d;
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:10px;
    border:1px solid #2c4c73;
    text-align:center;
}
th{
    background:#122944;
    color:#d4af37;
}
.footer{
    text-align:center;
}
.footer a{
    text-decoration:none;
    padding:12px 24px;
    background:#d4af37;
    color:#0f223a;
    border-radius:6px;
    font-weight:600;
}
</style>
</head>

<body>

<div class="header">
INF BN – RAISE CONDEMNATION REQUEST
</div>

<div class="container">

<!-- ================= FIRST PANEL ================= -->
<div class="card">
<h2>Select Equipment for Condemnation</h2>

<form method="POST">

<?php
$res=mysqli_query($conn,"
SELECT * FROM equipment e
WHERE e.status!='Condemned'
AND NOT EXISTS (
    SELECT 1 FROM condemnation_requests c
    WHERE c.equipment_id = e.id
    AND c.status='Pending'
)
");

while($e=mysqli_fetch_assoc($res)){
echo "
<div class='checkbox-item'>
<input type='checkbox' name='equipment_ids[]' value='{$e['id']}'>
<strong>{$e['type']}</strong> | {$e['model']} | Serial: {$e['serial_no']}
</div>
";
}
?>

<button name="submit">Send for Approval</button>

</form>
</div>


<!-- ================= SECOND PANEL ================= -->
<div class="card">
<h2>Pending Requests: Equipment Condemnation</h2>

<table>
<tr>
<th>Date</th>
<th>Clerk Name</th>
<th>Clerk Army No</th>
<th>Equipment Type</th>
<th>Serial No</th>
<th>Model</th>
<th>Purchase Date</th>
<th>Warranty End</th>
</tr>

<?php
$res=mysqli_query($conn,"
SELECT 
c.request_date,
u.name,
u.army_no,
e.type,
e.serial_no,
e.model,
e.purchase_date,
e.warranty_end
FROM condemnation_requests c
JOIN equipment e ON c.equipment_id=e.id
JOIN users u ON c.requested_by=u.username
WHERE c.status='Pending'
ORDER BY c.request_date DESC
");

while($r=mysqli_fetch_assoc($res)){
echo "<tr>
<td>{$r['request_date']}</td>
<td>{$r['name']}</td>
<td>{$r['army_no']}</td>
<td>{$r['type']}</td>
<td>{$r['serial_no']}</td>
<td>{$r['model']}</td>
<td>{$r['purchase_date']}</td>
<td>{$r['warranty_end']}</td>
</tr>";
}
?>
</table>

</div>

<div class="footer">
<a href="../dashboard.php">Return to Dashboard</a>
</div>

</div>
</body>
</html>