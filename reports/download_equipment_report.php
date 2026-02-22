<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['status'])) {
    die("Invalid Request");
}

$status = mysqli_real_escape_string($conn, $_GET['status']);

$allowed = [
    'Serviceable',
    'Under-Maintenance',
    'Non-Serviceable',
    'Condemned',
    'Pending Approval'
];

if (!in_array($status, $allowed)) {
    die("Invalid Status");
}

/* =========================
   EXPORT CSV (MUST COME FIRST)
========================= */
if (isset($_GET['export']) && $_GET['export'] == '1') {

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$status.'_equipment_report.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen("php://output", "w");

    fputcsv($output, ['Type','Make','Model','Serial No','Purchase Date','Warranty End','Cost','Status']);

    $query = mysqli_query($conn, "
        SELECT type, make, model, serial_no, purchase_date, warranty_end, cost, status
        FROM equipment
        WHERE status='$status'
    ");

    while($row = mysqli_fetch_assoc($query)){
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}

/* =========================
   NORMAL PAGE VIEW
========================= */

$query = mysqli_query($conn, "
    SELECT type, make, model, serial_no, purchase_date, warranty_end, cost, status
    FROM equipment
    WHERE status='$status'
");
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $status; ?> Equipment Report</title>
<style>
body{
    font-family:'Segoe UI',sans-serif;
    background:#0f2027;
    color:#fff;
    padding:40px;
}
table{
    width:100%;
    border-collapse:collapse;
}
th, td{
    padding:12px;
    border:1px solid #00c6ff;
    text-align:center;
}
th{
    background:#102a3a;
}
.export-btn{
    padding:10px 20px;
    background:#00c6ff;
    color:#001f54;
    border:none;
    border-radius:25px;
    font-weight:bold;
    cursor:pointer;
}
.export-btn:hover{
    background:#00f7ff;
}
.back{
    display:inline-block;
    margin-top:30px;
    padding:10px 20px;
    background:#00c6ff;
    color:#001f54;
    border-radius:25px;
    text-decoration:none;
    font-weight:bold;
}
</style>
</head>
<body>

<h2><?php echo $status; ?> Equipment List</h2>

<a href="download_equipment_report.php?status=<?php echo $status; ?>&export=1">
    <button class="export-btn">Export to CSV</button>
</a>

<br><br>

<table>
<tr>
    <th>Type</th>
    <th>Make</th>
    <th>Model</th>
    <th>Serial No</th>
    <th>Purchase Date</th>
    <th>Warranty End</th>
    <th>Cost</th>
    <th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($query)) { ?>
<tr>
    <td><?php echo $row['type']; ?></td>
    <td><?php echo $row['make']; ?></td>
    <td><?php echo $row['model']; ?></td>
    <td><?php echo $row['serial_no']; ?></td>
    <td><?php echo $row['purchase_date']; ?></td>
    <td><?php echo $row['warranty_end']; ?></td>
    <td><?php echo $row['cost']; ?></td>
    <td><?php echo $row['status']; ?></td>
</tr>
<?php } ?>

</table>

<a href="equipment_analytics_dashboard.php" class="back">⬅ Back to Analytics</a>

</body>
</html>