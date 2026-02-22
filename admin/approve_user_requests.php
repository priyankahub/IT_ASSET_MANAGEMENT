<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN') {
    die("Access Denied");
}
if (isset($_GET['created'])) {
    echo "<div class='success'>✅ User request created successfully. Please approve or modify below.</div>";
}
$admin = $_SESSION['username'];

/* ================= APPROVE ================= */
if (isset($_POST['approve'])) {

    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $req = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT * FROM user_requests WHERE id='$id'
    "));

    if (!$req) {
        die("Invalid request ID.");
    }

    if ($req['request_type'] == 'CREATE') {

        if (empty($req['army_no'])) {
            die("Army Number cannot be empty. Please update before approving.");
        }

        mysqli_query($conn,"
            INSERT INTO users (name, username, password, army_no, role)
            VALUES (
                '{$req['full_name']}',
                '{$req['username']}',
                '{$req['password']}',
                '{$req['army_no']}',
                '{$req['role']}'
            )
        ");
    }
    if ($req['request_type'] == 'EDIT') {
        mysqli_query($conn,"
            UPDATE users
            SET name = '{$req['full_name']}',
                army_no = '{$req['army_no']}',
                role = '{$req['role']}'
            WHERE username = '{$req['username']}'
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
            approved_by='{$_SESSION['username']}',
            approval_date=CURDATE()
        WHERE id='$id'
    ");

    header("Location: approve_user_requests.php?msg=approved");
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
    $role = $_POST['role'];
    $army_no = $_POST['army_no'];

    mysqli_query($conn,"
        UPDATE user_requests
        SET full_name='$full_name',
            role='$role',
            army_no='$army_no'
        WHERE id='$id'
    ");

    header("Location: approve_user_requests.php?msg=updated");
    exit;
}

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

/* ================= RIBBON ================= */
.ribbon{
    width:100%;
    background:linear-gradient(90deg,#001f3f,#003f5c,#001f3f);
    padding:15px 0;
    text-align:center;
    font-size:22px;
    font-weight:bold;
    letter-spacing:2px;
    color:#ffffff;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:20px;
    box-shadow:0 4px 15px rgba(0,0,0,0.6);
}

.ribbon img{
    height:45px;
}

/* ================= BODY ================= */
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left,#0f2027,#203a43 60%,#0a1923);
    color:#fff;
}

/* ================= CONTAINER ================= */
.container{
    width:95%;
    max-width:1400px;
    margin:40px auto;
}

/* ================= CARD ================= */
.card{
    background:rgba(10,25,40,0.9);
    padding:30px;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
    border:1px solid rgba(0,198,255,0.3);
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:25px;
}

th,td{
    padding:12px;
    text-align:center;
    white-space:nowrap;
}

th{
    background:#102a3a;
    color:#00c6ff;
    font-weight:600;
}

tr:hover{
    background:rgba(0,198,255,0.08);
}

/* ================= STATUS ================= */
.status-pending{ color:#ffb74d; font-weight:bold; }
.status-approved{ color:#00e676; font-weight:bold; }
.status-rejected{ color:#ff5252; font-weight:bold; }

/* ================= BUTTONS ================= */
button{
    padding:6px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

.approve{ background:#00c853; color:#001f54; }
.reject{ background:#d32f2f; color:white; }
.update{ background:#ffb300; color:#001f54; }

.actions{
    display:flex;
    gap:6px;
    justify-content:center;
    align-items:center;
}

input{
    padding:6px;
    border-radius:6px;
    border:1px solid rgba(0,198,255,0.4);
    background:#102a3a;
    color:white;
}

/* ================= FOOTER ================= */
.footer{
    margin-top:35px;
    text-align:center;
}

.footer a{
    text-decoration:none;
    padding:10px 20px;
    border-radius:25px;
    background:#00c6ff;
    color:#001f54;
    margin:5px;
    font-weight:bold;
}

</style>
</head>

<body>

<!-- ================= TOP RIBBON ================= -->
<div class="ribbon">
    <img src="../images/logo.jpg">
    IT EQUIPMENT LIFECYCLE MANAGEMENT
    <img src="../images/logo.jpg">
</div>

<div class="container">
<div class="card">

<h2>User Requests Approval (Admin)</h2>

<table>
<tr>
    <th>ID</th>
    <th>Type</th>
    <th>Full Name</th>
    <th>Username</th>
    <th>ID No</th>
    <th>Role</th>
    <th>Requested By</th>
    <th>Status</th>
    <th>Remark</th>
    <th>Actions</th>
</tr>

<?php
while($r = mysqli_fetch_assoc($requests)){

echo "<tr>";
echo "<td>{$r['id']}</td>";
echo "<td>{$r['request_type']}</td>";
echo "<td>{$r['full_name']}</td>";
echo "<td>{$r['username']}</td>";
echo "<td>{$r['army_no']}</td>";
echo "<td>{$r['role']}</td>";
echo "<td>{$r['requested_by']}</td>";

$status_class = strtolower($r['status']);
echo "<td class='status-$status_class'>{$r['status']}</td>";

echo "<td>".($r['remarks'] ?? '-')."</td>";

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