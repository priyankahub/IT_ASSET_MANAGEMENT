<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$message = "";
$message_color = "#ff4d4d"; // default red

// Fetch current password
$result = mysqli_query($conn, "SELECT password FROM users WHERE username='$username'");
$row = mysqli_fetch_assoc($result);
$current_password = $row['password'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Password Pattern
    $pattern = "/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{6,20}$/";

    if ($new_pass !== $confirm_pass) {
        $message = "Passwords do not match!";
    }
    elseif (!preg_match($pattern, $new_pass)) {
        $message = "Password must contain 1 uppercase, 1 number, 1 special character & 6-20 characters.";
    }
    else {
        mysqli_query($conn, "UPDATE users SET password='$new_pass' WHERE username='$username'");
        $message = "Password Updated Successfully!";
        $message_color = "#00ff99";
        $current_password = $new_pass;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Password Management</title>

<style>

body{
    margin:0;
    padding:0;
    min-height:100vh;
    font-family:'Segoe UI',sans-serif;
    background:
        radial-gradient(circle at top left,#0f2027,#203a43 60%,#0a1923);
    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
}

.card{
    width:500px;
    background:rgba(10,25,40,0.95);
    padding:45px;
    border-radius:20px;
    box-shadow:
        0 0 30px rgba(0,198,255,0.5),
        0 30px 70px rgba(0,0,0,0.8);
    border:1px solid rgba(0,198,255,0.3);
}

.card h2{
    text-align:center;
    margin-bottom:25px;
    letter-spacing:1px;
}

input{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border-radius:10px;
    border:1px solid rgba(0,198,255,0.4);
    background:#102a3a;
    color:white;
    font-size:14px;
}

input:focus{
    outline:none;
    border-color:#00c6ff;
    box-shadow:0 0 10px rgba(0,198,255,0.5);
}

button{
    width:100%;
    padding:14px;
    background:#00c6ff;
    border:none;
    border-radius:25px;
    font-weight:bold;
    cursor:pointer;
    margin-top:10px;
    transition:0.3s;
}

button:hover{
    background:#009dcf;
}

.message{
    text-align:center;
    margin-bottom:15px;
    font-weight:bold;
}

.criteria{
    font-size:12px;
    margin-bottom:15px;
    color:#00c6ff;
}

.back{
    text-align:center;
    margin-top:20px;
}

.back a{
    background:#00c6ff;
    padding:10px 25px;
    border-radius:25px;
    text-decoration:none;
    color:#001f54;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="card">
    <h2>Password Management</h2>

    <p><strong>Current Password:</strong> <?php echo $current_password; ?></p>

    <?php if($message!=""){ ?>
        <div class="message" style="color:<?php echo $message_color; ?>">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <div class="criteria">
        Password must contain:
        <br>• 1 Uppercase Letter
        <br>• 1 Number
        <br>• 1 Special Character
        <br>• Length 6-20 characters
    </div>

    <form method="POST">
        <input type="password" name="new_password" placeholder="Enter New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        <button type="submit">Update Password</button>
    </form>

    <div class="back">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>

</div>

</body>
</html>