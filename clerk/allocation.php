<?php
session_start();
include("../config/db.php");

/* ===== Rank Check ===== */
if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'CLERK') {
    die("Access Denied");
}

/* ===== ISSUE EQUIPMENT ===== */
if (isset($_POST['issue'])) {
    $equipment_id = $_POST['equipment_id'];
    $allotted_to  = $_POST['allotted_to'];
    $issue_date   = date('Y-m-d');

    // Ensure equipment is not already issued
    $check = mysqli_query($conn,"
        SELECT * FROM allocation 
        WHERE equipment_id='$equipment_id' AND status='Issued'
    ");

    if (mysqli_num_rows($check) == 0) {

        mysqli_query($conn,"
            INSERT INTO allocation 
            (equipment_id, allotted_to, issue_date, status)
            VALUES 
            ('$equipment_id','$allotted_to','$issue_date','Issued')
        ");

        mysqli_query($conn,"
            UPDATE equipment 
            SET status='Issued' 
            WHERE id='$equipment_id'
        ");
    }
}

/* ===== RETURN EQUIPMENT ===== */
if (isset($_POST['return'])) {
    $allocation_id = $_POST['allocation_id'];
    $return_date   = date('Y-m-d');

    $res = mysqli_query($conn,"
        SELECT equipment_id FROM allocation WHERE id='$allocation_id'
    ");
    $row = mysqli_fetch_assoc($res);
    $equipment_id = $row['equipment_id'];

    mysqli_query($conn,"
        UPDATE allocation 
        SET return_date='$return_date', status='Returned'
        WHERE id='$allocation_id'
    ");

    mysqli_query($conn,"
        UPDATE equipment 
        SET status='Serviceable'
        WHERE id='$equipment_id'
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Equipment Allocation</title>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:95%;
    max-width:950px;
    margin:30px auto;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,.2);
    margin-bottom:25px;
}
h2{
    color:#2a5298;
    margin-bottom:15px;
}
label{
    font-weight:bold;
}
select,input,button{
    width:100%;
    padding:10px;
    margin-top:8px;
    border-radius:5px;
    border:1px solid #ccc;
}
button{
    background:#2a5298;
    color:#fff;
    border:none;
    cursor:pointer;
}
button:hover{
    background:#1e3c72;
}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th,td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:left;
}
th{
    background:#f4f6f9;
    font-weight:bold;
}
tr:hover{
    background:#f9fbff;
}
.back{
    text-align:center;
}
.back a{
    color:#fff;
    text-decoration:none;
    font-weight:bold;
}
.divider{
    height:1px;
    background:#ddd;
    margin:30px 0;
}
</style>
</head>

<body>

<div class="container">

<!-- ================= ISSUE SECTION ================= -->
<div class="card">
<h2>Issue Equipment</h2>

<form method="POST" onsubmit="return confirm('Issue this equipment?')">

<label>Select Equipment</label>
<select name="equipment_id" required>
<option value="">-- Select Equipment --</option>

<?php
$equipments = mysqli_query($conn,"
    SELECT * FROM equipment 
    WHERE status='Serviceable'
      AND id NOT IN (
          SELECT equipment_id FROM allocation WHERE status='Issued'
      )
");
while($e=mysqli_fetch_assoc($equipments)){
    echo "<option value='{$e['id']}'>
        {$e['type']} - {$e['make']} ({$e['serial_no']})
    </option>";
}
?>
</select>

<label>Allotted To (Username)</label>
<input type="text" name="allotted_to" required>

<br><br>
<button type="submit" name="issue">Issue Equipment</button>

</form>
</div>

<!-- ================= RETURN SECTION ================= -->
<div class="card">
<h2>Return Issued Equipment</h2>

<form method="POST" onsubmit="return confirm('Return this equipment?')">

<label>Select Issued Equipment</label>
<select name="allocation_id" required>
<option value="">-- Select Issued Equipment --</option>

<?php
$issued = mysqli_query($conn,"
    SELECT a.id, e.type, e.make, e.serial_no, a.allotted_to
    FROM allocation a
    JOIN equipment e ON a.equipment_id=e.id
    WHERE a.status='Issued'
");
while($i=mysqli_fetch_assoc($issued)){
    echo "<option value='{$i['id']}'>
        {$i['type']} - {$i['make']} ({$i['serial_no']}) → {$i['allotted_to']}
    </option>";
}
?>
</select>

<br><br>
<button type="submit" name="return">Return Equipment</button>

</form>
</div>

<!-- ================= ALLOCATION HISTORY (BOTTOM) ================= -->
<div class="card">
<h2>Allocation History</h2>

<table>
<tr>
    <th>Equipment</th>
    <th>Allocated To</th>
    <th>Issue Date</th>
    <th>Return Date</th>
    <th>Status</th>
</tr>

<?php
$history = mysqli_query($conn,"
    SELECT e.type, e.make, e.serial_no,
           a.allotted_to, a.issue_date, a.return_date, a.status
    FROM allocation a
    JOIN equipment e ON a.equipment_id=e.id
    ORDER BY a.issue_date DESC
");

while($h=mysqli_fetch_assoc($history)){
    echo "<tr>
        <td>{$h['type']} - {$h['make']} ({$h['serial_no']})</td>
        <td>{$h['allotted_to']}</td>
        <td>{$h['issue_date']}</td>
        <td>".($h['return_date'] ?? '—')."</td>
        <td>{$h['status']}</td>
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
