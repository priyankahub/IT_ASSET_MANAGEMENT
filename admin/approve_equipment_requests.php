<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN') {
    die("Access Denied");
}

$admin_username = $_SESSION['username'];

// Handle Approve / Reject
if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == "approve") {

        // Get requested status
        $req = mysqli_query($conn, "SELECT * FROM equipment_status_requests WHERE equipment_id=$id AND status='Pending'");
        $request = mysqli_fetch_assoc($req);

        if ($request) {

            $new_status = $request['requested_status'];

            // Update equipment status
            mysqli_query($conn, "UPDATE equipment 
                                 SET status='$new_status' 
                                 WHERE id=$id");

            // Update request table
            mysqli_query($conn, "UPDATE equipment_status_requests 
                                 SET status='Approved',
                                     approved_by='$admin_username',
                                     approval_date=CURDATE()
                                 WHERE id=".$request['id']);

        }

    } elseif ($action == "reject") {

        mysqli_query($conn, "UPDATE equipment_status_requests 
                             SET status='Rejected',
                                 approved_by='$admin_username',
                                 approval_date=CURDATE()
                             WHERE equipment_id=$id AND status='Pending'");
    }

    header("Location: approve_equipment_requests.php");
    exit;
}

// Fetch all pending approval equipment
$query = "
SELECT e.*, r.requested_status, r.id AS request_id, r.requested_by
FROM equipment e
JOIN equipment_status_requests r ON e.id = r.equipment_id
WHERE e.status='Pending Approval' AND r.status='Pending'
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Approve Equipment Requests</title>
<style>
body{
    background:#0f2027;
    font-family:Segoe UI;
    color:#fff;
    padding:40px;
}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
}
th, td{
    padding:12px;
    border:1px solid #00c6ff;
    text-align:center;
}
th{
    background:#102a3a;
}
button{
    padding:6px 15px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}
.approve{
    background:#00c6ff;
    color:#001f54;
}
.reject{
    background:#ff4d4d;
    color:white;
}
a.back{
    color:#00c6ff;
    text-decoration:none;
}
</style>
</head>
<body>

<h2>Pending Equipment Approval</h2>

<table>
<tr>
    <th>ID</th>
    <th>Type</th>
    <th>Make</th>
    <th>Model</th>
    <th>Serial No</th>
    <th>Requested Status</th>
    <th>Requested By</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['type']; ?></td>
    <td><?php echo $row['make']; ?></td>
    <td><?php echo $row['model']; ?></td>
    <td><?php echo $row['serial_no']; ?></td>
    <td><?php echo $row['requested_status']; ?></td>
    <td><?php echo $row['requested_by']; ?></td>
    <td>
        <a href="approve_equipment_requests.php?action=approve&id=<?php echo $row['id']; ?>">
            <button class="approve">Approve</button>
        </a>
        <a href="approve_equipment_requests.php?action=reject&id=<?php echo $row['id']; ?>">
            <button class="reject">Reject</button>
        </a>
    </td>
</tr>
<?php } ?>

</table>

<br><br>
<a href="../dashboard.php" class="back">⬅ Back to Dashboard</a>

</body>
</html>