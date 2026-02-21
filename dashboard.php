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
body{
    margin:0;
    padding:40px 0;
    min-height:30vh;
    font-family:'Segoe UI',sans-serif;
    background:radial-gradient(circle at center,#0a1f44 0%,#000814 85%);
    display:flex;
    align-items:center;
    justify-content:center;
}

/* ===== Container ===== */
.container{
    width:92%;
    max-width:1200px;
    margin:120px auto 60px auto;
}

/* ===== Header Card ===== */
.header{
    background:#f4f4f4;
    padding:30px;
    border-radius:14px;
    box-shadow:0 15px 40px rgba(0,0,0,0.5);
    margin-bottom:35px;
    border-left:6px solid #FFD700;
}

.header h2{
    margin:0;
    color:#001f54;
    font-weight:700;
    letter-spacing:1px;
}

/* Role Badge */
.role-badge{
    display:inline-block;
    margin-top:12px;
    padding:6px 18px;
    border-radius:20px;
    font-size:13px;
    background:#001f54;
    color:#FFD700;
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
    background:#f4f4f4;
    padding:28px;
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
    transition:0.3s ease;
    border-top:4px solid #FFD700;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 20px 45px rgba(0,0,0,0.6);
}

.card h3{
    margin-bottom:18px;
    color:#001f54;
    font-weight:700;
}

/* ===== Buttons ===== */
.card a{
    display:block;
    margin:10px 0;
    padding:11px;
    background:#001f54;
    color:#FFD700;
    text-decoration:none;
    border-radius:8px;
    font-size:14px;
    font-weight:bold;
    transition:0.3s;
}

.card a:hover{
    background:#FFD700;
    color:#001f54;
}

/* ===== Logout ===== */
.logout{
    margin-top:50px;
    text-align:center;
}

.logout a{
    color:#001f54;
    font-size:14px;
    text-decoration:none;
    background:#FFD700;
    padding:10px 22px;
    border-radius:25px;
    font-weight:bold;
    transition:0.3s;
}

.logout a:hover{
    background:#e6c200;
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