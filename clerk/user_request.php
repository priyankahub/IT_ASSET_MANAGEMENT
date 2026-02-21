<?php
session_start();
include("../config/db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'CLERK') {
    die("Access Denied");
}

$message = "";

if (isset($_POST['submit_request'])) {

    $request_type = "CREATE";
    $full_name    = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $army_no      = mysqli_real_escape_string($conn, $_POST['army_no']);
    $rank         = mysqli_real_escape_string($conn, $_POST['rank']);
    $requested_by = $_SESSION['username'];
    $today        = date('Y-m-d');

    // Default password
    $password = "Password@#123";

    // Username uniqueness
    $checkUser = mysqli_query($conn,"
        SELECT id FROM users WHERE username='$username'
        UNION
        SELECT id FROM user_requests WHERE username='$username'
    ");

    // Army No uniqueness
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
            ('$request_type','$full_name','$username','$password','$army_no','$rank','$requested_by','$today','Pending')
        ");

        // Activity Log Entry
        mysqli_query($conn,"
            INSERT INTO activity_logs
            (user_username, action_tag, description)
            VALUES
            ('{$_SESSION['username']}',
            'New User Creation',
            'Requested CREATE for user: $full_name (Army No: $army_no)')
        ");

        $message = "✅ Request sent to Admin for approval.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Management Request</title>

<style>

/* ================= BODY ================= */
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#0f223a;
    color:#f5f7fa;
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
    color:#ffffff;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:18px;
}

.ribbon img{
    height:45px;
}

/* ================= CONTAINER ================= */
.container{
    width:95%;
    max-width:950px;
    margin:50px auto;
}

/* ================= CARD ================= */
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    border-left:4px solid #d4af37;
    margin-bottom:30px;
    transition:0.35s ease;
}

.card:hover{
    background:#1d3a5c;
    box-shadow:0 15px 40px rgba(0,0,0,0.6);
}

/* ================= FORM ================= */
h2{
    margin-bottom:20px;
    font-weight:600;
}

label{
    font-size:13px;
    font-weight:600;
    color:#b8c6db;
}

input, select{
    width:100%;
    padding:10px;
    margin-top:6px;
    margin-bottom:15px;
    border-radius:4px;
    border:1px solid #2c4c73;
    background:#0f223a;
    color:#ffffff;
}

input:focus, select:focus{
    border-color:#d4af37;
    outline:none;
}

button{
    width:100%;
    padding:12px;
    background:#d4af37;
    color:#0f223a;
    border:none;
    border-radius:4px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#c39c2d;
}

/* ================= MESSAGE ================= */
.success{
    background:#1e4d2b;
    color:#a8e6a1;
    padding:12px;
    border-radius:4px;
    margin-bottom:20px;
}

/* ================= FOOTER ================= */
.footer-actions{
    text-align:center;
    margin-top:30px;
}

.footer-actions a{
    text-decoration:none;
    padding:12px 24px;
    background:#d4af37;
    color:#0f223a;
    border-radius:6px;
    font-weight:600;
    margin:0 10px;
}

.footer-actions a:hover{
    background:#c39c2d;
}

</style>
</head>

<body>

<!-- Ribbon Header -->
<div class="ribbon">
    <h1>
        <img src="../images/logo.jpg" alt="Logo">
        User Management Request
        <img src="../images/logo.jpg" alt="Logo">
    </h1>
</div>

<div class="container">

<div class="card">

<h2>New User Creation Request</h2>

<?php if($message!=""){ ?>
<div class="success"><?php echo $message; ?></div>
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
Submit Request
</button>

</form>

</div>

<div class="footer-actions">
<a href="../dashboard.php">⬅ Back to Dashboard</a>
<a href="../logout.php">Logout</a>
</div>

</div>

</body>
</html>