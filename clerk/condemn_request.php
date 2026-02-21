<?php
session_start();
include("../config/db.php");

if ($_SESSION['rank'] != 'CLERK') die("Access Denied");

if(isset($_POST['submit'])){
foreach($_POST['equipment_ids'] as $eid){
mysqli_query($conn,"
INSERT INTO condemnation_requests
(equipment_id, requested_by, request_date)
VALUES
('$eid','{$_SESSION['username']}',CURDATE())
");
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Raise Condemnation Request</title>

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
    max-width:950px;
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

/* Highlight sweep animation */
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

/* CHECKBOX LIST */
.checkbox-group{
    margin-bottom:10px;
}

.checkbox-group input[type="checkbox"]{
    transform:scale(1.2);
    margin-right:8px;
}

.checkbox-item{
    padding:8px 0;
    border-bottom:1px solid #2c4c73;
}

.checkbox-item:hover{
    background:#1a355a;
}

/* BUTTON */
button{
    margin-top:20px;
    padding:12px;
    width:100%;
    background:#d4af37;
    color:#0f223a;
    border:none;
    border-radius:6px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#c39c2d;
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
INF BN – RAISE CONDEMNATION REQUEST
</div>

<div class="container">

<div class="card">

<h2>Raise Condemnation Request</h2>

<form method="POST">

<?php
$res=mysqli_query($conn,"
SELECT * FROM equipment WHERE status!='Condemned'
");

while($e=mysqli_fetch_assoc($res)){
echo "
<div class='checkbox-item'>
<input type='checkbox' name='equipment_ids[]' value='{$e['id']}'>
<strong>{$e['type']}</strong> - {$e['make']}
</div>
";
}
?>

<button name="submit">Send for Approval</button>

</form>

</div>

<div class="footer">
<a href="../dashboard.php">Return to Dashboard</a>
</div>

</div>

</body>
</html>