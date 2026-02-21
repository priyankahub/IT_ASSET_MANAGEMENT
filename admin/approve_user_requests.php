<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'ADMIN') {
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

        // 🔥 Safety check
        if (empty($req['army_no'])) {
            die("Army Number cannot be empty. Please update before approving.");
        }

        mysqli_query($conn,"
            INSERT INTO users (name, username, password, army_no, rank)
            VALUES (
                '{$req['full_name']}',
                '{$req['username']}',
                '{$req['password']}',
                '{$req['army_no']}',
                '{$req['rank']}'
            )
        ");
    }
    if ($req['request_type'] == 'EDIT') {
        mysqli_query($conn,"
            UPDATE users
            SET name = '{$req['full_name']}',
                army_no = '{$req['army_no']}',
                rank = '{$req['rank']}'
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
    $rank = $_POST['rank'];
    $army_no = $_POST['army_no'];

    mysqli_query($conn,"
        UPDATE user_requests
        SET full_name='$full_name',
            rank='$rank',
            army_no='$army_no'
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

/* ================= BODY ================= */
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left,#0f2027,#203a43 60%,#0a1923);
    color:#fff;
}

/* Tech Grid */
body::before{
    content:"";
    position:fixed;
    width:100%;
    height:100%;
    background-image:
        linear-gradient(rgba(0,198,255,0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,198,255,0.05) 1px, transparent 1px);
    background-size:40px 40px;
    pointer-events:none;
}

/* ================= CONTAINER ================= */
.container{
    width:95%;
    max-width:1200px;
    margin:60px auto;
}

/* ================= CARD ================= */
.card{
    background:rgba(10,25,40,0.9);
    padding:30px;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
    border:1px solid rgba(0,198,255,0.3);
    backdrop-filter:blur(8px);
    transition:0.3s;
}

.card:hover{
    box-shadow:0 20px 50px rgba(0,0,0,0.8);
}

/* ================= TITLE ================= */
h2{
    color:#ffffff;
    letter-spacing:1px;
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:25px;
    overflow:hidden;
    border-radius:12px;
}

th,td{
    padding:12px;
    text-align:center;
}

th{
    background:#102a3a;
    color:#00c6ff;
    font-weight:600;
    border-bottom:1px solid rgba(0,198,255,0.3);
}

tr{
    transition:0.3s ease;
}

tr:hover{
    background:rgba(0,198,255,0.08);
    box-shadow:inset 0 0 15px rgba(0,198,255,0.2);
}

/* ================= STATUS ================= */
.status-pending{
    color:#ffb74d;
    font-weight:bold;
}

.status-approved{
    color:#00e676;
    font-weight:bold;
}

.status-rejected{
    color:#ff5252;
    font-weight:bold;
}

/* ================= BUTTONS ================= */
button{
    padding:6px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
    transition:0.3s ease;
}

.approve{
    background:#00c853;
    color:#001f54;
}

.approve:hover{
    box-shadow:0 0 12px rgba(0,200,83,0.8);
    transform:translateY(-2px);
}

.reject{
    background:#d32f2f;
    color:white;
}

.reject:hover{
    box-shadow:0 0 12px rgba(211,47,47,0.8);
    transform:translateY(-2px);
}

.update{
    background:#ffb300;
    color:#001f54;
}

.update:hover{
    box-shadow:0 0 12px rgba(255,179,0,0.8);
}

.save{
    background:#00c6ff;
    color:#001f54;
}

.save:hover{
    box-shadow:0 0 12px rgba(0,198,255,0.8);
}

/* ================= INPUTS ================= */
input,select{
    padding:6px;
    border-radius:6px;
    border:1px solid rgba(0,198,255,0.4);
    background:#102a3a;
    color:white;
}

input:focus,select:focus{
    outline:none;
    box-shadow:0 0 8px rgba(0,198,255,0.7);
}

/* ================= SUCCESS ================= */
.success{
    background:#102f44;
    padding:12px;
    margin-top:15px;
    border-left:4px solid #00c6ff;
    border-radius:6px;
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
    transition:0.3s;
}

.footer a:hover{
    background:#0099cc;
    box-shadow:0 5px 20px rgba(0,198,255,0.8);
}

.actions form{
    display:inline;
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
        echo "<td><input type='text' name='army_no' value='{$r['army_no']}' required></td>";

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
        echo "<td>{$r['army_no']}</td>";
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
