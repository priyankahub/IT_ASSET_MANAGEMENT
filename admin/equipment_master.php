<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || 
    !in_array($_SESSION['role'], ['ADMIN','CLERK'])) {
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

        $actual_status = "Pending Approval";

        // Insert Equipment (Always Pending)
        $insert1 = mysqli_query($conn, "
            INSERT INTO equipment
            (type, make, model, serial_no, purchase_date, warranty_end, cost, status)
            VALUES
            ('$type','$make','$model','$serial','$purchase','$warranty','$cost','$actual_status')
        ");

        if (!$insert1) {

            mysqli_rollback($conn);
            $message = "Database error while inserting equipment.";
            $msgClass = "error";

        } else {

            $equipment_id = mysqli_insert_id($conn);

            // Insert Request Entry
            $insert2 = mysqli_query($conn, "
                INSERT INTO equipment_status_requests
                (equipment_id, requested_status, requested_by, request_date)
                VALUES
                ('$equipment_id','$selected_status','{$_SESSION['username']}',CURDATE())
            ");

            if (!$insert2) {

                mysqli_rollback($conn);
                $message = "Database error while creating approval request.";
                $msgClass = "error";

            } else {

                // Activity Log
                $log_user = $_SESSION['username'];
                $tag = "Equipment Registration Request";
                $desc = "Registered $type | Serial: $serial | Requested Status: $selected_status";

                mysqli_query($conn,"
                    INSERT INTO activity_logs
                    (user_username, action_tag, description)
                    VALUES
                    ('$log_user', '$tag', '$desc')
                ");

                mysqli_commit($conn);

                $message = "Equipment request submitted for Admin approval.";
                $msgClass = "success";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Equipment Master</title>

<style>

/* KEEPING YOUR EXACT ORIGINAL CSS UNCHANGED */

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left,#0f2027,#203a43 60%,#0a1923);
    color:#f5f7fa;
}

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
}

.container{
    width:95%;
    max-width:1300px;
    margin:60px auto;
}

.card{
    background:rgba(10,25,40,0.92);
    padding:35px;
    border-radius:18px;
    margin-bottom:50px;
    border-left:4px solid #d4af37;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
}

.page-title,
.table-title{
    font-size:22px;
    font-weight:700;
    margin-bottom:25px;
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:30px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group label{
    font-size:13px;
    margin-bottom:8px;
    color:#b8c6db;
    font-weight:600;
}

.form-group input,
.form-group select{
    padding:12px;
    border-radius:8px;
    border:1px solid rgba(212,175,55,0.4);
    background:#102a3a;
    color:#ffffff;
    font-size:14px;
}

.submit-btn{
    margin-top:30px;
    padding:14px 25px;
    width:250px;
    border:none;
    border-radius:10px;
    background:#d4af37;
    color:#0f223a;
    font-weight:700;
    cursor:pointer;
}

.success{
    padding:14px;
    border-radius:8px;
    background:#1b5e20;
    color:#a5d6a7;
    margin-bottom:25px;
    border-left:4px solid #4caf50;
}

.error{
    padding:14px;
    border-radius:8px;
    background:#7f1d1d;
    color:#ffb3b3;
    margin-bottom:25px;
    border-left:4px solid #ff5252;
}

table{
    width:100%;
    border-collapse:collapse;
    border-radius:12px;
}

th{
    background:#122944;
    padding:15px;
}

td{
    padding:15px;
}

.status-Serviceable { color:#6dd3ce; font-weight:600; }
.status-Non-Serviceable { color:#ffa726; font-weight:600; }
.status-Under-Maintenance { color:#ffd54f; font-weight:600; }
.status-Condemned { color:#ff8fa3; font-weight:600; }
.status-Pending-Approval { color:#64b5f6; font-weight:600; }
.status-Request-Rejected { color:#ff5252; font-weight:600; }

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
}

</style>
</head>

<body>

<div class="ribbon">
    <h1>
        <img src="../images/logo.jpg">
        INF BN – EQUIPMENT REGISTRATION CONTROL
        <img src="../images/logo.jpg">
    </h1>
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
<input type="text" name="type" required>
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
<input type="date" name="purchase_date" max="<?php echo date('Y-m-d'); ?>" required>
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

$class = "status-" . str_replace(" ","-",str_replace("/","-",$r['status']));

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