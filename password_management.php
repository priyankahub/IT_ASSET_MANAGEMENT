<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

    mysqli_query($conn, "UPDATE users SET password='$new_password' WHERE username='$username'");

    $message = "Password Updated Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Password Management</title>
<style>

.pass-card{
    width:450px;
    margin:100px auto;
    background:rgba(10,25,40,0.95);
    padding:40px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,0.7);
    border:1px solid rgba(0,198,255,0.3);
    color:white;
}

.pass-card h2{
    text-align:center;
    margin-bottom:30px;
}

.pass-card input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border-radius:8px;
    border:none;
}

.pass-card button{
    width:100%;
    padding:12px;
    background:#00c6ff;
    border:none;
    border-radius:25px;
    font-weight:bold;
    cursor:pointer;
}

.pass-card button:hover{
    background:#008ccf;
}

.message{
    text-align:center;
    margin-bottom:15px;
    color:#00ff99;
}

.back-btn{
    text-align:center;
    margin-top:20px;
}

.back-btn a{
    background:#00c6ff;
    padding:10px 25px;
    border-radius:25px;
    text-decoration:none;
    color:#001f54;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="pass-card">
    <h2>Password Management</h2>

    <?php if($message!=""){ echo "<div class='message'>$message</div>"; } ?>

    <form method="POST">
        <input type="password" name="new_password" placeholder="Enter New Password" required>
        <button type="submit">Update Password</button>
    </form>

    <div class="back-btn">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

</body>
</html>