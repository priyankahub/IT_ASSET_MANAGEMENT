<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADJQM') {
    die("Access Denied");
}

$message = "";

if (isset($_POST['create'])) {

    $name       = $_POST['name'];
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $role       = $_POST['role'];
    $id_number  = $_POST['id_number'];

    // Check duplicate username
    $check = mysqli_query($conn,"SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($check) > 0){
        $message = "❌ Username already exists";
    } else {

        mysqli_query($conn,"
            INSERT INTO users
            (name, username, password, role, id_number)
            VALUES
            ('$name','$username','$password','$role','$id_number')
        ");

        $message = "✅ User Created Successfully";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create User</title>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:95%;
    max-width:600px;
    margin:50px auto;
}
.card{
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,.2);
}
h2{
    color:#2a5298;
    margin-bottom:20px;
    text-align:center;
}
label{
    font-weight:bold;
    display:block;
    margin-top:15px;
}
input, select{
    width:100%;
    padding:10px;
    margin-top:6px;
    border-radius:6px;
    border:1px solid #ccc;
}
button{
    margin-top:20px;
    width:100%;
    padding:12px;
    border:none;
    border-radius:6px;
    background:#2a5298;
    color:white;
    font-size:15px;
    cursor:pointer;
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
    text-align:center;
}
.error{
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    text-align:center;
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

<h2>Create User (Direct - Admin)</h2>

<?php
if($message!=""){
    if(strpos($message,"❌")!==false)
        echo "<div class='error'>$message</div>";
    else
        echo "<div class='success'>$message</div>";
}
?>

<form method="POST">

<label>Full Name</label>
<input type="text" name="name" required>

<label>Username</label>
<input type="text" name="username" required>

<label>Password</label>
<input type="password" name="password" required>

<label>ID Card Number</label>
<input type="text" name="id_number" required>

<label>Rank</label>
<select name="role" required>
    <option value="ADJQM">ADMIN</option>
    <option value="CO">CO</option>
    <option value="ITJCO">IT JCO</option>
    <option value="CLERK">CLERK</option>
    <option value="USER">USER</option>
</select>

<button type="submit" name="create">Create User</button>

</form>

</div>

<div class="footer">
    <a href="../dashboard.php">← Back to Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="../logout.php">Logout</a>
</div>

</div>

</body>
</html>
