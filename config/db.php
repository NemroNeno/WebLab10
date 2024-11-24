<?php
class Database {
    private static $host = 'localhost'; // XAMPP default host
    private static $dbName = 'weblab'; // Replace with your database name
    private static $username = 'root'; // XAMPP default MySQL username
    private static $password = ''; // XAMPP default MySQL password is empty

    public static function connect() {
        try {
            // Create a PDO instance
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbName . ";charset=utf8";
            $conn = new PDO($dsn, self::$username, self::$password);

            // Set PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }
}

// Test the connection
Database::connect();
?>
