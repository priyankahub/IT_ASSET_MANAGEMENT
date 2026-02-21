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
<title>Login | IT EQUIPMENT Lifecycle Management Portal</title>

<style>
body{
    margin:0;
    height:100vh;
    font-family:'Segoe UI',sans-serif;
    background:radial-gradient(circle at center,#0a1f44 0%,#000814 80%);
    display:flex;
    align-items:center;
    justify-content:center;
}

/* ===== CEREMONIAL HEADER ===== */
.main-header{
    position:absolute;
    top:0;
    width:100%;
    height:110px;
    background:#001f54;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 40px;
}

.header-side{
    background:transparent;   /* removes red */
    height:100%;
    width:120px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.header-side img{
    width:90px;
}

.header-title{
    flex:1;
    text-align:center;
    color:#FFD700;
    font-size:42px;
    font-weight:800;
    letter-spacing:4px;
}

.gold-strip{
    position:absolute;
    top:110px;
    width:100%;
    height:6px;   /* thinner line */
    background:#FFD700;
}

/* ===== LOGIN CARD ===== */
.login-card{
    margin-top:190px;
    background:#f4f4f4;
    padding:55px 50px;
    width:480px;
    border-radius:16px;
    box-shadow:0 30px 60px rgba(0,0,0,0.6);
    text-align:center;
    border-top:6px solid #FFD700;
}

.logo{
    width:120px;
    margin-bottom:20px;
}

h2{
    margin-bottom:8px;
    color:#001d3d;
    font-size:30px;
}

.subtitle{
    font-size:14px;
    color:#555;
    margin-bottom:30px;
}

input{
    width:100%;
    padding:16px;
    margin:12px 0;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:14px;
    transition:0.3s;
}

input:focus{
    border-color:#FFD700;
    box-shadow:0 0 12px rgba(255,215,0,0.6);
    outline:none;
}

button{
    width:100%;
    padding:16px;
    margin-top:12px;
    background:#FFD700;
    color:#000;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    font-size:16px;
    transition:0.3s;
}

button:hover{
    background:#e6c200;
    transform:scale(1.03);
}

#jaiMessage{
    display:none;
    font-size:20px;
    font-weight:bold;
    color:#138808;
    margin-top:20px;
}

.links{
    margin-top:30px;
}

.links a{
    text-decoration:none;
    color:#001d3d;
    font-weight:bold;
    margin:0 10px;
}

.links a:hover{
    color:#FFD700;
}

.error{
    color:#c0392b;
    font-weight:bold;
    margin-bottom:10px;
}

.footer{
    position:absolute;
    bottom:15px;
    font-size:13px;
    color:#aaa;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="main-header">

    <div class="header-side">
        <img src="../images/militartyschoo_logo.png">
    </div>

    <div class="header-title">
        IT EQUIPMENT Lifecycle Management Portal
    </div>

    <div class="header-side">
        <img src="../images/militartyschoo_logo.png">
    </div>

</div>

<div class="gold-strip"></div>

<div class="login-card">

<h2>User Login</h2>

<?php if (!empty($message)) { ?>
    <div class="error"><?php echo $message; ?></div>
<?php } ?>

<div id="jaiMessage">
</div>

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

<script>
document.querySelector("form").addEventListener("submit", function(e) {
    e.preventDefault();

    const msg = document.getElementById("jaiMessage");
    msg.style.display = "block";

    setTimeout(() => {
        e.target.submit();
    }, 1500);
});
</script>

</body>
</html>