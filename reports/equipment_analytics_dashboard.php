<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit;
}

/* ===============================
   FETCH COUNTS FROM EQUIPMENT
================================*/

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM equipment");
$total_data = mysqli_fetch_assoc($total_query);
$total = $total_data['total'];

function getCount($conn, $status){
    $q = mysqli_query($conn, "SELECT COUNT(*) as count FROM equipment WHERE status='$status'");
    $d = mysqli_fetch_assoc($q);
    return $d['count'];
}

$serviceable = getCount($conn,'Serviceable');
$non_serviceable = getCount($conn,'Non-Serviceable');
$maintenance = getCount($conn,'Under-Maintenance');
$condemned = getCount($conn,'Condemned');
$pending = getCount($conn,'Pending Approval');

?>
<!DOCTYPE html>
<html>
<head>
<title>Equipment Analytics Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background: radial-gradient(circle at top,#0f2027,#203a43 60%,#0a1923);
    color:#fff;
}

/* Ribbon */
.ribbon{
    background:#0c1f33;
    padding:15px;
    text-align:center;
    border-bottom:3px solid #d4af37;
    font-size:22px;
    font-weight:bold;
}

/* Container */
.container{
    width:92%;
    margin:40px auto;
}

/* Summary Cards */
.summary{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:25px;
    margin-bottom:40px;
}

.card{
    background:rgba(10,25,40,0.95);
    padding:25px;
    border-radius:18px;
    text-align:center;
    border:1px solid rgba(0,198,255,0.4);
    transition:0.4s ease;
    box-shadow:0 10px 30px rgba(0,0,0,0.7);
}

.card:hover{
    transform:translateY(-8px) scale(1.05);
    box-shadow:0 0 25px #00c6ff;
}

.card h3{
    margin-bottom:10px;
}

.count{
    font-size:28px;
    font-weight:bold;
    color:#00c6ff;
}

/* Table */
table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:40px;
}

th, td{
    padding:12px;
    border:1px solid #00c6ff;
    text-align:center;
}

th{
    background:#102a3a;
}

/* Download Buttons */
.download-btn{
    padding:8px 15px;
    background:#00c6ff;
    color:#001f54;
    border:none;
    border-radius:20px;
    cursor:pointer;
    transition:0.3s ease;
}

.download-btn:hover{
    background:#00f7ff;
    box-shadow:0 0 15px #00f7ff;
}

/* Charts */
.chart-container{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px;
    margin-top:40px;
}

/* Chart Card Wrapper */
.chart-box{
    background:rgba(10,25,40,0.95);
    padding:20px;
    border-radius:18px;
    border:1px solid rgba(0,198,255,0.4);
    box-shadow:0 10px 30px rgba(0,0,0,0.6);
    transition:0.4s ease;
}

.chart-box:hover{
    transform:translateY(-5px);
    box-shadow:0 0 25px #00c6ff;
}

/* Control chart size */
.chart-box canvas{
    width:100% !important;
    height:350px !important;   /* THIS fixes oversize issue */
}
.back{
    display:inline-block;
    margin-top:30px;
    padding:10px 20px;
    background:#00c6ff;
    color:#001f54;
    border-radius:25px;
    text-decoration:none;
    font-weight:bold;
}
.back:hover{
    background:#00f7ff;
}
</style>
</head>

<body>

<div class="ribbon">
    Equipment Inventory Analytics
</div>

<div class="container">

<!-- SUMMARY CARDS -->
<div class="summary">

<div class="card">
    <h3>Total Equipment</h3>
    <div class="count"><?php echo $total; ?></div>
</div>

<div class="card">
    <h3>Serviceable</h3>
    <div class="count"><?php echo $serviceable; ?></div>
</div>

<div class="card">
    <h3>Under Maintenance</h3>
    <div class="count"><?php echo $maintenance; ?></div>
</div>

<div class="card">
    <h3>Non-Serviceable</h3>
    <div class="count"><?php echo $non_serviceable; ?></div>
</div>

<div class="card">
    <h3>Condemned</h3>
    <div class="count"><?php echo $condemned; ?></div>
</div>

<div class="card">
    <h3>Pending Approval</h3>
    <div class="count"><?php echo $pending; ?></div>
</div>

</div>

<!-- TABLE REPORT -->
<h2>Status Breakdown Table</h2>
<table>
<tr>
    <th>Status</th>
    <th>Count</th>
    <th>Download Report</th>
</tr>

<tr>
    <td>Serviceable</td>
    <td><?php echo $serviceable; ?></td>
    <td>
        <a href="download_equipment_report.php?status=Serviceable">
            <button class="download-btn">Download</button>
        </a>
    </td>
</tr>

<tr>
    <td>Under-Maintenance</td>
    <td><?php echo $maintenance; ?></td>
    <td>
        <a href="download_equipment_report.php?status=Under-Maintenance">
            <button class="download-btn">Download</button>
        </a>
    </td>
</tr>

<tr>
    <td>Non-Serviceable</td>
    <td><?php echo $non_serviceable; ?></td>
    <td>
        <a href="download_equipment_report.php?status=Non-Serviceable">
            <button class="download-btn">Download</button>
        </a>
    </td>
</tr>

<tr>
    <td>Condemned</td>
    <td><?php echo $condemned; ?></td>
    <td>
        <a href="download_equipment_report.php?status=Condemned">
            <button class="download-btn">Download</button>
        </a>
    </td>
</tr>

<tr>
    <td>Pending Approval</td>
    <td><?php echo $pending; ?></td>
    <td>
        <a href="download_equipment_report.php?status=Pending Approval">
            <button class="download-btn">Download</button>
        </a>
    </td>
</tr>

</table>

<!-- CHARTS -->
<div class="chart-container">

    <div class="chart-box">
        <canvas id="pieChart"></canvas>
    </div>

    <div class="chart-box">
        <canvas id="barChart"></canvas>
    </div>

</div>

<a href="../dashboard.php" class="back">⬅ Back to Dashboard</a>

</div>

<script>

const dataValues = [
    <?php echo $serviceable; ?>,
    <?php echo $maintenance; ?>,
    <?php echo $non_serviceable; ?>,
    <?php echo $condemned; ?>,
    <?php echo $pending; ?>
];

const labels = [
    'Serviceable',
    'Under Maintenance',
    'Non-Serviceable',
    'Condemned',
    'Pending Approval'
];

// PIE CHART
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: dataValues,
            backgroundColor:[
                '#00c6ff',
                '#ff9800',
                '#ff4d4d',
                '#9c27b0',
                '#ffc107'
            ],
            borderWidth:1
        }]
    },
    options:{
        responsive: true,
        maintainAspectRatio: false,
        plugins:{
            legend:{
                labels:{color:'#fff'}
            }
        }
    }
});


// BAR CHART
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label:'Equipment Count',
            data: dataValues,
            backgroundColor:'#00c6ff'
        }]
    },
    options:{
        responsive: true,
        maintainAspectRatio: false,
        scales:{
            y:{
                ticks:{color:'#fff'},
                grid:{color:'rgba(255,255,255,0.1)'}
            },
            x:{
                ticks:{color:'#fff'},
                grid:{color:'rgba(255,255,255,0.1)'}
            }
        },
        plugins:{
            legend:{
                labels:{color:'#fff'}
            }
        }
    }
});

</script>

</body>
</html>