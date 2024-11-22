<?php
$host = 'lallah.db.elephantsql.com';  // e.g., stampy.db.elephantsql.com
$port = '5432';                  // Default PostgreSQL port
$dbname = 'wadekgen';
$user = 'wadekgen';
$password = 'WeKH6WAEW_9pQiE59i9lMDEUKMIEqK-d';

try {
    $conn = new PDO("postgres://wadekgen:WeKH6WAEW_9pQiE59i9lMDEUKMIEqK-d@lallah.db.elephantsql.com/wadekgen");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connected successfully!";  // For debugging only
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
