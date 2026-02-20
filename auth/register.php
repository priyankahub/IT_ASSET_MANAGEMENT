<?php
include("../config/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $army_no  = mysqli_real_escape_string($conn, $_POST['army_no']);
    $rank     = mysqli_real_escape_string($conn, $_POST['rank']);

    // Password Policy
    if (!preg_match('/^(?=.*[A-Z])(?=.*[\W_]).{6,20}$/', $password)) {
        $message = "❌ Password must be 6-20 chars, include 1 Capital & 1 Special character.";
    }
    else {

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
                ('CREATE','$name','$username','$password','$army_no','$rank','SELF',CURDATE(),'Pending')
            ");

            $message = "✅ Registration request sent for Admin approval.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Account</title>

<style>
body{
    margin:0;
    height:100vh;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
    display:flex;
    align-items:center;
    justify-content:center;
}
.card{
    background:#fff;
    padding:30px;
    width:400px;
    border-radius:10px;
    box-shadow:0 8px 20px rgba(0,0,0,.25);
    text-align:center;
}
h2{color:#2a5298;}
input,select{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:5px;
    border:1px solid #ccc;
}
button{
    width:100%;
    padding:10px;
    background:#2a5298;
    color:#fff;
    border:none;
    border-radius:5px;
    cursor:pointer;
}
button:hover{
    background:#1e3c72;
}
.success{color:green;font-weight:bold;}
.error{color:#e74c3c;font-weight:bold;}
a{color:#2a5298;text-decoration:none;font-weight:bold;}
</style>
</head>

<body>

<div class="card">
<h2>New User - Self Registration</h2>

<?php if ($message!="") { ?>
<p class="<?php echo str_contains($message,'✅')?'success':'error'; ?>">
<?php echo $message; ?>
</p>
<?php } ?>

<form method="POST">

    <input type="text" name="name" placeholder="Full Name" required>

    <input type="text" name="username" placeholder="Username" required>

    <input type="password" name="password" placeholder="Password" required>

    <input type="text" name="army_no" placeholder="Army Number" required>

    <select name="rank" required>
        <option value="">Select Rank</option>
        <option value="USER">USER</option>
        <option value="CLERK">CLERK</option>
        <option value="ITJCO">ITJCO</option>
        <option value="CO">CO</option>
        <option value="ADMIN">ADMIN</option>
    </select>

    <button type="submit">Register</button>
</form>

<br>
<a href="../index.php">⬅ Back to Login</a>

</div>

</body>
</html>