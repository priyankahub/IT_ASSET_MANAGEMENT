<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'ADMIN') {
    die("Access Denied");
}

function safe_count($conn, $query){
    $result = mysqli_query($conn, $query);
    if($result && mysqli_num_rows($result) > 0){
        return mysqli_fetch_assoc($result)['count'];
    }
    return 0;
}

$total = safe_count($conn,"SELECT COUNT(*) as count FROM equipment");
$serviceable = safe_count($conn,"SELECT status,COUNT(*) as count FROM equipment GROUP BY status");
$condemned = safe_count($conn,"SELECT COUNT(*) as count FROM equipment WHERE status='Condemned'");
$allocated = safe_count($conn,"SELECT status,COUNT(*) as count FROM allocation group by status");
$warranty_risk = safe_count($conn,"SELECT COUNT(*) as count FROM equipment WHERE warranty_end <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)");
$maintenance_recent = safe_count($conn,"SELECT COUNT(*) as count FROM maintenance WHERE start_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)");

$readiness = $total>0?round(($serviceable/$total)*100,1):0;
$allocation_rate = $total>0?round(($allocated/$total)*100,1):0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Defense Command Monitoring Panel</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#0f223a; /* slightly lighter navy */
    color:#f5f7fa;
}

/* HEADER */
.header{
    padding:25px 40px;
    font-size:22px;
    font-weight:600;
    letter-spacing:1px;
    background:#122944;
    border-bottom:3px solid #d4af37;
    text-align:center; 
}

/* SECTION */
.section{
    padding:35px 50px;
}

/* KPI GRID */
.kpi-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:25px;
    margin-bottom:50px;
}

.kpi{
    background:#162f4f;
    padding:20px;
    border-radius:6px;
    border-left:4px solid #d4af37;
    transition:all 0.3s ease;
    position:relative;
}

.kpi:hover{
    transform:translateY(-8px);
    box-shadow:0 0 20px rgba(212,175,55,0.6),
               0 15px 35px rgba(0,0,0,0.7);
    background:#1d3a5c;
}


.kpi-title{
    font-size:13px;
    color:#b8c6db;
}

.kpi-value{
    font-size:26px;
    font-weight:600;
    color:#ffffff;
}

/* CHART GRID */
.chart-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:50px;
}

.chart-box{
    background:#162f4f;
    padding:20px;
    border-radius:6px;
    transition:all 0.3s ease;
    position:relative;
}

.chart-box:hover{
    transform:translateY(-8px);
    box-shadow:0 0 25px rgba(212,175,55,0.5),
               0 15px 35px rgba(0,0,0,0.7);
    background:#1d3a5c;
}

canvas{
    max-height:300px;
}

/* BUTTON */
.footer{
    padding:40px;
    text-align:center;
}

.footer a{
    background:#d4af37;
    color:#0f223a;
    padding:10px 22px;
    border-radius:4px;
    text-decoration:none;
    font-weight:600;
}
.footer a:hover{
    background:#c39c2d;
}
</style>
</head>

<body>

<div class="header">
IT EQUIPMENT RECORDS ANALYTICS – DEFENSE COMMAND MONITORING PANEL
</div>

<div class="section">

    <div class="kpi-grid">

        <div class="kpi">
            <div class="kpi-title">TOTAL ASSETS</div>
            <div class="kpi-value"><?php echo $total;?></div>
            <div class="kpi-desc">
                Total equipment currently recorded in the inventory system.
            </div>
        </div>

        <div class="kpi">
            <div class="kpi-title">OPERATIONAL READINESS</div>
            <div class="kpi-value"><?php echo $readiness;?>%</div>
            <div class="kpi-desc">
                Percentage of serviceable assets ready for deployment.
            </div>
        </div>

        <div class="kpi">
            <div class="kpi-title">DEPLOYMENT UTILIZATION</div>
            <div class="kpi-value"><?php echo $allocation_rate;?>%</div>
            <div class="kpi-desc">
                Ratio of allocated equipment to total assets.
            </div>
        </div>

        <div class="kpi">
            <div class="kpi-title">WARRANTY RISK (90 DAYS)</div>
            <div class="kpi-value"><?php echo $warranty_risk;?></div>
            <div class="kpi-desc">
                Assets with warranty expiring within the next 90 days.
            </div>
        </div>

    </div>

    <div class="chart-grid">
        <div class="chart-box">
            <canvas id="statusChart"></canvas>
        </div>
        <div class="chart-box">
            <canvas id="allocationChart"></canvas>
        </div>
    </div>

</div>

<div class="footer">
    <a href="../dashboard.php">Return to Dashboard</a>
</div>

<script>
Chart.defaults.color = "#f5f7fa";

new Chart(document.getElementById('statusChart'), {
    type:'doughnut',
    data:{
        labels:['Serviceable','Condemned','Other'],
        datasets:[{
            data:[<?php echo $serviceable;?>,<?php echo $condemned;?>,<?php echo $total-($serviceable+$condemned);?>],
            backgroundColor:[
                '#6dd3ce',  // pastel teal
                '#ff8fa3',  // soft red
                '#89c2ff'   // soft blue
            ]
        }]
    }
});

new Chart(document.getElementById('allocationChart'), {
    type:'bar',
    data:{
        labels:['Allocated','Unallocated'],
        datasets:[{
            data:[<?php echo $allocated;?>,<?php echo $total-$allocated;?>],
            backgroundColor:[
                '#ffd166',
                '#90caf9'
            ]
        }]
    },
    options:{
        scales:{
            y:{ beginAtZero:true }
        }
    }
});
</script>

</body>
</html>