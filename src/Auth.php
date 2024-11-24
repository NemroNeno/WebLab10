<?php
require_once '../models/UserModel.php';

class Auth {
    public static function login($email, $password) {
        $user = UserModel::authenticate($email, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            return true;
        } else {
            return true;
        }
    }

    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function user() {
        return $_SESSION['user'] ?? null;
    }

    public static function logout() {
        session_destroy();
    }
}
?>