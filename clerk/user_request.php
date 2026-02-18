<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'CLERK') {
    die("Access Denied");
}

$message = "";

/* ===== HANDLE CREATE / EDIT / DELETE REQUEST ===== */
if (isset($_POST['submit_request'])) {

    $request_type = $_POST['request_type'];
    $full_name    = $_POST['full_name'];
    $username     = $_POST['username'];
    $password     = $_POST['password'];
    $id_number    = $_POST['id_number'];
    $rank         = $_POST['rank'];
    $requested_by = $_SESSION['username'];
    $today        = date('Y-m-d');

    mysqli_query($conn,"
        INSERT INTO user_requests
        (request_type, full_name, username, password, id_number, rank, requested_by, request_date, status)
        VALUES
        ('$request_type','$full_name','$username','$password','$id_number','$rank','$requested_by','$today','Pending')
    ");

    $message = "✅ Request sent to Admin for approval.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Management Request</title>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}

.container{
    width:95%;
    max-width:900px;
    margin:40px auto;
}

.card{
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,.2);
    margin-bottom:25px;
}

h2{
    color:#2a5298;
    margin-bottom:20px;
}

label{
    font-weight:bold;
}

input, select{
    width:100%;
    padding:10px;
    margin-top:6px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ccc;
}

button{
    width:100%;
    padding:10px;
    background:#2a5298;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    background:#1e3c72;
}

.success{
    background:#d4edda;
    color:#155724;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
}

.footer-actions{
    text-align:center;
    margin-top:20px;
}

.footer-actions a{
    color:#fff;
    text-decoration:none;
    margin:0 10px;
    padding:8px 15px;
    background:rgba(255,255,255,0.2);
    border-radius:20px;
}

.footer-actions a:hover{
    background:rgba(255,255,255,0.35);
}
</style>
</head>

<body>

<div class="container">

<div class="card">

<h2>User Management Request (Clerk)</h2>

<?php if($message!=""){ ?>
<div class="success"><?php echo $message; ?></div>
<?php } ?>

<form method="POST">

<label>Request Type</label>
<select name="request_type" required>
    <option value="CREATE">Create User</option>
    <option value="EDIT">Edit User</option>
    <option value="DELETE">Delete User</option>
</select>

<label>Full Name</label>
<input type="text" name="full_name" required>

<label>Username</label>
<input type="text" name="username" required>

<label>Password</label>
<input type="text" name="password">

<label>ID Number</label>
<input type="text" name="id_number">

<label>Rank</label>
<select name="rank" required>
    <option value="ADMIN">ADMIN</option>
    <option value="CO">CO</option>
    <option value="ITJCO">ITJCO</option>
    <option value="CLERK">CLERK</option>
    <option value="USER">USER</option>
</select>

<button type="submit" name="submit_request">
Submit Request
</button>

</form>

</div>

<div class="footer-actions">
<a href="../dashboard.php">⬅ Back to Dashboard</a>
<a href="../logout.php">Logout</a>
</div>

</div>

</body>
</html>
