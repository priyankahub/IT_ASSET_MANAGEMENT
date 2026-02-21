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

/* ================= BODY ================= */
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left,#0f2027,#203a43 60%,#0a1923);
    color:#f5f7fa;
}

/* Subtle Grid Overlay */
body::before{
    content:"";
    position:fixed;
    width:100%;
    height:100%;
    background-image:
        linear-gradient(rgba(212,175,55,0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(212,175,55,0.05) 1px, transparent 1px);
    background-size:40px 40px;
    pointer-events:none;
}

/* ================= RIBBON ================= */
.ribbon{
    width:100%;
    background:#0c1f33;
    border-bottom:3px solid #d4af37;
    padding:18px 0;
    text-align:center;
    box-shadow:0 5px 25px rgba(0,0,0,0.6);
}

.ribbon h1{
    margin:0;
    font-size:22px;
    letter-spacing:2px;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:18px;
}

.ribbon img{
    height:45px;
    transition:0.3s ease;
}

.ribbon img:hover{
    transform:scale(1.1);
}

/* ================= CONTAINER ================= */
.container{
    width:95%;
    max-width:1100px;
    margin:60px auto;
}

/* ================= CARD ================= */
.card{
    background:rgba(10,25,40,0.92);
    padding:35px;
    border-radius:18px;
    border-left:4px solid #d4af37;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
    transition:0.4s ease;
    position:relative;
    overflow:hidden;
}

/* Lift + Glow */
.card:hover{
    transform:translateY(-12px) scale(1.01);
    box-shadow:
        0 0 30px rgba(212,175,55,0.6),
        0 25px 60px rgba(0,0,0,0.9);
}

/* Sweep Highlight */
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
    transition:0.7s;
}

.card:hover::before{
    left:100%;
}

/* ================= TITLE ================= */
h2{
    margin-bottom:25px;
    font-size:22px;
    letter-spacing:1px;
}

/* ================= TABLE ================= */
table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:12px;
}

th{
    background:#122944;
    padding:15px;
    text-align:left;
    font-weight:600;
}

td{
    padding:15px;
    border-bottom:1px solid rgba(255,255,255,0.05);
    transition:0.3s ease;
}

/* Row Hover Highlight */
tr:hover{
    background:rgba(212,175,55,0.08);
    box-shadow:inset 0 0 15px rgba(212,175,55,0.2);
}

/* ================= FOOTER ================= */
.footer{
    text-align:center;
    margin-top:50px;
}

.footer a{
    text-decoration:none;
    padding:14px 30px;
    background:#d4af37;
    color:#0f223a;
    border-radius:30px;
    font-weight:bold;
    transition:0.3s ease;
}

.footer a:hover{
    background:#c39c2d;
    box-shadow:0 5px 20px rgba(212,175,55,0.7);
}

</style>

</head>

<body>

<div class="ribbon">
    <h1>
        <img src="../images/logo.jpg">
        INF BN – MY ACTIVITY LOG
        <img src="../images/logo.jpg">
    </h1>
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