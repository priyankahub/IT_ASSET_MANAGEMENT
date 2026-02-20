<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'ADMIN') {
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

    $today = date('Y-m-d');

    if ($purchase > $today) {
        $message = "Purchase date cannot be in the future.";
        $msgClass = "error";
    } elseif ($warranty < $purchase) {
        $message = "Expiry date cannot be older than Purchase date.";
        $msgClass = "error";
    } else {
        mysqli_query($conn, "
            INSERT INTO equipment
            (type, make, model, serial_no, purchase_date, warranty_end, cost, status)
            VALUES
            ('$type','$make','$model','$serial','$purchase','$warranty','$cost','Serviceable')
        ");
        $message = "Equipment added successfully.";
        $msgClass = "success";
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
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg,#1e3c72,#2a5298);
}

.container{
    width:95%;
    max-width:1150px;
    margin:40px auto;
}

.card{
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,.15);
    margin-bottom:30px;
}

.page-title{
    font-size:24px;
    font-weight:600;
    color:#2a5298;
    margin-bottom:20px;
}

/* FORM GRID */
.form-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:20px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group label{
    font-size:13px;
    font-weight:600;
    margin-bottom:6px;
    color:#333;
}

.form-group input{
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:14px;
    transition:.3s;
}

.form-group input:focus{
    border-color:#2a5298;
    outline:none;
    box-shadow:0 0 4px rgba(42,82,152,.4);
}

.submit-btn{
    margin-top:20px;
    padding:12px;
    width:200px;
    border:none;
    border-radius:6px;
    background:#2a5298;
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

.submit-btn:hover{
    background:#1e3c72;
}

/* MESSAGE STYLES */
.success{
    padding:12px;
    border-radius:6px;
    background:#e8f5e9;
    color:#2e7d32;
    margin-bottom:15px;
}

.error{
    padding:12px;
    border-radius:6px;
    background:#fdecea;
    color:#c62828;
    margin-bottom:15px;
}

/* TABLE */
.table-title{
    font-size:20px;
    font-weight:600;
    color:#2a5298;
    margin-bottom:15px;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

th{
    background:#f4f6f9;
    padding:12px;
    text-align:left;
    font-weight:600;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f9fbff;
}

.status-serviceable{
    color:#2e7d32;
    font-weight:600;
}

.status-condemned{
    color:#c62828;
    font-weight:600;
}

.back{
    text-align:center;
    margin-top:20px;
}

.back a{
    text-decoration:none;
    padding:10px 18px;
    background:rgba(255,255,255,.25);
    color:white;
    border-radius:20px;
}
.back a:hover{
    background:rgba(255,255,255,.4);
}
</style>
</head>

<body>

<div class="container">

<!-- FORM CARD -->
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
<input type="date" name="purchase_date"
       max="<?php echo date('Y-m-d'); ?>" required>
</div>

<div class="form-group">
<label>Warranty / Expiry Date</label>
<input type="date" name="warranty_end" required>
</div>

<div class="form-group">
<label>Cost</label>
<input type="number" step="0.00001" name="cost" required>
</div>

</div>

<button class="submit-btn" name="add">Add Equipment</button>

</form>
</div>

<!-- TABLE CARD -->
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

$statusClass = ($r['status']=='Serviceable')
                ? "status-serviceable"
                : "status-condemned";

echo "<tr>
<td>{$r['id']}</td>
<td>{$r['type']}</td>
<td>{$r['make']}</td>
<td>{$r['model']}</td>
<td>{$r['purchase_date']}</td>
<td>{$r['warranty_end']}</td>
<td class='$statusClass'>{$r['status']}</td>
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
