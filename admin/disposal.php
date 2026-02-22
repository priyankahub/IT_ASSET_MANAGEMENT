<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN') {
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

/* ================= BODY ================= */
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left,#0f2027,#203a43 60%,#0a1923);
    color:#f5f7fa;
}

/* Subtle Grid */
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
    max-width:1300px;
    margin:60px auto;
}

/* ================= CARD ================= */
.card{
    background:rgba(10,25,40,0.92);
    padding:35px;
    border-radius:18px;
    margin-bottom:50px;
    border-left:4px solid #d4af37;
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

/* Sweep Animation */
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
h2,h3{
    margin-bottom:20px;
    font-weight:600;
    letter-spacing:1px;
}

/* ================= FORM CONTROLS ================= */
select{
    padding:10px;
    border-radius:6px;
    border:1px solid #2c4c73;
    background:#0f223a;
    color:#ffffff;
    margin-right:10px;
}

button{
    padding:12px 20px;
    border:none;
    border-radius:8px;
    background:#d4af37;
    color:#0f223a;
    font-weight:700;
    cursor:pointer;
    transition:.3s ease;
}

button:hover{
    background:#c39c2d;
    box-shadow:0 0 15px rgba(212,175,55,0.7);
    transform:translateY(-3px);
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    border-radius:12px;
    overflow:hidden;
}

th{
    background:#122944;
    padding:14px;
    text-align:left;
    font-weight:600;
}

td{
    padding:14px;
    border-bottom:1px solid rgba(255,255,255,0.05);
    transition:0.3s ease;
}

/* Row Highlight */
tr:hover{
    background:rgba(212,175,55,0.08);
    box-shadow:inset 0 0 15px rgba(212,175,55,0.2);
}

input[type="checkbox"]{
    transform:scale(1.2);
    cursor:pointer;
}

/* ================= BACK BUTTON ================= */
.back{
    text-align:center;
    margin-top:50px;
}

.back a{
    text-decoration:none;
    padding:14px 30px;
    background:#d4af37;
    color:#0f223a;
    border-radius:30px;
    font-weight:bold;
    transition:0.3s ease;
}

.back a:hover{
    background:#c39c2d;
    box-shadow:0 5px 20px rgba(212,175,55,0.7);
}

</style>
</head>

<body>

<div class="ribbon">
    <h1>
        <img src="../images/logo.jpg">
        INF BN – CONDEMNATION & DISPOSAL CONTROL
        <img src="../images/logo.jpg">
    </h1>
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