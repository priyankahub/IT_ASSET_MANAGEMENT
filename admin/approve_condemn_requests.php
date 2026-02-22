<?php
session_start();
include("../config/db.php");

if ($_SESSION['rank'] != 'ADMIN') die("Access Denied");

if(isset($_POST['approve'])){
$id=$_POST['id'];

$req=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM condemnation_requests WHERE id='$id'
"));

mysqli_query($conn,"
UPDATE equipment SET status='Condemned'
WHERE id='{$req['equipment_id']}'
");

mysqli_query($conn,"
INSERT INTO disposal(equipment_id, disposal_date, reason)
VALUES('{$req['equipment_id']}',CURDATE(),
'Condemned by {$_SESSION['username']} on request of {$req['requested_by']}')
");

mysqli_query($conn,"
UPDATE condemnation_requests
SET status='Approved',
approved_by='{$_SESSION['username']}',
approval_date=CURDATE()
WHERE id='$id'
");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Approve Condemn Requests</title>

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
}

/* TITLE */
h2{
    margin-bottom:25px;
    font-weight:600;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#122944;
    padding:14px;
    text-align:center;
    font-weight:600;
}

td{
    padding:14px;
    text-align:center;
    border-bottom:1px solid #2c4c73;
}

tr:hover{
    background:#1a355a;
    transition:0.3s;
}

/* APPROVE BUTTON */
.approve-btn{
    background:#28a745;
    color:#fff;
    padding:8px 18px;
    border:none;
    border-radius:4px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s ease;
}

.approve-btn:hover{
    background:#1e7e34;
    transform:scale(1.08);
    box-shadow:0 0 12px rgba(40,167,69,0.7);
}

/* NO REQUEST MESSAGE */
.no-data{
    text-align:center;
    padding:30px;
    font-size:16px;
    color:#b8c6db;
}

/* FOOTER */
.footer{
    margin-top:40px;
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

.footer a:hover{
    background:#c39c2d;
}
</style>

<script>
function confirmApprove(){
    return confirm("⚠ CONFIRM APPROVAL\n\nYou are about to APPROVE this condemnation request.\n\nThis will permanently mark the equipment as CONDEMNED.\n\nAre you sure you want to proceed?");
}
</script>

</head>

<body>

<div class="header">
INF BN – CONDEMNATION APPROVAL CONTROL
</div>

<div class="container">
<div class="card">

<h2>Approve Condemn Requests</h2>

<table>

<tr>
<th>Equipment Details</th>
<th>Action</th>
</tr>

<?php
$res=mysqli_query($conn,"
SELECT c.id,e.type,e.make
FROM condemnation_requests c
JOIN equipment e ON c.equipment_id=e.id
WHERE c.status='Pending'
");

if(mysqli_num_rows($res)==0){
echo "<tr><td colspan='2' class='no-data'>No Pending Condemnation Requests</td></tr>";
}

while($r=mysqli_fetch_assoc($res)){
echo "<tr>
<td>{$r['type']} - {$r['make']}</td>
<td>
<form method='POST' onsubmit='return confirmApprove();'>
<input type='hidden' name='id' value='{$r['id']}'>
<button name='approve' class='approve-btn'>Approve</button>
</form>
</td>
</tr>";
}
?>

</table>

</div>

<div class="footer">
<a href='../dashboard.php'>⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>