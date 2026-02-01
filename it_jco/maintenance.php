<?php
session_start();
include("../config/db.php");

/* ===== Role Check ===== */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ITJCO') {
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
h2,h3{
    color:#2a5298;
}
form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}
form textarea{
    grid-column:span 2;
}
input,select,textarea,button{
    padding:10px;
    border-radius:5px;
    border:1px solid #ccc;
}
button{
    grid-column:span 2;
    background:#2a5298;
    color:#fff;
    border:none;
    cursor:pointer;
}
button:hover{
    background:#1e3c72;
}
.success{
    color:green;
    font-weight:bold;
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:10px;
    border-bottom:1px solid #ddd;
}
th{
    background:#f4f6f9;
}
.back{
    text-align:center;
}
.back a{
    color:#fff;
    text-decoration:none;
}
</style>
</head>

<body>

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
