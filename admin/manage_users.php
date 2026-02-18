<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADJQM') {
    die("Access Denied");
}

/* ===== DELETE USER ===== */
if (isset($_POST['delete'])) {
    $username = $_POST['username'];

    mysqli_query($conn, "
        DELETE FROM users 
        WHERE username='$username'
    ");

    header("Location: manage_users.php");
    exit;
}

$users = mysqli_query($conn, "
    SELECT name, username, role, id_number 
    FROM users
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>

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
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,.2);
}
h2{
    color:#2a5298;
    margin-bottom:20px;
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    padding:14px;
    text-align:center;
}
th{
    background:#f4f6f9;
    font-weight:bold;
    border-bottom:2px solid #ddd;
}
tr{
    border-bottom:1px solid #eee;
}
tr:hover{
    background:#f9fbff;
}
.role-badge{
    padding:5px 12px;
    border-radius:20px;
    color:white;
    font-size:12px;
    font-weight:bold;
}
.ADMIN, .ADJQM{background:#6f42c1;}
.CO{background:#17a2b8;}
.ITJCO{background:#fd7e14;}
.CLERK{background:#007bff;}
.USER{background:#28a745;}
button{
    padding:7px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:13px;
}
.delete-btn{
    background:#dc3545;
    color:white;
}
.delete-btn:hover{
    background:#b02a37;
}
.top-actions{
    margin-bottom:20px;
    display:flex;
    justify-content:space-between;
}
.top-actions a{
    text-decoration:none;
    padding:8px 14px;
    border-radius:6px;
    background:#2a5298;
    color:white;
    font-size:14px;
}
.top-actions a:hover{
    background:#1e3c72;
}
.footer{
    margin-top:25px;
    text-align:center;
}
.footer a{
    text-decoration:none;
    padding:8px 16px;
    border-radius:20px;
    background:rgba(255,255,255,0.3);
    color:white;
    margin:8px;
    font-weight:bold;
}
.footer a:hover{
    background:rgba(255,255,255,0.5);
}
</style>
</head>

<body>

<div class="container">

<div class="card">

<h2>Manage Users (Admin Panel)</h2>

<div class="top-actions">
    <a href="create_user.php">+ Create New User</a>
    <a href="export_users.php">⬇ Download Excel</a>
</div>

<table>
<tr>
    <th>Full Name</th>
    <th>Username</th>
    <th>Role</th>
    <th>ID Number</th>
    <th>Action</th>
</tr>

<?php
while($u = mysqli_fetch_assoc($users)){

    $roleClass = $u['role'];

    echo "<tr>
        <td>{$u['name']}</td>
        <td>{$u['username']}</td>
        <td><span class='role-badge $roleClass'>{$u['role']}</span></td>
        <td>{$u['id_number']}</td>
        <td>
            <form method='POST' 
                  onsubmit=\"return confirm('Delete this user?')\">
                <input type='hidden' name='username' value='{$u['username']}'>
                <button class='delete-btn' name='delete'>Delete</button>
            </form>
        </td>
    </tr>";
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
