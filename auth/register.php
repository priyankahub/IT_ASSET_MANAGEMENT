<?php
include("../config/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $army_no  = mysqli_real_escape_string($conn, $_POST['army_no']);
    $rank     = mysqli_real_escape_string($conn, $_POST['rank']);

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
    border-radius:10px;
    border:1px solid rgba(65,105,225,0.3);
    background:#1b263b;
    color:#fff;
    font-size:14px;
    transition:0.3s;
}

input::placeholder{
    color:#8faed6;
}

input:focus,select:focus{
    outline:none;
    border-color:#4da3ff;
    box-shadow:0 0 15px #4da3ff;
}

/* ============ BUTTON ============ */
button{
    width:100%;
    padding:14px;
    background:linear-gradient(90deg,#1f3a5f,#2f5e99);
    color:#fff;
    border:none;
    border-radius:10px;
    font-weight:bold;
    letter-spacing:1px;
    cursor:pointer;
    transition:0.3s;
    margin-top:10px;
}

button:hover{
    transform:scale(1.05);
    box-shadow:0 0 20px #2f5e99;
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

    <select name="rank" required>
        <option value="">Select Rank</option>
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