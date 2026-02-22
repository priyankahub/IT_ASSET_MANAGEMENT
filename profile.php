<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<link rel="stylesheet" href="assets/css/style.css">
<style>

.profile-card{
    width:450px;
    margin:100px auto;
    background:rgba(10,25,40,0.95);
    padding:40px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,0.7);
    border:1px solid rgba(0,198,255,0.3);
    color:white;
}

.profile-card h2{
    text-align:center;
    margin-bottom:30px;
}

.profile-item{
    margin-bottom:15px;
    font-size:15px;
}

.back-btn{
    margin-top:25px;
    text-align:center;
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

<div class="profile-card">
    <h2>Personal Details</h2>

    <div class="profile-item"><strong>Full Name:</strong> <?php echo $user['full_name']; ?></div>
    <div class="profile-item"><strong>Username:</strong> <?php echo $user['username']; ?></div>
    <div class="profile-item"><strong>Password:</strong> <?php echo $user['password']; ?></div>
    <div class="profile-item"><strong>Rank:</strong> <?php echo $user['rank']; ?></div>

    <div class="back-btn">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

</body>
</html>