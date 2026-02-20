<?php
session_start();
include("../config/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn,
        "SELECT * FROM users WHERE username='$username'"
    );

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);

        if ($password === $user['password']) {

            $_SESSION['rank']     = $user['rank'];
            $_SESSION['name']     = $user['name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['army_no']  = $user['army_no'];

            header("Location: ../dashboard.php");
            exit;

        } else {
            $message = "❌ Invalid username or password";
        }

    } else {

        $checkPending = mysqli_query($conn,
            "SELECT id FROM user_requests 
             WHERE username='$username' AND status='Pending'"
        );

        if (mysqli_num_rows($checkPending) == 1) {
            $message = "⏳ Your account is pending Admin approval.";
        } else {
            $message = "❌ Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login | THE INFANTRY SCHOOL MHOW</title>

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

.login-card{
    background:#fff;
    padding:35px;
    width:400px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.25);
    text-align:center;
}

.login-card h2{
    margin-bottom:20px;
    color:#2a5298;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
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
    font-weight:bold;
}

button:hover{
    background:#1e3c72;
}

.error{
    color:#e74c3c;
    font-weight:bold;
    margin-bottom:10px;
}

.success{
    color:#155724;
    font-weight:bold;
    margin-bottom:10px;
}

.links{
    margin-top:15px;
}

.links a{
    text-decoration:none;
    color:#2a5298;
    font-weight:bold;
    margin:0 8px;
}

.links a:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="login-card">

<h2>User Login</h2>

<?php if (!empty($message)) { ?>
    <div class="error"><?php echo $message; ?></div>
<?php } ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<div class="links">
    <a href="../index.php">⬅ Back to Home</a> |
    <a href="register.php">New User? Create Account</a>
</div>

</div>

</body>
</html>