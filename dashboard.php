<?php
session_start();

if (!isset($_SESSION['rank'])) {
    header("Location: index.php");
    exit;
}

$r = trim($_SESSION['rank']);
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

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
    align-items:center;
    justify-content:center;
    color:#fff;
}

/* Tech grid overlay */
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

/* ===== Container ===== */
.container{
    width:92%;
    max-width:1200px;
    margin:140px auto 60px auto;
}

/* ===== Army Header ===== */
.top-header{
    text-align:center;
    margin-bottom:30px;
}

.top-header img{
    height:70px;
    transition:0.4s ease;
}

.top-header img:hover{
    transform:scale(1.1) rotate(-2deg);
}

.top-header h1{
    margin:10px 0 0;
    font-size:22px;
    letter-spacing:2px;
    color:#00c6ff;
}

/* ===== Welcome Header Card ===== */
.header{
    background:rgba(10,25,40,0.9);
    padding:30px;
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
    margin-bottom:35px;
    border:1px solid rgba(0,198,255,0.3);
    backdrop-filter:blur(8px);
    transition:0.3s;
}

.header:hover{
    box-shadow:0 20px 50px rgba(0,0,0,0.8);
}

.header h2{
    margin:0;
    font-weight:700;
    letter-spacing:1px;
    color:#ffffff;
}

/* Role Badge */
.role-badge{
    display:inline-block;
    margin-top:12px;
    padding:6px 18px;
    border-radius:20px;
    font-size:13px;
    background:#00c6ff;
    color:#001f54;
    font-weight:bold;
    letter-spacing:1px;
}

/* ===== Cards Layout ===== */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(270px,1fr));
    gap:25px;
}

/* ===== Card Styling ===== */
.card{
    background:rgba(10,25,40,0.88);
    padding:28px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.5);
    transition:0.4s ease;
    border:1px solid rgba(0,198,255,0.25);
    backdrop-filter:blur(6px);
    position:relative;
    overflow:hidden;
}

.card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 50px rgba(0,0,0,0.9);
    border-color:#00c6ff;
}

/* glow animation sweep */
.card::before{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:100%;
    background:linear-gradient(120deg,transparent,rgba(0,198,255,0.2),transparent);
    transition:0.6s;
}

.card:hover::before{
    left:100%;
}

.card h3{
    margin-bottom:18px;
    font-weight:700;
    color:#ffffff;
}

/* ===== Buttons ===== */
.card a{
    display:block;
    margin:10px 0;
    padding:11px;
    background:#102a3a;
    color:#00c6ff;
    text-decoration:none;
    border-radius:8px;
    font-size:14px;
    font-weight:bold;
    transition:0.3s ease;
    border:1px solid rgba(0,198,255,0.3);
}

.card a:hover{
    background:#00c6ff;
    color:#001f54;
    box-shadow:0 5px 15px rgba(0,198,255,0.6);
    transform:translateX(4px);
}

/* ===== Logout ===== */
.logout{
    margin-top:60px;
    text-align:center;
}

.logout a{
    color:#001f54;
    font-size:14px;
    text-decoration:none;
    background:#00c6ff;
    padding:10px 24px;
    border-radius:25px;
    font-weight:bold;
    transition:0.3s;
}

.logout a:hover{
    background:#0099cc;
    box-shadow:0 5px 20px rgba(0,198,255,0.8);
}

</style>

</head>

<body>

<div class="container">

<div class="header">
    <h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>
    <span class="role-badge"><?php echo $r; ?></span>
</div>

<div class="cards">

<?php if ($r === 'ADMIN') { ?>
    <div class="card">
        <h3>Equipment Management</h3>
        <a href="admin/equipment_master.php">Equipment Master</a>
        <a href="admin/disposal.php">Direct Condemnation</a>
    </div>

    <div class="card">
        <h3>User Management</h3>
        <a href="admin/create_user.php">Create User (Direct)</a>
        <a href="admin/manage_users.php">Edit / Delete Users</a>
        <a href="admin/approve_user_requests.php">Approve User Requests</a>
    </div>

    <div class="card">
        <h3>Approval Workflows</h3>
        <a href="admin/approve_condemn_requests.php">Approve Condemnation Requests</a>
        <a href="reports/analytics.php">Analytics Dashboard</a>
    </div>
<?php } ?>

<?php if ($r === 'CO') { ?>
    <div class="card">
        <h3>Reports & Oversight</h3>
        <a href="reports/holding_state.php">Holding State</a>
        <a href="reports/lifecycle_report.php">Life Cycle History</a>
        <a href="reports/maintenance_report.php">Maintenance Reports</a>
        <a href="reports/analytics.php">Analytics Dashboard</a>
    </div>
<?php } ?>

<?php if ($r === 'ITJCO') { ?>
    <div class="card">
        <h3>Maintenance Control</h3>
        <a href="it_jco/maintenance.php">Maintenance Entry</a>
        <a href="it_jco/warranty.php">Warranty Tracking</a>
    </div>
<?php } ?>

<?php if ($r === 'CLERK') { ?>
    <div class="card">
        <h3>Equipment Allocation</h3>
        <a href="clerk/allocation.php">Issue / Return Equipment</a>
    </div>

    <div class="card">
        <h3>User Requests</h3>
        <a href="clerk/user_request.php">Create / Edit / Delete User Request</a>
        <a href="clerk/my_activity.php">My Activity Log</a>
    </div>

    <div class="card">
        <h3>Condemnation</h3>
        <a href="clerk/condemn_request.php">Raise Condemnation Request</a>
    </div>
<?php } ?>

<?php if ($r === 'USER') { ?>
    <div class="card">
        <h3>My Assets</h3>
        <a href="user/my_equipment.php">My Equipment</a>
    </div>
<?php } ?>

</div>

<div class="logout">
    <a href="logout.php">Logout</a>
</div>

</div>

</body>
</html>