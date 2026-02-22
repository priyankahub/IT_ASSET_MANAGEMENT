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

            $_SESSION['role']     = $user['role'];
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

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">

<style>

/* ===== BACKGROUND ===== */
body{
    margin:0;
    height:100vh;
    font-family:'Montserrat',sans-serif;
    background: radial-gradient(circle at top,#0d1b2a,#000814 75%);
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

/* Animated light effect */
body::before{
    content:"";
    position:absolute;
    width:700px;
    height:700px;
    background:radial-gradient(circle,#1b263b,transparent 70%);
    top:-200px;
    right:-200px;
    animation: moveGlow 8s infinite alternate ease-in-out;
}

@keyframes moveGlow{
    from{transform:translate(0,0);}
    to{transform:translate(-80px,60px);}
}

/* ===== HEADER ===== */
.main-header{
    position:absolute;
    top:0;
    width:100%;
    height:100px;
    background:#0b1f3a;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 40px;
}

.header-side img{
    width:80px;
}

/* PROFESSIONAL TITLE */
.header-title{
    flex:1;
    text-align:center;
    color:#e6edf3;
    font-size:28px;
    font-weight:600;
    letter-spacing:1px;
}

/* Subtle divider strip */
.gold-strip{
    position:absolute;
    top:100px;
    width:100%;
    height:3px;
    background:#1f3a5f;
}

/* ===== LOGIN CARD ===== */
.login-card{
    margin-top:170px;
    background:rgba(13,27,42,0.95);
    padding:60px 50px;
    width:480px;
    border-radius:16px;
    box-shadow:0 30px 60px rgba(0,0,0,0.7);
    text-align:center;
    border:1px solid rgba(255,255,255,0.05);
    backdrop-filter:blur(15px);
    animation:fadeIn 0.8s ease;
    transition:all 0.35s ease;
    position:relative;
    overflow:hidden;
}

/* Hover Lift + Glow */
.login-card:hover{
    transform:translateY(-12px) scale(1.02);
    box-shadow:
        0 0 30px rgba(77,163,255,0.5),
        0 35px 70px rgba(0,0,0,0.8);
}

/* Subtle sweep animation */
.login-card::before{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:100%;
    background:linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.06),
        transparent
    );
    transition:0.6s;
}

.login-card:hover::before{
    left:100%;
}

@keyframes fadeIn{
    from{opacity:0;transform:translateY(20px);}
    to{opacity:1;transform:translateY(0);}
}

h2{
    margin-bottom:20px;
    color:#ffffff;
    font-size:24px;
    font-weight:600;
}

input{
    width:100%;
    padding:14px;
    margin:14px 0;
    border-radius:12px;
    border:1px solid #1f3a5f;
    background:#162a46;
    color:#fff;
    font-size:14px;
    transition:all 0.3s ease;
    position:relative;
}

/* Hover Glow */
input:hover{
    border-color:#3b82f6;
    box-shadow:0 0 12px rgba(77,163,255,0.3);
}

/* Focus Premium Effect */
input:focus{
    border-color:#4da3ff;
    box-shadow:
        0 0 15px rgba(77,163,255,0.6),
        0 0 25px rgba(77,163,255,0.2);
    background:#1c3557;
    transform:scale(1.02);
    outline:none;
}

button{
    width:100%;
    padding:14px;
    margin-top:18px;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;
    border:none;
    border-radius:12px;
    cursor:pointer;
    font-weight:600;
    font-size:15px;
    transition:all 0.3s ease;
    position:relative;
    overflow:hidden;
}

/* Hover Lift + Glow */
button:hover{
    transform:translateY(-5px);
    box-shadow:
        0 0 20px rgba(77,163,255,0.6),
        0 10px 25px rgba(0,0,0,0.6);
}

/* Click Press Effect */
button:active{
    transform:scale(0.96);
}

/* Links */
.links{
    margin-top:35px;
    display:flex;
    justify-content:center;
    gap:20px;
}

/* Button Style Links */
.links a{
    text-decoration:none;
    padding:10px 20px;
    background:rgba(37,99,235,0.15);
    border:1px solid rgba(77,163,255,0.4);
    border-radius:25px;
    color:#ffffff;
    font-weight:500;
    font-size:14px;
    transition:all 0.3s ease;
    position:relative;
    overflow:hidden;
}

/* Hover Popup Effect */
.links a:hover{
    transform:translateY(-5px);
    background:#2563eb;
    box-shadow:
        0 0 15px rgba(77,163,255,0.6),
        0 8px 20px rgba(0,0,0,0.6);
}

/* Click effect */
.links a:active{
    transform:scale(0.95);
}

.error{
    color:#ff6b6b;
    font-weight:500;
    margin-bottom:12px;
}

.footer{
    position:absolute;
    bottom:15px;
    font-size:12px;
    color:#aaa;
}

</style>
</head>

<body>

<!-- HEADER -->
<div class="main-header">

    <div class="header-side">
        <img src="../images/logo.jpg">
    </div>

    <div class="header-title">
        IT Equipment Lifecycle Management Portal
    </div>

    <div class="header-side">
        <img src="../images/logo.jpg">
    </div>

</div>

<div class="gold-strip"></div>

<div class="login-card">

<h2>User Login</h2>

<?php if (!empty($message)) { ?>
    <div class="error"><?php echo $message; ?></div>
<?php } ?>

<div id="jaiMessage"></div>

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