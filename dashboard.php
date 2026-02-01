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
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
        }

        .header {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
            color: #2a5298;
        }

        .role-badge {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            background: #2a5298;
            color: #fff;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            text-align: center;
        }

        .card h3 {
            margin-bottom: 15px;
            color: #2a5298;
        }

        .card a {
            display: block;
            margin: 8px 0;
            padding: 10px;
            background: #2a5298;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .card a:hover {
            background: #1e3c72;
        }

        .logout {
            margin-top: 30px;
            text-align: center;
        }

        .logout a {
            color: #fff;
            font-size: 14px;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
        }

        .logout a:hover {
            background: rgba(255,255,255,0.35);
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

    <!-- Role-based Cards -->
    <div class="cards">

        <?php if ($r == 'CO') { ?>
            <div class="card">
                <h3>Reports & Insights</h3>
                <a href="reports/holding_state.php">Holding State</a>
                <a href="reports/lifecycle_report.php">Life Cycle History</a>
                <a href="reports/maintenance_report.php">Maintenance Reports</a>
            </div>
        <?php } ?>

        <?php if ($r == 'ADJQM') { ?>
            <div class="card">
                <h3>Admin Controls</h3>
                <a href="admin/equipment_master.php">Equipment Master</a>
                <a href="admin/disposal.php">Condemnation</a>
                <a href="reports/analytics.php">Analytics</a>
            </div>
        <?php } ?>

        <?php if ($r == 'ITJCO') { ?>
            <div class="card">
                <h3>Maintenance</h3>
                <a href="it_jco/maintenance.php">Maintenance</a>
                <a href="it_jco/warranty.php">Warranty Tracking</a>
            </div>
        <?php } ?>

        <?php if ($r == 'CLERK') { ?>
            <div class="card">
                <h3>Allocation</h3>
                <a href="clerk/allocation.php">Equipment Allocation</a>
            </div>
        <?php } ?>

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
