<?php $content = 'loginContent.php'; include('master.php'); ?>
<!-- loginContent.php -->
<style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background: #ffffff;
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 100%;
    }

    .login-container h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .login-container label {
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
        display: block;
    }

    .login-container input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .login-container input:focus {
        border-color: #007BFF;
        outline: none;
    }

    .login-container button {
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    .login-container button:hover {
        background-color: #0056b3;
    }

    .login-container .footer {
        margin-top: 15px;
        text-align: center;
        font-size: 12px;
        color: #777;
    }
</style>

<div class="login-container">
    <h2>Login</h2>
    <form method="POST" action="../controllers/AuthController.php">
        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Login</button>
    </form>
    <div class="footer">
        <p>Don't have an account? <a href="register.php" style="color: #007BFF;">Register here</a>.</p>
    </div>
</div>
