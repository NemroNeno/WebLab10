<?php
require '../config/db.php';

class UserModel {
    public static function authenticate($email, $password) {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM \"user\" WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
