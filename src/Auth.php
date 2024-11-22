<?php
// src/Auth.php
session_start();

class Auth {
    public static function login($username, $password) {
        // Implement login logic
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