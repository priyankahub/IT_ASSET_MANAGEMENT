<?php
session_start();
include("../config/db.php");

/* =========================
   ROLE CHECK
========================= */
if ($_SESSION['rank'] != 'CLERK') die("Access Denied");

/* =========================
   FETCH ACTIVITY LOGS
========================= */
$res = mysqli_query($conn,"
    SELECT * FROM activity_logs
    WHERE user_username='{$_SESSION['username']}'
    ORDER BY action_date DESC
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
.header{
    padding:25px 0;
    font-size:22px;
    font-weight:600;
    background:#122944;
    border-bottom:3px solid #d4af37;
    text-align:center;
}
.container{
    width:95%;
    max-width:1100px;
    margin:50px auto;
}
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    border-left:4px solid #d4af37;
}
h2{
    margin-bottom:20px;
}
table{
    width:100%;
    border-collapse:collapse;
}
th{
    background:#122944;
    padding:12px;
    text-align:left;
}
td{
    padding:12px;
    border-bottom:1px solid #2c4c73;
}
tr:hover{
    background:#1a355a;
}
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
<th>Action</th>
<th>Description</th>
<th>Date</th>
</tr>

<?php
if(mysqli_num_rows($res) > 0){
    while($r=mysqli_fetch_assoc($res)){
        echo "<tr>
        <td>{$r['action_tag']}</td>
        <td>{$r['description']}</td>
        <td>{$r['action_date']}</td>
        </tr>";
    }
} else {
    echo "<tr>
        <td colspan='3' style='text-align:center;'>No activity found.</td>
    </tr>";
}
?>

</table>

</div>

<div class="footer">
<a href='../dashboard.php'>Return to Dashboard</a>
</div>

</div>
</body>
</html>