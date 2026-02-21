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
    max-width:1000px;
    margin:50px auto;
}

/* CARD */
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    border-left:4px solid #d4af37;
    margin-bottom:30px;
    transition:all 0.3s ease;
    position:relative;
}

/* Hover Highlight Effect */
.card:hover{
    transform:translateY(-8px);
    background:#1d3a5c;
    box-shadow:
        0 0 20px rgba(212,175,55,0.5),
        0 15px 35px rgba(0,0,0,0.7);
}

/* TITLES */
h2{
    color:#ffffff;
    margin-bottom:20px;
    font-weight:600;
}

/* LABELS */
label{
    font-size:13px;
    color:#b8c6db;
    font-weight:600;
}

/* INPUTS */
select,
input{
    width:100%;
    padding:10px;
    margin-top:6px;
    margin-bottom:15px;
    border-radius:4px;
    border:1px solid #2c4c73;
    background:#0f223a;
    color:#ffffff;
}

select:focus,
input:focus{
    border-color:#d4af37;
    outline:none;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    background:#d4af37;
    color:#0f223a;
    border:none;
    border-radius:4px;
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
    margin-top:20px;
}

th{
    background:#122944;
    padding:12px;
    text-align:left;
    font-weight:600;
    color:#ffffff;
}

td{
    padding:12px;
    border-bottom:1px solid #2c4c73;
}

tr:hover{
    background:#1a355a;
}

/* DIVIDER */
.divider{
    height:1px;
    background:#2c4c73;
    margin:30px 0;
}

/* BACK BUTTON */
.back{
    text-align:center;
    margin-top:30px;
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
