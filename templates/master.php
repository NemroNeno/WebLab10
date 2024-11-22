<!-- templates/master.php -->
<?php
require_once '../src/Auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Attendance Management System</h1>
        <?php if (Auth::check()): ?>
            <p>Welcome, <?= Auth::user()['username'] ?> | <a href="logout.php">Logout</a></p>
        <?php endif; ?>
    </header>
    <main>
        <?php include $view; ?>
    </main>
</body>
</html>