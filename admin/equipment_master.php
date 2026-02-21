<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || 
    !in_array($_SESSION['rank'], ['ADMIN','CLERK'])) {
    die("Access Denied");
}

$message = "";
$msgClass = "";

if (isset($_POST['add'])) {

    $type = $_POST['type'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $serial = $_POST['serial_no'];
    $purchase = $_POST['purchase_date'];
    $warranty = $_POST['warranty_end'];
    $cost = $_POST['cost'];
    $selected_status = $_POST['status'];

    $today = date('Y-m-d');

    if ($purchase > $today) {
        $message = "Purchase date cannot be in the future.";
        $msgClass = "error";
    } elseif ($warranty < $purchase) {
        $message = "Expiry date cannot be older than Purchase date.";
        $msgClass = "error";
    } else {

        mysqli_begin_transaction($conn);

        try {

            // Determine actual stored status
            $actual_status = ($selected_status == 'Serviceable') 
                             ? 'Serviceable' 
                             : 'Pending Approval';

            // Insert Equipment
            mysqli_query($conn, "
                INSERT INTO equipment
                (type, make, model, serial_no, purchase_date, warranty_end, cost, status)
                VALUES
                ('$type','$make','$model','$serial','$purchase','$warranty','$cost','$actual_status')
            ");

            $equipment_id = mysqli_insert_id($conn);

            // If status requires approval
            if ($selected_status != 'Serviceable') {

                mysqli_query($conn, "
                    INSERT INTO equipment_status_requests
                    (equipment_id, requested_status, requested_by, request_date)
                    VALUES
                    ('$equipment_id','$selected_status','{$_SESSION['username']}',CURDATE())
                ");
            }

            /* =========================
            ACTIVITY LOG ENTRY
            ========================= */

            $log_user = $_SESSION['username'];

            if($selected_status == 'Serviceable'){
                $tag = "Equipment Registration";
                $desc = "Registered $type | Serial: $serial";
            } else {
                $tag = "Equipment Status Request";
                $desc = "Registered $type | Serial: $serial | Requested Status: $selected_status";
            }

            mysqli_query($conn,"
                INSERT INTO activity_logs
                (user_username, action_tag, description)
                VALUES
                ('$log_user', '$tag', '$desc')
            ");

            mysqli_commit($conn);

            $message = "Equipment added successfully.";
            $msgClass = "success";

        } catch (Exception $e) {
            mysqli_rollback($conn);
            $message = "Error occurred. Serial number may already exist.";
            $msgClass = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Equipment Master</title>

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
    max-width:1300px;
    margin:50px auto;
}
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    margin-bottom:40px;
    border-left:4px solid #d4af37;
}
.page-title,
.table-title{
    font-size:20px;
    font-weight:600;
    margin-bottom:20px;
}
.form-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:25px;
}
.form-group{
    display:flex;
    flex-direction:column;
}
.form-group label{
    font-size:13px;
    margin-bottom:6px;
    color:#b8c6db;
}
.form-group input,
.form-group select{
    padding:10px;
    border-radius:4px;
    border:1px solid #2c4c73;
    background:#0f223a;
    color:#ffffff;
    font-size:14px;
}
.form-group input:focus,
.form-group select:focus{
    outline:none;
    border-color:#d4af37;
}
.submit-btn{
    margin-top:25px;
    padding:12px 20px;
    width:220px;
    border:none;
    border-radius:4px;
    background:#d4af37;
    color:#0f223a;
    font-weight:600;
    cursor:pointer;
}
.submit-btn:hover{
    background:#c39c2d;
}
.success{
    padding:12px;
    border-radius:4px;
    background:#1b5e20;
    color:#a5d6a7;
    margin-bottom:20px;
}
.error{
    padding:12px;
    border-radius:4px;
    background:#7f1d1d;
    color:#ffb3b3;
    margin-bottom:20px;
}
table{
    width:100%;
    border-collapse:collapse;
}
th{
    background:#122944;
    padding:12px;
}
td{
    padding:12px;
    border-bottom:1px solid #2c4c73;
}
tr:hover{
    background:#1a355a;
}

/* Status Colors */
.status-Serviceable { color:#6dd3ce; font-weight:600; }
.status-Non-Serviceable { color:#ffa726; font-weight:600; }
.status-Under-Maintenance { color:#ffd54f; font-weight:600; }
.status-Condemned { color:#ff8fa3; font-weight:600; }
.status-Pending-Approval { color:#64b5f6; font-weight:600; }

.back{
    text-align:center;
    margin-top:40px;
}
.back a{
    text-decoration:none;
    padding:12px 24px;
    background:#d4af37;
    color:#0f223a;
    border-radius:4px;
    font-weight:600;
}
</style>
</head>

<body>

<div class="header">
INF BN – EQUIPMENT REGISTRATION CONTROL
</div>

<div class="container">

<div class="card">
<div class="page-title">Equipment Registration</div>

<?php if($message!=""){ ?>
<div class="<?php echo $msgClass; ?>">
    <?php echo $message; ?>
</div>
<?php } ?>

<form method="POST">

<div class="form-grid">

<div class="form-group">
<label>Equipment Type</label>
<select name="type" required>
<?php
$types = mysqli_query($conn,"SELECT type_name FROM equipment_types WHERE is_active=1");
while($t=mysqli_fetch_assoc($types)){
echo "<option value='{$t['type_name']}'>{$t['type_name']}</option>";
}
?>
</select>
</div>

<div class="form-group">
<label>Make</label>
<input type="text" name="make" required>
</div>

<div class="form-group">
<label>Model</label>
<input type="text" name="model" required>
</div>

<div class="form-group">
<label>Serial Number</label>
<input type="text" name="serial_no" required>
</div>

<div class="form-group">
<label>Purchase Date</label>
<input type="date" name="purchase_date"
max="<?php echo date('Y-m-d'); ?>" required>
</div>

<div class="form-group">
<label>Warranty / Expiry Date</label>
<input type="date" name="warranty_end" required>
</div>

<div class="form-group">
<label>Cost</label>
<input type="number" step="0.01" name="cost" required>
</div>

<div class="form-group">
<label>Status</label>
<select name="status" required>
<option value="Serviceable">Serviceable</option>
<option value="Non-Serviceable">Non-Serviceable</option>
<option value="Under-Maintenance">Under-Maintenance</option>
<option value="Condemned">Condemned</option>
</select>
</div>

</div>

<button class="submit-btn" name="add">Add Equipment</button>

</form>
</div>

<div class="card">
<div class="table-title">Registered Equipment</div>

<table>
<tr>
<th>ID</th>
<th>Type</th>
<th>Make</th>
<th>Model</th>
<th>Purchase Date</th>
<th>Expiry Date</th>
<th>Status</th>
</tr>

<?php
$res=mysqli_query($conn,"SELECT * FROM equipment ORDER BY id DESC");
while($r=mysqli_fetch_assoc($res)){

$class = "status-" . str_replace(" ","-",$r['status']);

echo "<tr>
<td>{$r['id']}</td>
<td>{$r['type']}</td>
<td>{$r['make']}</td>
<td>{$r['model']}</td>
<td>{$r['purchase_date']}</td>
<td>{$r['warranty_end']}</td>
<td class='$class'>{$r['status']}</td>
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