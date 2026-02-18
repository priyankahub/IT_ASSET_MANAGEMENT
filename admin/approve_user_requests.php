<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADJQM') {
    die("Access Denied");
}

$admin = $_SESSION['username'];

/* ================= APPROVE ================= */
if (isset($_POST['approve'])) {

    $id = $_POST['id'];

    $req = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT * FROM user_requests WHERE id='$id'
    "));

    if ($req['request_type'] == 'CREATE') {

        mysqli_query($conn,"
            INSERT INTO users (name, username, password, role)
            VALUES (
                '{$req['full_name']}',
                '{$req['username']}',
                '{$req['password']}',
                '{$req['rank']}'
            )
        ");
    }

    if ($req['request_type'] == 'DELETE') {
        mysqli_query($conn,"
            DELETE FROM users WHERE username='{$req['username']}'
        ");
    }

    mysqli_query($conn,"
        UPDATE user_requests
        SET status='Approved',
            approved_by='$admin',
            approval_date=CURDATE()
        WHERE id='$id'
    ");

    header("Location: approve_user_requests.php");
    exit;
}

/* ================= REJECT ================= */
if (isset($_POST['reject'])) {

    $id = $_POST['id'];
    $reason = $_POST['reason'];

    mysqli_query($conn,"
        UPDATE user_requests
        SET status='Rejected',
            approved_by='$admin',
            approval_date=CURDATE(),
            remarks='$reason'
        WHERE id='$id'
    ");

    header("Location: approve_user_requests.php");
    exit;
}

/* ================= SAVE UPDATE ================= */
if (isset($_POST['save_update'])) {

    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $rank = $_POST['rank'];
    $id_number = $_POST['id_number'];

    mysqli_query($conn,"
        UPDATE user_requests
        SET full_name='$full_name',
            rank='$rank',
            id_number='$id_number'
        WHERE id='$id'
    ");

    // Remove edit mode after save
    header("Location: approve_user_requests.php?msg=updated");
    exit;
}

/* ================= FETCH REQUESTS ================= */
$requests = mysqli_query($conn,"
    SELECT * FROM user_requests
    ORDER BY 
        FIELD(status,'Pending','Approved','Rejected'),
        request_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>User Requests Approval</title>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:95%;
    max-width:1200px;
    margin:40px auto;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,.2);
}
h2{
    color:#2a5298;
}
table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
th,td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}
th{
    background:#f4f6f9;
}
.status-pending{color:#ff9800;font-weight:bold;}
.status-approved{color:green;font-weight:bold;}
.status-rejected{color:red;font-weight:bold;}
button{
    padding:6px 10px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}
.approve{background:#28a745;color:white;}
.reject{background:#dc3545;color:white;}
.update{background:#ffc107;}
.save{background:#007bff;color:white;}
input,select{
    padding:6px;
}
.success{
    background:#c8e6c9;
    padding:10px;
    margin-top:10px;
    border-radius:5px;
}
.actions form{
    display:inline;
}
.footer{
    margin-top:25px;
    text-align:center;
}
.footer a{
    text-decoration:none;
    padding:8px 15px;
    border-radius:20px;
    background:rgba(255,255,255,0.3);
    color:white;
    margin:5px;
}
.footer a:hover{
    background:rgba(255,255,255,0.5);
}
</style>
</head>

<body>

<div class="container">
<div class="card">

<h2>User Requests Approval (Admin)</h2>

<?php
if(isset($_GET['msg']) && $_GET['msg']=='updated'){
    echo "<div class='success'>✏ Request Updated Successfully</div>";
}
?>

<table>
<tr>
    <th>ID</th>
    <th>Type</th>
    <th>Full Name</th>
    <th>Username</th>
    <th>ID No</th>
    <th>Rank</th>
    <th>Requested By</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php
while($r = mysqli_fetch_assoc($requests)){

    $edit_mode = (isset($_GET['edit']) && $_GET['edit']==$r['id']);

    echo "<tr>";

    echo "<td>{$r['id']}</td>";
    echo "<td>{$r['request_type']}</td>";

    if($edit_mode){

        echo "<form method='POST'>";
        echo "<input type='hidden' name='id' value='{$r['id']}'>";

        echo "<td><input type='text' name='full_name' value='{$r['full_name']}' required></td>";
        echo "<td>{$r['username']}</td>";
        echo "<td><input type='text' name='id_number' value='{$r['id_number']}' required></td>";

        echo "<td>
            <select name='rank'>
                <option ".($r['rank']=='ADMIN'?'selected':'').">ADMIN</option>
                <option ".($r['rank']=='CO'?'selected':'').">CO</option>
                <option ".($r['rank']=='ITJCO'?'selected':'').">ITJCO</option>
                <option ".($r['rank']=='CLERK'?'selected':'').">CLERK</option>
                <option ".($r['rank']=='USER'?'selected':'').">USER</option>
            </select>
        </td>";

        echo "<td>{$r['requested_by']}</td>";

        echo "<td class='status-pending'>Pending</td>";

        echo "<td>
            <button class='save' name='save_update'>Save</button>
        </td>";

        echo "</form>";

    } else {

        echo "<td>{$r['full_name']}</td>";
        echo "<td>{$r['username']}</td>";
        echo "<td>{$r['id_number']}</td>";
        echo "<td>{$r['rank']}</td>";
        echo "<td>{$r['requested_by']}</td>";

        $status_class = strtolower($r['status']);
        echo "<td class='status-$status_class'>{$r['status']}</td>";

        echo "<td class='actions'>";

        if($r['status']=='Pending'){

            echo "
            <form method='POST'>
                <input type='hidden' name='id' value='{$r['id']}'>
                <button class='approve' name='approve'>Approve</button>
            </form>

            <form method='POST'>
                <input type='hidden' name='id' value='{$r['id']}'>
                <input type='text' name='reason' placeholder='Remark' required>
                <button class='reject' name='reject'>Reject</button>
            </form>

            <a href='approve_user_requests.php?edit={$r['id']}'>
                <button class='update'>Update</button>
            </a>
            ";
        } else {
            echo "-";
        }

        echo "</td>";
    }

    echo "</tr>";
}
?>

</table>

</div>

<div class="footer">
    <a href="../dashboard.php">← Back to Dashboard</a>
    <a href="../logout.php">Logout</a>
</div>

</div>

</body>
</html>
