<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN') {
    die("Access Denied");
}

$message = "";

if (isset($_POST['submit_request'])) {

    $full_name    = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $army_no      = mysqli_real_escape_string($conn, $_POST['army_no']);
    $role         = mysqli_real_escape_string($conn, $_POST['role']);
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
            (request_type, full_name, username, password, army_no, role, requested_by, request_date, status)
            VALUES
            ('CREATE','$full_name','$username','$password','$army_no','$role','$requested_by','$today','Pending')
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
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left,#0f2027,#203a43 60%,#0a1923);
    color:#f5f5f5;
}

/* Subtle Grid */
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
    max-width:650px;
    margin:60px auto;
}

/* ================= CARD ================= */
.card{
    background:rgba(10,25,40,0.92);
    padding:40px;
    border-radius:20px;
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

/* Sweep Animation */
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
    text-align:center;
    margin-bottom:30px;
    letter-spacing:1px;
}

/* ================= FORM ================= */
label{
    font-weight:600;
    font-size:14px;
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
    border-color:#d4af37;
    box-shadow:0 0 12px rgba(212,175,55,0.6);
    outline:none;
}

/* ================= BUTTON ================= */
button{
    width:100%;
    padding:14px;
    background:#d4af37;
    color:#0f223a;
    font-weight:bold;
    border:none;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s ease;
    letter-spacing:1px;
}

button:hover{
    background:#c39c2d;
    box-shadow:0 5px 20px rgba(212,175,55,0.7);
    transform:translateY(-3px);
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

/* ================= RETURN BUTTON ================= */
.return{
    text-align:center;
    margin-top:35px;
}

.return a{
    text-decoration:none;
    padding:14px 28px;
    background:#d4af37;
    color:#0f223a;
    border-radius:30px;
    font-weight:bold;
    transition:0.3s ease;
}

.return a:hover{
    background:#c39c2d;
    box-shadow:0 5px 20px rgba(212,175,55,0.7);
}

/* ================= FOOTER ================= */

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

<div class="ribbon">
    <h1>
        <img src="../images/logo.jpg">
        INDIAN ARMY IT ASSET MANAGEMENT
        <img src="../images/logo.jpg">
    </h1>
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

<label>Role</label>
<select name="role" required>
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

<div class="return">
    <a href="../dashboard.php">← Return to Dashboard</a>
</div>

</div>
</div>

</body>
</html>