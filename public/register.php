<?php $content = 'formContent.php'; include('master.php'); 
require_once '../controllers/SessionDestroyer.php';
?>
<!-- formContent.php -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .form-container {
        background: #ffffff;
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 100%;
    }

    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .form-container label {
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
        display: block;
    }

    .form-container input, 
    .form-container select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-container input:focus, 
    .form-container select:focus {
        border-color: #007BFF;
        outline: none;
    }

    .form-container button {
        width: 100%;
        padding: 10px;
        background-color: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    .form-container button:hover {
        background-color: #0056b3;
    }

    .form-container .footer {
        margin-top: 15px;
        text-align: center;
        font-size: 12px;
        color: #777;
    }
</style>

<div class="form-container">
    <h2><?php echo isset($_GET['action']) && $_GET['action'] == 'register' ? 'Register' : 'Login'; ?></h2>
    <?php if (isset($error)): ?>
        <div class="flash flash-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="../controllers/AuthController.php">
        <?php if (isset($_GET['action']) && $_GET['action'] == 'register'): ?>
            <label>Full Name:</label>
            <input type="text" name="fullname" placeholder="Enter your full name" required>
            <label>Role:</label>
            <select name="role_id" required>
                <option value="" disabled selected>Select a role</option>
                <option value="1">Teacher</option>
                <option default value="2">Student</option>
            </select>
        <?php endif; ?>
        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>
        <input type="hidden" name="action" value="<?php echo isset($_GET['action']) ? $_GET['action'] : 'login'; ?>">
        <button type="submit"><?php echo isset($_GET['action']) && $_GET['action'] == 'register' ? 'Register' : 'Login'; ?></button>
    </form>
    <div class="footer">
        <?php if (isset($_GET['action']) && $_GET['action'] == 'register'): ?>
            <p>Already have an account? <a href="?action=login" style="color: #007BFF;">Login here</a>.</p>
        <?php else: ?>
            <p>Don't have an account? <a href="?action=register" style="color: #007BFF;">Register here</a>.</p>
        <?php endif; ?>
    </div>
</div>
