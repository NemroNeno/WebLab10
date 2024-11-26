<?php
session_set_cookie_params(['path' => '/', 'domain' => 'localhost', 'secure' => false, 'httponly' => true]);
session_start();
require_once '../config/db.php';

// Validate session data
if (!isset($_SESSION['userId'], $_SESSION['roleId'])) {
    echo "Unset";
    // header('Location: ../public/register.php');
    exit;
}

$current_userId = $_SESSION['userId'];
$current_roleId = $_SESSION['roleId'];
$current_fullname = "";
$current_email = "";

try {
    // Fetch user details
    $query = 'SELECT fullname, email FROM users WHERE id = :id';
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $current_userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $current_fullname = $row['fullname'];
        $current_email = $row['email'];
    } else {
        // User not found
        header('Location: ../public/error.php'); // Or handle as needed
        exit;
    }
} catch (PDOException $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage());
    header('Location: ../public/error.php');
    exit;
}
?>
