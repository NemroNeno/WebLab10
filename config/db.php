<?php

try {
    // Database connection details
$host = 'lallah.db.elephantsql.com';
$dbname = 'wadekgen';
$username = 'wadekgen';
$password = 'WeKH6WAEW_9pQiE59i9lMDEUKMIEqK-d';

    $myPDO = new PDO('pgsql:host=lallah.db.elephantsql.com;dbname=wadekgen', 'wadekgen', 'WeKH6WAEW_9pQiE59i9lMDEUKMIEqK-d');
    $myPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
