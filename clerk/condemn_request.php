<?php
session_start();
include("../config/db.php");

if ($_SESSION['role'] != 'CLERK') die("Access Denied");

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

/* ================= BODY ================= */
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left,#0f2027,#203a43 60%,#0a1923);
    color:#f5f7fa;
}

/* Subtle Grid Overlay */
body::before{
    content:"";
    position:fixed;
    width:100%;
    height:100%;
    background-image:
        linear-gradient(rgba(212,175,55,0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(212,175,55,0.05) 1px, transparent 1px);
    background-size:40px 40px;
    pointer-events:none;
}

/* ================= RIBBON ================= */
.ribbon{
    width:100%;
    background:#0c1f33;
    border-bottom:3px solid #d4af37;
    padding:18px 0;
    text-align:center;
    box-shadow:0 5px 25px rgba(0,0,0,0.6);
}

.ribbon h1{
    margin:0;
    font-size:22px;
    letter-spacing:2px;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:18px;
}

.ribbon img{
    height:45px;
    transition:0.3s ease;
}

.ribbon img:hover{
    transform:scale(1.1);
}

/* ================= CONTAINER ================= */
.container{
    width:95%;
    max-width:1200px;
    margin:60px auto;
}

/* ================= CARD ================= */
.card{
    background:rgba(10,25,40,0.92);
    padding:35px;
    border-radius:18px;
    border-left:4px solid #d4af37;
    margin-bottom:50px;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
    transition:0.4s ease;
    position:relative;
    overflow:hidden;
}

/* Lift + Glow */
.card:hover{
    transform:translateY(-12px) scale(1.01);
    box-shadow:
        0 0 30px rgba(212,175,55,0.6),
        0 25px 60px rgba(0,0,0,0.9);
}

/* Sweep Highlight */
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
    transition:0.7s;
}

.card:hover::before{
    left:100%;
}

/* ================= TITLES ================= */
h2{
    margin-bottom:25px;
    font-size:22px;
    letter-spacing:1px;
}

/* ================= CHECKBOX ITEMS ================= */
.checkbox-item{
    padding:12px 10px;
    border-bottom:1px solid rgba(255,255,255,0.05);
    transition:0.3s ease;
}

.checkbox-item:hover{
    background:rgba(212,175,55,0.08);
    box-shadow:inset 0 0 10px rgba(212,175,55,0.2);
    transform:translateX(5px);
}

/* ================= BUTTON ================= */
button{
    margin-top:25px;
    padding:14px;
    width:100%;
    background:#d4af37;
    color:#0f223a;
    border:none;
    border-radius:10px;
    font-weight:700;
    cursor:pointer;
    transition:0.3s ease;
    letter-spacing:1px;
}

button:hover{
    background:#c39c2d;
    box-shadow:0 0 20px rgba(212,175,55,0.7);
    transform:translateY(-3px);
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:12px;
}

th{
    background:#122944;
    padding:14px;
    color:#d4af37;
    font-weight:600;
}

td{
    padding:14px;
    border-bottom:1px solid rgba(255,255,255,0.05);
    transition:0.3s ease;
}

tr:hover{
    background:rgba(212,175,55,0.08);
    box-shadow:inset 0 0 15px rgba(212,175,55,0.2);
}

/* ================= FOOTER ================= */
.footer{
    text-align:center;
    margin-top:50px;
}

.footer a{
    text-decoration:none;
    padding:14px 30px;
    background:#d4af37;
    color:#0f223a;
    border-radius:30px;
    font-weight:bold;
    transition:0.3s ease;
}

.footer a:hover{
    background:#c39c2d;
    box-shadow:0 5px 20px rgba(212,175,55,0.7);
}

</style>
</head>

<body>

<div class="ribbon">
    <h1>
        <img src="../images/logo.jpg">
        INF BN – RAISE CONDEMNATION REQUEST
        <img src="../images/logo.jpg">
    </h1>
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