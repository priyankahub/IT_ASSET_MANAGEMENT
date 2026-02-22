<?php
include("../config/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $army_no  = mysqli_real_escape_string($conn, $_POST['army_no']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    if (!preg_match('/^(?=.*[A-Z])(?=.*[\W_]).{6,20}$/', $password)) {
        $message = "❌ Password must be 6-20 chars, include 1 Capital & 1 Special character.";
    }
    else {

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
                ('CREATE','$name','$username','$password','$army_no','$role','SELF',CURDATE(),'Pending')
            ");

            $message = "✅ Registration request sent for Admin approval.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Indian Army | Secure Registration</title>

<style>

/* ============ BACKGROUND ============ */
body{
    margin:0;
    height:100vh;
    font-family:'Segoe UI',sans-serif;
    background: radial-gradient(circle at top,#0d1b2a,#000814);
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

/* Subtle animated glow */
body::before{
    content:"";
    position:absolute;
    width:600px;
    height:600px;
    background: radial-gradient(circle,#1b263b,transparent 70%);
    top:-200px;
    right:-200px;
    animation: glowMove 8s infinite alternate ease-in-out;
}

@keyframes glowMove{
    from{transform:translate(0,0);}
    to{transform:translate(-60px,40px);}
}

/* ============ CARD ============ */
.card{
    position:relative;
    background:rgba(13,27,42,0.95);
    padding:45px 40px;
    width:460px;
    border-radius:20px;
    box-shadow:0 0 40px rgba(0,0,0,0.9);
    border:1px solid rgba(65,105,225,0.4);
    text-align:center;
    backdrop-filter: blur(15px);
    animation: fadeIn 1s ease;
    transition:all 0.35s ease;
    overflow:hidden;
}

/* Hover Lift + Glow */
.card:hover{
    transform:translateY(-12px) scale(1.02);
    box-shadow:
        0 0 35px rgba(77,163,255,0.5),
        0 40px 80px rgba(0,0,0,0.9);
}

/* Sweep animation */
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
        rgba(255,255,255,0.06),
        transparent
    );
    transition:0.6s;
}

.card:hover::before{
    left:100%;
}

@keyframes fadeIn{
    from{opacity:0;transform:translateY(20px);}
    to{opacity:1;transform:translateY(0);}
}

/* ============ LOGO ============ */
.logo{
    width:90px;
    margin-bottom:15px;
    filter: drop-shadow(0 0 10px #4da3ff);
}

/* ============ HEADER TEXT ============ */
h2{
    color:#4da3ff;
    margin-bottom:25px;
    letter-spacing:1px;
    font-weight:600;
    text-transform:uppercase;
}

/* ============ INPUTS ============ */
input,select{
    width:100%;
    padding:13px;
    margin:12px 0;
    border-radius:12px;
    border:1px solid rgba(65,105,225,0.3);
    background:#1b263b;
    color:#fff;
    font-size:14px;
    transition:all 0.3s ease;
}

/* Hover effect */
input:hover,
select:hover{
    border-color:#3b82f6;
    box-shadow:0 0 12px rgba(77,163,255,0.3);
}

/* Focus effect */
input:focus,
select:focus{
    outline:none;
    border-color:#4da3ff;
    box-shadow:
        0 0 15px rgba(77,163,255,0.7),
        0 0 25px rgba(77,163,255,0.3);
    background:#213654;
    transform:scale(1.02);
}

/* ============ BUTTON ============ */
button{
    width:100%;
    padding:14px;
    margin-top:12px;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:#fff;
    border:none;
    border-radius:12px;
    font-weight:bold;
    letter-spacing:1px;
    cursor:pointer;
    transition:all 0.3s ease;
    position:relative;
    overflow:hidden;
}

/* Hover Lift + Glow */
button:hover{
    transform:translateY(-5px);
    box-shadow:
        0 0 25px rgba(77,163,255,0.6),
        0 15px 35px rgba(0,0,0,0.7);
}

/* Click Press */
button:active{
    transform:scale(0.96);
}

/* ============ MESSAGE ============ */
.success{
    color:#00ffaa;
    font-weight:bold;
    margin-bottom:12px;
}
.error{
    color:#ff4d6d;
    font-weight:bold;
    margin-bottom:12px;
}

/* ============ LINK ============ */
a{
    color:#4da3ff;
    text-decoration:none;
    font-weight:600;
}

a:hover{
    text-decoration:underline;
}

/* ============ FOOTER TEXT ============ */
.footer-text{
    font-size:12px;
    color:#8faed6;
    margin-top:18px;
    letter-spacing:0.5px;
}

</style>
</head>

<body>

<div class="card">

<img src="../images/indian_army_logo.png" class="logo" alt="Indian Army Logo">

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

    <select name="role" required>
        <option value="">Select Role</option>
        <option value="USER">USER</option>
        <option value="CLERK">CLERK</option>
        <option value="ITJCO">ITJCO</option>
        <option value="CO">CO</option>
        <option value="ADMIN">ADMIN</option>
    </select>

    <button type="submit">REGISTER</button>
</form>

<br>
<a href="../index.php">⬅ Back to Login</a>

<div class="footer-text">
    Secured Registration Portal • Indian Army IT Asset Management
</div>

</div>

</body>
</html>