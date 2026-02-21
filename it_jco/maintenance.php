<?php
session_start();
include("../config/db.php");

/* ===== Rank Check ===== */
if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'ITJCO') {
    die("Access Denied");
}

$message = "";

/* ================= ADD MAINTENANCE RECORD ================= */
if (isset($_POST['add'])) {

    $equipment_id = $_POST['equipment_id'];
    $maintenance_type = $_POST['maintenance_type'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];
    $date = date('Y-m-d');

    mysqli_query($conn, "
        INSERT INTO maintenance
        (equipment_id, maintenance_type, start_date, remarks, status)
        VALUES
        ('$equipment_id','$maintenance_type','$date','$remarks','$status')
    ");

    $message = "Maintenance record added successfully";
}

/* ================= SCHEDULE MAINTENANCE (STEP C) ================= */
if (isset($_POST['schedule'])) {

    $equipment_id = $_POST['equipment_id'];
    $scheduled_date = $_POST['scheduled_date'];
    $schedule_type = $_POST['schedule_type'];
    $remarks = $_POST['remarks'];

    mysqli_query($conn, "
        INSERT INTO maintenance_schedule
        (equipment_id, scheduled_date, schedule_type, remarks)
        VALUES
        ('$equipment_id','$scheduled_date','$schedule_type','$remarks')
    ");

    $message = "Maintenance scheduled successfully";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Maintenance Management</title>

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
    margin-bottom:40px;
    transition:all 0.35s ease;
    position:relative;
    overflow:hidden;
}

/* Lift + Glow */
.card:hover{
    transform:translateY(-10px) scale(1.01);
    background:#1d3a5c;
    box-shadow:
        0 0 25px rgba(212,175,55,0.6),
        0 20px 40px rgba(0,0,0,0.75);
}

/* Light sweep animation */
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
    transition:0.6s;
}

.card:hover::before{
    left:100%;
}

/* TITLES */
h2,h3{
    margin-bottom:20px;
    font-weight:600;
}

/* FORM GRID */
form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
}

form textarea{
    grid-column:span 2;
}

/* INPUTS */
input,select,textarea{
    padding:10px;
    border-radius:4px;
    border:1px solid #2c4c73;
    background:#0f223a;
    color:#ffffff;
}

input:focus,
select:focus,
textarea:focus{
    border-color:#d4af37;
    outline:none;
}

/* BUTTON */
button{
    grid-column:span 2;
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

/* SUCCESS MESSAGE */
.success{
    background:#1e4d2b;
    color:#a8e6a1;
    padding:12px;
    border-radius:4px;
    margin-bottom:20px;
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
}

td{
    padding:12px;
    border-bottom:1px solid #2c4c73;
}

tr:hover{
    background:#1a355a;
}

/* FOOTER */
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
INF BN – MAINTENANCE MANAGEMENT CONTROL
</div>

<div class="container">

<!-- ================= ADD MAINTENANCE ================= -->
<div class="card">
<h2>Maintenance Management (IT JCO)</h2>

<?php if ($message != "") { ?>
<p class="success"><?php echo $message; ?></p>
<?php } ?>

<form method="POST">

<select name="equipment_id" required>
    <option value="">Select Equipment</option>
    <?php
    $eq = mysqli_query($conn, "SELECT id,type,make FROM equipment WHERE status!='Condemned'");
    while ($e = mysqli_fetch_assoc($eq)) {
        echo "<option value='{$e['id']}'>{$e['type']} - {$e['make']}</option>";
    }
    ?>
</select>

<select name="maintenance_type" required>
    <option value="">Maintenance Type</option>
    <option value="Preventive">Preventive</option>
    <option value="Breakdown">Breakdown</option>
</select>

<select name="status" required>
    <option value="">Status</option>
    <option value="In Progress">In Progress</option>
    <option value="Completed">Completed</option>
</select>

<input type="text" name="remarks" placeholder="Remarks / Action Taken" required>

<button type="submit" name="add">Add Maintenance Record</button>
</form>
</div>

<!-- ================= MAINTENANCE SCHEDULING ================= -->
<div class="card">
<h3>Schedule Maintenance</h3>

<form method="POST">

<select name="equipment_id" required>
    <option value="">Select Equipment</option>
    <?php
    $eq = mysqli_query($conn, "SELECT id,type,make FROM equipment WHERE status!='Condemned'");
    while ($e = mysqli_fetch_assoc($eq)) {
        echo "<option value='{$e['id']}'>{$e['type']} - {$e['make']}</option>";
    }
    ?>
</select>

<input type="date" name="scheduled_date" required>

<select name="schedule_type" required>
    <option value="">Schedule Type</option>
    <option value="Preventive">Preventive</option>
    <option value="Inspection">Inspection</option>
</select>

<input type="text" name="remarks" placeholder="Remarks">

<button type="submit" name="schedule">Schedule Maintenance</button>
</form>
</div>

<!-- ================= MAINTENANCE HISTORY ================= -->
<div class="card">
<h3>Maintenance History</h3>

<table>
<tr>
<th>Equipment</th>
<th>Type</th>
<th>Date</th>
<th>Status</th>
<th>Remarks</th>
</tr>

<?php
$hist = mysqli_query($conn, "
    SELECT m.start_date, m.maintenance_type, m.status, m.remarks,
           e.type, e.make
    FROM maintenance m
    JOIN equipment e ON m.equipment_id = e.id
    ORDER BY m.start_date DESC
");

if (mysqli_num_rows($hist) > 0) {
    while ($h = mysqli_fetch_assoc($hist)) {
        echo "<tr>
            <td>{$h['type']} ({$h['make']})</td>
            <td>{$h['maintenance_type']}</td>
            <td>{$h['start_date']}</td>
            <td>{$h['status']}</td>
            <td>{$h['remarks']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No maintenance records found</td></tr>";
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
