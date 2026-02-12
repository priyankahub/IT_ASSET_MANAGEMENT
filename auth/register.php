<?php
include("../config/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $check = mysqli_query($conn,
        "SELECT * FROM users WHERE username='$username'"
    );

    if (mysqli_num_rows($check) > 0) {
        $message = "❌ Username already exists";
    } else {
        mysqli_query($conn,"
            INSERT INTO users (name, username, password, role)
            VALUES ('$name','$username','$password','USER')
        ");
        $message = "✅ Account created successfully";
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
    width:380px;
    border-radius:10px;
    box-shadow:0 8px 20px rgba(0,0,0,.25);
    text-align:center;
}
h2{color:#2a5298;}
input{
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
}
.success{color:green;font-weight:bold;}
.error{color:#e74c3c;font-weight:bold;}
a{color:#2a5298;text-decoration:none;font-weight:bold;}
</style>
</head>

<body>

<div class="card">
<h2>Create New Account</h2>

<?php if ($message!="") { ?>
<p class="<?php echo str_contains($message,'✅')?'success':'error'; ?>">
<?php echo $message; ?>
</p>
<?php } ?>

<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>

<br>
<a href="../index.php">⬅ Back to Login</a>

</div>

</body>
</html>
