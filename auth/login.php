<?php
session_start();
include("../config/db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE username='$username'"
    );

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);

        if ($password === $user['password']) {
            $_SESSION['rank'] = $user['rank'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['army_no'] = $user['army_no'];

            header("Location: ../dashboard.php");
            exit;

        } else {
            $message = "❌ Invalid username or password";
        }

    } else {

        $checkPending = mysqli_query(
            $conn,
            "SELECT id FROM user_requests WHERE username='$username' AND status='Pending'"
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
    <title>Login Status</title>

    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-card {
            background: #ffffff;
            padding: 30px 35px;
            width: 380px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            text-align: center;
        }

        .status-card h2 {
            margin-bottom: 15px;
            color: #2a5298;
        }

        .error {
            color: #e74c3c;
            font-weight: bold;
        }

        .retry-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 15px;
            background: #2a5298;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .retry-btn:hover {
            background: #1e3c72;
        }
    </style>
</head>

<body>

<div class="status-card">
    <h2>Login Status</h2>

    <?php if (!empty($message)) { ?>
        <p class="error"><?php echo $message; ?></p>
        <a href="../index.php" class="retry-btn">⬅ Try Again</a>
    <?php } ?>

</div>

</body>
</html>