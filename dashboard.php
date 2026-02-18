<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$r = $_SESSION['role'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>
body{
    margin:0;
    padding:0;
    min-height:100vh;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:90%;
    max-width:1100px;
    margin:40px auto;
}
.header{
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,0.2);
    margin-bottom:30px;
}
.header h2{
    margin:0;
    color:#2a5298;
}
.role-badge{
    display:inline-block;
    margin-top:8px;
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    background:#2a5298;
    color:#fff;
}
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 6px 15px rgba(0,0,0,0.15);
    text-align:center;
}
.card h3{
    margin-bottom:15px;
    color:#2a5298;
}
.card a{
    display:block;
    margin:8px 0;
    padding:10px;
    background:#2a5298;
    color:#fff;
    text-decoration:none;
    border-radius:5px;
    font-size:14px;
    transition:0.3s;
}
.card a:hover{
    background:#1e3c72;
}
.logout{
    margin-top:30px;
    text-align:center;
}
.logout a{
    color:#fff;
    font-size:14px;
    text-decoration:none;
    background:rgba(255,255,255,0.2);
    padding:8px 15px;
    border-radius:20px;
}
.logout a:hover{
    background:rgba(255,255,255,0.35);
}
</style>
</head>

<body>

<div class="container">

<!-- Header -->
<div class="header">
    <h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>
    <span class="role-badge"><?php echo $r; ?></span>
</div>

<div class="cards">

<!-- ================= CO ================= -->
<?php if ($r == 'CO') { ?>
<div class="card">
<h3>Reports & Oversight</h3>
<a href="reports/holding_state.php">Holding State</a>
<a href="reports/lifecycle_report.php">Life Cycle History</a>
<a href="reports/maintenance_report.php">Maintenance Reports</a>
<a href="reports/analytics.php">Analytics Dashboard</a>
</div>
<?php } ?>

<!-- ================= ADMIN ================= -->
<?php if ($r == 'ADJQM') { ?>
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

<!-- ================= IT JCO ================= -->
<?php if ($r == 'ITJCO') { ?>
<div class="card">
<h3>Maintenance Control</h3>
<a href="it_jco/maintenance.php">Maintenance Entry</a>
<a href="it_jco/warranty.php">Warranty Tracking</a>
</div>
<?php } ?>

<!-- ================= CLERK ================= -->
<?php if ($r == 'CLERK') { ?>
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

<!-- ================= USER ================= -->
<?php if ($r == 'USER') { ?>
<div class="card">
<h3>My Assets</h3>
<a href="user/my_equipment.php">My Equipment</a>
</div>
<?php } ?>

</div>

<!-- Logout -->
<div class="logout">
<a href="logout.php">Logout</a>
</div>

</div>

</body>
</html>
