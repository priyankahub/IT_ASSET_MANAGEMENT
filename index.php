<!DOCTYPE html>
<html>
<head>
    <title>IT Equipment Life Cycle Management System</title>

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

        .login-card {
            background: #ffffff;
            padding: 30px 35px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
            text-align: center;
        }

        .login-card h2 {
            margin-bottom: 10px;
            color: #2a5298;
        }

        .login-card p {
            margin-bottom: 25px;
            color: #666;
            font-size: 14px;
        }

        .login-card input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .login-card input:focus {
            outline: none;
            border-color: #2a5298;
        }

        .login-card button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #2a5298;
            color: white;
            font-size: 15px;
            cursor: pointer;
        }

        .login-card button:hover {
            background: #1e3c72;
        }

        .forgot-link {
            margin-top: 12px;
            display: block;
            font-size: 13px;
            color: #2a5298;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .footer-text {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>

<div class="login-card">
    <h2>IT Asset Management</h2>
    <p>Secure Login Portal</p>

    <form method="POST" action="auth/login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <!-- Forgot Password -->
    <a href="auth/reset_password.php" class="forgot-link">
        Forgot Password?
    </a>

    <div class="footer-text">
        IT Equipment Life Cycle Management System
    </div>
</div>

</body>
</html>
