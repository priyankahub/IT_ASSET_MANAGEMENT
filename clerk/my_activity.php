<?php
session_start();
include("../config/db.php");

if ($_SESSION['rank'] != 'CLERK') die("Access Denied");

$res = mysqli_query($conn,"
    SELECT * FROM user_requests
    WHERE requested_by='{$_SESSION['username']}'
    ORDER BY request_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Activity Log</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#0f223a;
    color:#f5f7fa;
}

/* HEADER */
.header{
    padding:25px 0;
    font-size:22px;
    font-weight:600;
    background:#122944;
    border-bottom:3px solid #d4af37;
    text-align:center;
}

/* CONTAINER */
.container{
    width:95%;
    max-width:1100px;
    margin:50px auto;
}

/* CARD */
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    border-left:4px solid #d4af37;
    transition:all 0.35s ease;
    position:relative;
    overflow:hidden;
}

/* Lift + Glow */
.card:hover{
    transform:translateY(-10px) scale(1.01);
    background:#1d3a5c;
    box-shadow:
        0 0 25px rgba(212,175,55,0.6),
        0 20px 40px rgba(0,0,0,0.75);
}

/* Highlight sweep effect */
.card::before{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:100%;
    background:linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.08),
        transparent
    );
    transition:0.6s;
}

.card:hover::before{
    left:100%;
}

/* TITLE */
h2{
    margin-bottom:20px;
    font-weight:600;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#122944;
    padding:12px;
    text-align:left;
    font-weight:600;
}

td{
    padding:12px;
    border-bottom:1px solid #2c4c73;
}

tr:hover{
    background:#1a355a;
}

/* STATUS COLORS */
.status-approved{
    color:#6dd3ce;
    font-weight:600;
}

.status-pending{
    color:#ffd166;
    font-weight:600;
}

.status-rejected{
    color:#ff8fa3;
    font-weight:600;
}

/* FOOTER */
.footer{
    text-align:center;
    margin-top:40px;
}

.footer a{
    text-decoration:none;
    padding:12px 24px;
    background:#d4af37;
    color:#0f223a;
    border-radius:6px;
    font-weight:600;
}

.footer a:hover{
    background:#c39c2d;
}
</style>
</head>

<body>

<div class="header">
INF BN – MY ACTIVITY LOG
</div>

<div class="container">

<div class="card">

<h2>My Activity Log</h2>

<table>
<tr>
<th>Request Type</th>
<th>Username</th>
<th>Status</th>
<th>Approved By</th>
<th>Date</th>
</tr>

<?php
while($r=mysqli_fetch_assoc($res)){

$statusClass = "";
if($r['status']=="Approved") $statusClass="status-approved";
if($r['status']=="Pending") $statusClass="status-pending";
if($r['status']=="Rejected") $statusClass="status-rejected";

echo "<tr>
<td>{$r['request_type']}</td>
<td>{$r['username']}</td>
<td class='$statusClass'>{$r['status']}</td>
<td>{$r['approved_by']}</td>
<td>{$r['request_date']}</td>
</tr>";
}
?>

</table>

</div>

<div class="footer">
<a href="../dashboard.php">Return to Dashboard</a>
</div>

</div>

</body>
</html>