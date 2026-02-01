<?php
session_start();
include("../config/db.php");

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE username='$username' AND password='$password'"
    );

    if (mysqli_num_rows($query) == 1) {

        $user = mysqli_fetch_assoc($query);
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // Redirect on success
        header("Location: ../dashboard.php");
        exit;

    } else {
        $message = "❌ Invalid username or password";
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
            width: 360px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            text-align: center;
        }

        .status-card h2 {
            margin-bottom: 15px;
            color: #2a5298;
        }

        .status-card p {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .error {
            color: #e74c3c;
            font-weight: bold;
        }

        .retry-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #2a5298;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .retry-btn:hover {
            background: #1e3c72;
        }
    </style>
</head>

<body>

<div class="status-card">
    <h2>Login Failed</h2>

    <p class="error"><?php echo $message; ?></p>

    <a href="../index.php" class="retry-btn">⬅ Try Again</a>
</div>

</body>
</html>
