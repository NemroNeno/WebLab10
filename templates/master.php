<?php
require_once '../src/Auth.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NUST Attendance Management System">
    <title>NUST - Attendance Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #007BFF;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f6f9;
        }

        .header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--secondary-color);
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            min-height: calc(100vh - 180px);
        }

        .flash-messages {
            margin: 1rem 0;
        }

        .flash {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .flash-success { background-color: #d4edda; color: #155724; }
        .flash-error { background-color: #f8d7da; color: #721c24; }
        .flash-warning { background-color: #fff3cd; color: #856404; }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 2rem 0;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            padding: 0 1rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="logo">
                <i class="fas fa-university"></i> Attendance Management System
            </div>
            <div class="nav-links">
                <?php if (Auth::check()): ?>
                    <?php if (Auth::user()['role_id'] == 1): // Teacher ?>
                        <a href="/teacher/dashboard"><i class="fas fa-home"></i> Dashboard</a>
                        <a href="/teacher/sessions"><i class="fas fa-calendar"></i> Sessions</a>
                        <a href="/teacher/reports"><i class="fas fa-chart-bar"></i> Reports</a>
                    <?php else: // Student ?>
                        <a href="/student/dashboard"><i class="fas fa-home"></i> Dashboard</a>
                        <a href="/student/attendance"><i class="fas fa-check-circle"></i> My Attendance</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/public/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                <?php endif; ?>
            </div>
            <?php if (Auth::check()): ?>
                <div class="user-info">
                    <span><i class="fas fa-user"></i> <?= htmlspecialchars(Auth::user()['username']) ?></span>
                    <a href="/logout" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            <?php endif; ?>
        </nav>
    </header>

    <div class="main-content">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash-messages">
                <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                    <div class="flash flash-<?= $type ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endforeach; ?>
                <?php unset($_SESSION['flash']); ?>
            </div>
        <?php endif; ?>

        <?php include $view; ?>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div>
                <p>&copy; <?= date('Y') ?> NUST Attendance Management System</p>
            </div>
            <div>
                <p>Contact: support@nust.edu.pk</p>
            </div>
        </div>
    </footer>

    <script>
        // Auto-hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const flashMessages = document.querySelector('.flash-messages');
                if (flashMessages) {
                    flashMessages.style.display = 'none';
                }
            }, 5000);
        });
    </script>
</body>
</html>