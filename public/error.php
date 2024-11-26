<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Something Went Wrong</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            text-align: center;
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .error-container h1 {
            font-size: 3rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .error-container p {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }
        .error-container a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .error-container a:hover {
            text-decoration: underline;
        }
        .error-details {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Oops!</h1>
        <p>Something went wrong. Please try again later.</p>
        <a href="/">Go back to Home</a>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-details">
                <strong>Error Details:</strong>
                <p><?= htmlspecialchars($_SESSION['error_message']); ?></p>
            </div>
            <?php unset($_SESSION['error_message']); // Clear error message after displaying ?>
        <?php endif; ?>
    </div>
</body>
</html>
