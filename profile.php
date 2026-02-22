<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>User Management</title>

<style>

/* ===== BODY ===== */
body{
    margin:0;
    padding:0;
    min-height:100vh;
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left,#0f2027,#203a43 60%,#0a1923);
    display:flex;
    flex-direction:column;
    align-items:center;
    color:#fff;
}

/* Grid Overlay */
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

/* ===== RIBBON ===== */
.ribbon{
    width:100%;
    background:#0c1f33;
    border-bottom:3px solid #d4af37;
    padding:12px 0;
    text-align:center;
    box-shadow:0 5px 25px rgba(0,0,0,0.6);
}

.ribbon h1{
    margin:0;
    font-size:20px;
    letter-spacing:2px;
    color:#ffffff;
}

/* ===== EMBLEM ===== */
.emblem{
    margin-top:30px;
}

.emblem img{
    width:80px;
    filter:drop-shadow(0 0 10px rgba(0,198,255,0.6));
}

/* ===== PROFILE CARD ===== */
.profile-card{
    width:520px;
    margin:30px 0 60px;
    background:rgba(10,25,40,0.95);
    padding:45px;
    border-radius:20px;
    box-shadow:
        0 0 30px rgba(0,198,255,0.4),
        0 25px 60px rgba(0,0,0,0.8);
    border:1px solid rgba(0,198,255,0.3);
    transition:0.4s ease;
    position:relative;
    overflow:hidden;
}

/* Hover Highlight + Popup */
.profile-card:hover{
    transform:translateY(-10px) scale(1.02);
    box-shadow:
        0 0 40px rgba(0,198,255,0.7),
        0 35px 80px rgba(0,0,0,0.9);
}

/* Golden Top Strip */
.profile-card::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:6px;
    background:#d4af37;
}

/* Heading */
.profile-card h2{
    text-align:center;
    margin-bottom:35px;
    font-size:22px;
    letter-spacing:1px;
}

/* Detail Row */
.profile-item{
    margin-bottom:18px;
    padding:14px 18px;
    background:#102a3a;
    border-radius:12px;
    border:1px solid rgba(0,198,255,0.3);
    transition:0.3s;
}

/* Row Hover Glow */
.profile-item:hover{
    border-color:#00c6ff;
    box-shadow:0 0 15px rgba(0,198,255,0.4);
}

/* Label Highlight */
.profile-item strong{
    color:#00c6ff;
}

/* Role Badge */
.role-badge{
    display:inline-block;
    padding:6px 15px;
    border-radius:20px;
    font-size:12px;
    background:#00c6ff;
    color:#001f54;
    font-weight:bold;
}

/* Button */
.btn{
    text-align:center;
    margin-top:30px;
}

.btn a{
    background:#00c6ff;
    padding:12px 30px;
    border-radius:25px;
    text-decoration:none;
    color:#001f54;
    font-weight:bold;
    transition:0.3s;
}

.btn a:hover{
    background:#008ccf;
}

</style>
</head>

<body>

<!-- Ribbon -->
<div class="ribbon">
    <h1>USER MANAGEMENT</h1>
</div>

<!-- User pic -->
<div class="emblem">
    <div class="user-icon">
        <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="User Icon">
    </div>
</div>

<!-- Profile Card -->
<div class="profile-card">
    <h2>PERSONAL DETAILS</h2>

    <div class="profile-item">
        <strong>Full Name:</strong>
        <?php echo htmlspecialchars($user['name']); ?>
    </div>

    <div class="profile-item">
        <strong>Username:</strong>
        <?php echo htmlspecialchars($user['username']); ?>
    </div>

    <div class="profile-item">
        <strong>Password:</strong>
        <?php echo htmlspecialchars($user['password']); ?>
    </div>

    <div class="profile-item">
        <strong>Role:</strong>
        <span class="role-badge">
            <?php echo htmlspecialchars($user['role']); ?>
        </span>
    </div>

    <div class="btn">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>

</div>

</body>
</html>