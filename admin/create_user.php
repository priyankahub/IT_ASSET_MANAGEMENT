<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'ADMIN') {
    die("Access Denied");
}

$message = "";

if (isset($_POST['submit_request'])) {

    $full_name    = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $army_no      = mysqli_real_escape_string($conn, $_POST['army_no']);
    $rank         = mysqli_real_escape_string($conn, $_POST['rank']);
    $requested_by = $_SESSION['username'];
    $today        = date('Y-m-d');

    $password = "Password@#123";

    $checkUser = mysqli_query($conn,"
        SELECT id FROM users WHERE username='$username'
        UNION
        SELECT id FROM user_requests WHERE username='$username'
    ");

    $checkArmy = mysqli_query($conn,"
        SELECT id FROM users WHERE army_no='$army_no'
        UNION
        SELECT id FROM user_requests WHERE army_no='$army_no'
    ");

    if (mysqli_num_rows($checkUser) > 0) {
        $message = "❌ Username already exists.";
    }
    elseif (mysqli_num_rows($checkArmy) > 0) {
        $message = "❌ Army Number already exists.";
    }
    else {

        mysqli_query($conn,"
            INSERT INTO user_requests
            (request_type, full_name, username, password, army_no, rank, requested_by, request_date, status)
            VALUES
            ('CREATE','$full_name','$username','$password','$army_no','$rank','$requested_by','$today','Pending')
        ");

        header("Location: approve_user_requests.php?created=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Create User | Military Admin</title>

<style>

/* ================= BODY ================= */

body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background:
        radial-gradient(circle at top left, #0f2027, #203a43 60%, #0a1923);
    color:#f5f5f5;
}

/* subtle tech grid overlay */
body::before{
    content:"";
    position:fixed;
    width:100%;
    height:100%;
    background-image:
        linear-gradient(rgba(0,150,255,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0,150,255,0.06) 1px, transparent 1px);
    background-size:40px 40px;
    pointer-events:none;
}

/* ================= HEADER ================= */

.header{
    text-align:center;
    padding:25px 0;
}

.header img{
    height:70px;
    margin-bottom:10px;
    transition:transform 0.4s ease;
}

.header img:hover{
    transform:scale(1.08) rotate(-2deg);
}

.header h1{
    margin:0;
    font-size:26px;
    letter-spacing:2px;
    color:#00c6ff; /* Electric Blue */
}

/* ================= CONTAINER ================= */

.container{
    width:95%;
    max-width:650px;
    margin:20px auto 60px auto;
}

/* ================= CARD ================= */

.card{
    background:rgba(10,25,40,0.88);
    padding:35px;
    border-radius:15px;
    backdrop-filter: blur(10px);
    border:1px solid rgba(0,198,255,0.3);
    box-shadow:0 10px 30px rgba(0,0,0,0.7);
    transition: all 0.4s ease;
}

.card:hover{
    transform:translateY(-5px);
    box-shadow:0 15px 40px rgba(0,0,0,0.9);
}

/* ================= TITLE ================= */

h2{
    text-align:center;
    margin-bottom:25px;
    color#ffffff;
    letter-spacing:1px;
}

/* ================= FORM ================= */

label{
    font-weight:600;
    font-size:14px;
    letter-spacing:0.5px;
}

input, select{
    width:100%;
    padding:12px;
    margin-top:6px;
    margin-bottom:18px;
    border-radius:8px;
    border:1px solid #1e3a4d;
    background:#102a3a;
    color:#fff;
    transition:0.3s ease;
}

input:focus, select:focus{
    border-color:#00c6ff;
    box-shadow:0 0 12px rgba(0,198,255,0.7);
    outline:none;
}

/* ================= BUTTON ================= */

button{
    width:100%;
    padding:12px;
    background:linear-gradient(45deg,#005c97,#00c6ff);
    color:#fff;
    font-weight:bold;
    border:none;
    border-radius:8px;
    cursor:pointer;
    transition: all 0.3s ease;
    letter-spacing:1px;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 5px 20px rgba(0,198,255,0.8);
}

/* ================= MESSAGE ================= */

.success{
    background:#102f44;
    border-left:4px solid #00c6ff;
    padding:12px;
    border-radius:6px;
    margin-bottom:15px;
}

.error{
    background:#3a1f2d;
    border-left:4px solid #ff4d6d;
    padding:12px;
    border-radius:6px;
    margin-bottom:15px;
}

/* ================= FOOTER BADGE ================= */

.footer{
    text-align:center;
    margin-top:30px;
    font-size:12px;
    color:#aaa;
    letter-spacing:1px;
}

</style>
</head>

<body>

<div class="header">
    <img src="../images/logo.jpg" alt="Indian Army Logo">
    <h1>INDIAN ARMY IT ASSET MANAGEMENT</h1>
</div>

<div class="container">
<div class="card">

<h2>Create New User Request</h2>

<?php if($message!=""){ ?>
<div class="<?php echo (strpos($message,'❌') !== false) ? 'error' : 'success'; ?>">
<?php echo $message; ?>
</div>
<?php } ?>

<form method="POST">

<label>Full Name</label>
<input type="text" name="full_name" required>

<label>Username</label>
<input type="text" name="username" required>

<label>Army Number</label>
<input type="text" name="army_no" required>

<label>Rank</label>
<select name="rank" required>
    <option value="ADMIN">ADMIN</option>
    <option value="CO">CO</option>
    <option value="ITJCO">ITJCO</option>
    <option value="CLERK">CLERK</option>
    <option value="USER">USER</option>
</select>

<button type="submit" name="submit_request">
INITIATE USER CREATION
</button>

</form>

<div class="footer">
SECURE ADMIN PANEL • MILITARY GRADE SYSTEM
</div>

</div>
</div>

</body>
</html>