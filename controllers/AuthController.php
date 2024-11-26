<?php
session_set_cookie_params(['path' => '/', 'domain' => 'localhost', 'secure' => false, 'httponly' => true]);
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check for missing required fields
    if (empty($action) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit;
    }

    // Database connection
    require_once '../config/db.php';

    if ($action === 'register') {
        $fullname = $_POST['fullname'] ?? '';
        $role_id = $_POST['role_id'] ?? '';

        // Check if all fields are filled
        if (empty($fullname) || empty($role_id)) {
            echo "<script>alert('All fields are required for registration!'); window.history.back();</script>";
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Check if email already exists
            $query = "SELECT id FROM users WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->fetch()) {
                echo "<script>alert('Email is already registered! Please use a different email.'); window.history.back();</script>";
                exit;
            }

            // Insert new user
            $query = "INSERT INTO users (fullname, email, password, role_id) VALUES (:fullname, :email, :password, :role_id)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':role_id', $role_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!'); window.location.href = '../public/register.php';</script>";
                exit;
            } else {
                echo "<script>alert('Registration failed. Please try again.'); window.history.back();</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        }

    } elseif ($action === 'login') {
        try {
            session_unset();
            // Fetch user details for the given email
            $query = "SELECT id, password, role_id FROM users WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Verify password
                if (password_verify($password, $row['password'])) {
                    echo "Password verified. Logging in...<br>";
                    // Redirect based on role
                    $_SESSION['userId'] = $row['id'];
                    $_SESSION['roleId'] = $row['role_id'];
                    if ($row['role_id'] == 1) {
                        header("Location: ../public/teacher.php");
                    } elseif ($row['role_id'] == 2) {
                        header("Location: ../public/student.php");
                    } else {
                        echo "<script>alert('Invalid role. Please contact support.'); window.history.back();</script>";
                    }
                    exit;
                } else {
                    echo "<script>alert('Invalid password. Please try again.'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('No user found with this email. Please register first.'); window.history.back();</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Invalid action!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request method!'); window.history.back();</script>";
}
?>
