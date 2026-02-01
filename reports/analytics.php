<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADJQM') {
    die("Access Denied");
}

$total = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM equipment")
)['total'];

$serviceable = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) count FROM equipment WHERE status='Serviceable'")
)['count'];

$condemned = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) count FROM equipment WHERE status='Condemned'")
)['count'];

$others = $total - ($serviceable + $condemned);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Analytics Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 30px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .chart-container {
            position: relative;
            width: 320px;
            height: 320px;
        }

        .center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-weight: bold;
        }

        .center-text span {
            display: block;
            font-size: 26px;
        }

        .legend {
            list-style: none;
            padding: 0;
        }

        .legend li {
            margin-bottom: 10px;
            font-size: 15px;
        }

        .dot {
            height: 14px;
            width: 14px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .green { background: #2ecc71; }
        .red { background: #e74c3c; }
        .blue { background: #3498db; }

        .footer {
            text-align: center;
            margin-top: 25px;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Equipment Analytics Dashboard</h2>

    <div class="card">

        <!-- Doughnut Chart -->
        <div class="chart-container">
            <canvas id="chart" width="320" height="320"></canvas>
            <div class="center-text">
                TOTAL
                <span><?php echo $total; ?></span>
            </div>
        </div>

        <!-- Legend -->
        <ul class="legend">
            <li><span class="dot green"></span> Serviceable (<?php echo $serviceable; ?>)</li>
            <li><span class="dot red"></span> Condemned (<?php echo $condemned; ?>)</li>
            <li><span class="dot blue"></span> Others (<?php echo $others; ?>)</li>
        </ul>

    </div>

    <div class="footer">
        <a href="../dashboard.php">⬅ Back to Dashboard</a>
    </div>
</div>

<script>
const canvas = document.getElementById("chart");
const ctx = canvas.getContext("2d");

const data = [
    { value: <?php echo $serviceable; ?>, color: "#2ecc71" },
    { value: <?php echo $condemned; ?>, color: "#e74c3c" },
    { value: <?php echo $others; ?>, color: "#3498db" }
];

const total = <?php echo $total; ?>;
let startAngle = 0;

data.forEach(item => {
    const sliceAngle = (item.value / total) * 2 * Math.PI;

    ctx.beginPath();
    ctx.arc(160, 160, 130, startAngle, startAngle + sliceAngle);
    ctx.arc(160, 160, 80, startAngle + sliceAngle, startAngle, true);
    ctx.closePath();

    ctx.fillStyle = item.color;
    ctx.fill();

    startAngle += sliceAngle;
});
</script>

</body>
</html>
