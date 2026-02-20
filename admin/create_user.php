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
            ('CREATE','$full_name','$username','$password','$army_no','$rank','$requested_by','$today','Pending')
        ");

        // 🔥 Redirect directly to approval page
        header("Location: approve_user_requests.php?created=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Create User (Admin)</title>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:95%;
    max-width:700px;
    margin:40px auto;
}
.card{
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,.2);
}
h2{
    color:#2a5298;
    margin-bottom:20px;
}
label{
    font-weight:bold;
}
input, select{
    width:100%;
    padding:10px;
    margin-top:6px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ccc;
}
button{
    width:100%;
    padding:10px;
    background:#2a5298;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
button:hover{
    background:#1e3c72;
}
.success{
    background:#d4edda;
    color:#155724;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
}
.error{
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
}
</style>
</head>

<body>

<div class="container">
<div class="card">

<h2>Create User (Admin)</h2>

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
Create
</button>

</form>

</div>
</div>

</body>
</html>