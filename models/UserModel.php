<?php
require '../config/db.php';

class UserModel {
    public static function authenticate($email, $password) {
        $conn = Database::connect();
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // Authentication successful
        } else {
            return false; // Authentication failed
        }
    }
}
?>
