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
.page-title,
.table-title{
    font-size:22px;
    font-weight:700;
    margin-bottom:25px;
    letter-spacing:1px;
}

/* ================= FORM GRID ================= */
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

/* Inputs */
.form-group input,
.form-group select{
    padding:12px;
    border-radius:8px;
    border:1px solid rgba(212,175,55,0.4);
    background:#102a3a;
    color:#ffffff;
    font-size:14px;
    transition:0.3s ease;
}

.form-group input:focus,
.form-group select:focus{
    outline:none;
    box-shadow:0 0 12px rgba(212,175,55,0.7);
}

/* ================= BUTTON ================= */
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
    transition:0.3s ease;
    letter-spacing:1px;
}

.submit-btn:hover{
    background:#c39c2d;
    box-shadow:0 0 20px rgba(212,175,55,0.7);
    transform:translateY(-3px);
}

/* ================= ALERTS ================= */
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

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:12px;
}

th{
    background:#122944;
    padding:15px;
    font-weight:600;
}

td{
    padding:15px;
    border-bottom:1px solid rgba(255,255,255,0.05);
    transition:0.3s ease;
}

/* Row Hover Glow */
tr:hover{
    background:rgba(212,175,55,0.08);
    box-shadow:inset 0 0 15px rgba(212,175,55,0.2);
}

/* ================= STATUS COLORS ================= */
.status-Serviceable { color:#6dd3ce; font-weight:600; }
.status-Non-Serviceable { color:#ffa726; font-weight:600; }
.status-Under-Maintenance { color:#ffd54f; font-weight:600; }
.status-Condemned { color:#ff8fa3; font-weight:600; }
.status-Pending-Approval { color:#64b5f6; font-weight:600; }

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