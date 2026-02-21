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
    flex-direction:column;
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

/* ===== TOP RIBBON ===== */
.ribbon{
    width:100%;
    background:#0c1f33;
    border-bottom:3px solid #d4af37;
    padding:15px 0;
    text-align:center;
    box-shadow:0 5px 25px rgba(0,0,0,0.6);
}

.ribbon h1{
    margin:0;
    font-size:24px;
    letter-spacing:2px;
    font-weight:700;
    color:#ffffff;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:20px;
}

.ribbon img{
    height:45px;
}

/* ===== Container ===== */
.container{
    width:92%;
    max-width:1400px;
    margin:60px auto;
}

/* ===== Welcome Card ===== */
.header{
    background:rgba(10,25,40,0.9);
    padding:35px;
    border-radius:18px;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
    margin-bottom:45px;
    border:1px solid rgba(0,198,255,0.3);
}

.header h2{
    margin:0;
    font-size:24px;
}

.role-badge{
    display:inline-block;
    margin-top:15px;
    padding:8px 20px;
    border-radius:25px;
    font-size:13px;
    background:#00c6ff;
    color:#001f54;
    font-weight:bold;
}

/* ===== Cards ===== */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:35px;
}

.card{
    background:rgba(10,25,40,0.92);
    padding:40px 30px;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
    transition:0.4s ease;
    border:1px solid rgba(0,198,255,0.25);
    min-height:220px;
}
/* ===== FULL WIDTH CARD (2nd Row Stretch) ===== */
/* ===========================
   FULL WIDTH ACTIVITY BANNER
=========================== */
/* ===========================
   FULL WIDTH ACTIVITY BANNER
=========================== */

.activity-banner{
    grid-column: 1 / -1; /* span entire row */
    min-height: 150px;   /* smaller height */
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:35px;
}

/* Make button slightly larger for banner look */
.activity-banner a{
    max-width:300px;
}
.full-width-card{
    grid-column: 1 / -1;   /* span entire row */
    min-height: 160px;     /* shorter than other cards */
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
}
.card:hover{
    transform:translateY(-10px);
    box-shadow:0 25px 60px rgba(0,0,0,0.9);
    border-color:#00c6ff;
}

.card h3{
    margin-bottom:25px;
    font-size:18px;
}

.card a{
    display:block;
    margin:14px 0;
    padding:14px;
    background:#102a3a;
    color:#00c6ff;
    text-decoration:none;
    border-radius:10px;
    font-size:14px;
    font-weight:bold;
    transition:0.3s ease;
    border:1px solid rgba(0,198,255,0.3);
    text-align:center;
}

.card a:hover{
    background:#00c6ff;
    color:#001f54;
}

/* ===== Logout ===== */
.logout{
    margin-top:70px;
    text-align:center;
}

.logout a{
    background:#00c6ff;
    padding:12px 30px;
    border-radius:30px;
    text-decoration:none;
    color:#001f54;
    font-weight:bold;
}

</style>
</head>

<body>

<!-- Ribbon -->
<div class="ribbon">
    <h1>
        <img src="images/logo.jpg">
        IT Equipment Lifecycle Management
        <img src="images/logo.jpg">
    </h1>
</div>

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
        <a href="admin/create_user.php">Create User</a>
        <a href="admin/manage_users.php">Manage Users</a>
        <a href="admin/approve_user_requests.php">Approve User Requests</a>
    </div>

    <div class="card">
        <h3>Approval Workflows</h3>
        <a href="admin/approve_condemn_requests.php">Approve Condemnation</a>
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
        <h3>Equipment Registration</h3>
        <a href="admin/equipment_master.php">Update Equipment Status</a>
    </div>

    <div class="card">
        <h3>Equipment Allocation</h3>
        <a href="clerk/allocation.php">Issue / Return Equipment</a>
    </div>

    <div class="card">
        <h3>Condemnation</h3>
        <a href="clerk/condemn_request.php">Raise Condemnation Request</a>
    </div>

    <div class="card">
        <h3>User Requests</h3>
        <a href="clerk/user_request.php">Create New User</a>
        <a href="clerk/edit_user_request.php">Update User</a>
        <a href="clerk/delete_user_request.php">Delete User</a>
    </div>

    <div class="card activity-banner">
        <h3>My Activity Log</h3>
        <a href="clerk/my_activity.php">View My Activity</a>
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