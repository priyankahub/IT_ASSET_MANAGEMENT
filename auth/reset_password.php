<?php
include("../config/db.php");

$message = "";
$success = false;

if (isset($_POST['reset'])) {

    $username = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $message = "❌ Passwords do not match. Please re-enter.";
    } else {

        // Check if user exists
        $check = mysqli_query(
            $conn,
            "SELECT * FROM users WHERE username='$username'"
        );

        if (mysqli_num_rows($check) == 1) {

            mysqli_query(
                $conn,
                "UPDATE users SET password='$new_password'
                 WHERE username='$username'"
            );

            $message = "✅ Password reset successfully. Please login.";
            $success = true;

        } else {
            $message = "❌ Username not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reset-card {
            background: #ffffff;
            padding: 30px 35px;
            width: 360px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            text-align: center;
        }

        .reset-card h2 {
            margin-bottom: 10px;
            color: #2a5298;
        }

        .reset-card p {
            margin-bottom: 25px;
            color: #666;
            font-size: 14px;
        }

        .reset-card input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .reset-card input:focus {
            outline: none;
            border-color: #2a5298;
        }

        .reset-card button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #2a5298;
            color: white;
            font-size: 15px;
            cursor: pointer;
        }

        .reset-card button:hover {
            background: #1e3c72;
        }

        .msg {
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        .back-link {
            display: block;
            margin-top: 15px;
            font-size: 13px;
            color: #2a5298;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="reset-card">
    <h2>Reset Password</h2>
    <p>Enter your new credentials</p>

    <?php if ($message != "") { ?>
        <div class="msg <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <?php if (!$success) { ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="reset">Reset Password</button>
    </form>
    <?php } ?>

    <a href="../index.php" class="back-link">⬅ Back to Login</a>
</div>

</body>
</html>
