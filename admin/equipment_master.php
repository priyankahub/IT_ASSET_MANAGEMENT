<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADJQM') {
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
        $message = "Cannot add equipment: Purchase date is from future";
        $msgClass = "error";
    } elseif ($warranty < $purchase) {
        $message = "Expiry date cannot be older than Purchase date";
        $msgClass = "error";
    } else {
        mysqli_query($conn, "
            INSERT INTO equipment
            (type, make, model, serial_no, purchase_date, warranty_end, cost, status)
            VALUES
            ('$type','$make','$model','$serial','$purchase','$warranty','$cost','Serviceable')
        ");
        $message = "Equipment added successfully";
        $msgClass = "success";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Equipment Master</title>
<script src="../assets/js/script.js"></script>

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
    margin-bottom:15px;
}
.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}
input,button{
    padding:10px;
    border-radius:5px;
    border:1px solid #ccc;
}
button{
    background:#2a5298;
    color:#fff;
    border:none;
    cursor:pointer;
}
button:hover{background:#1e3c72;}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:10px;
    border-bottom:1px solid #ddd;
    text-align:left;
}
th{background:#f4f6f9;}
.success{color:green;font-weight:bold;}
.error{color:red;font-weight:bold;}
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

<div class="card">
<h2>Equipment Master – Admin</h2>

<?php if($message!=""){ ?>
<p class="<?php echo $msgClass; ?>"><?php echo $message; ?></p>
<?php } ?>

<form method="POST" onsubmit="return confirmAction('Add this equipment?')">
<div class="form-grid">
    <input type="text" name="type" placeholder="Equipment Type" required>
    <input type="text" name="make" placeholder="Make" required>
    <input type="text" name="model" placeholder="Model" required>
    <input type="text" name="serial_no" placeholder="Serial Number" required>

    <input type="date" name="purchase_date"
           max="<?php echo date('Y-m-d'); ?>" required>

    <input type="date" name="warranty_end" required>

    <input type="number" step="0.00001" name="cost"
           placeholder="Cost" required>
</div>
<br>
<button name="add">Add Equipment</button>
</form>
</div>

<div class="card">
<h3>Registered Equipment</h3>

<table>
<tr>
<th>ID</th><th>Type</th><th>Make</th><th>Model</th>
<th>Purchase</th><th>Expiry</th><th>Status</th>
</tr>

<?php
$res=mysqli_query($conn,"SELECT * FROM equipment");
while($r=mysqli_fetch_assoc($res)){
echo "<tr>
<td>{$r['id']}</td>
<td>{$r['type']}</td>
<td>{$r['make']}</td>
<td>{$r['model']}</td>
<td>{$r['purchase_date']}</td>
<td>{$r['warranty_end']}</td>
<td>{$r['status']}</td>
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
