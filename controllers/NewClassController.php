<?php
session_set_cookie_params(['path' => '/', 'domain' => 'localhost', 'secure' => false, 'httponly' => true]);
session_start();
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $class_name = $_POST['class_name'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $selected_students = $_POST['selected_students'] ?? [];
    $teacherId = $_SESSION['userId'];
    // $teacherId = $_POST['userIdInput'];

    if (!empty($class_name) && !empty($start_time) && !empty($end_time) && !empty($selected_students)) {
        // Insert the class data into the classes table
        try {
            // Start a transaction to ensure both insertions are completed
            $conn->beginTransaction();

            // Insert the new class
            $sql = "INSERT INTO classes (name, start_time, end_time) VALUES (:name, :start_time, :end_time)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $class_name);
            $stmt->bindParam(':start_time', $start_time);
            $stmt->bindParam(':end_time', $end_time);
            $stmt->execute();

            // Get the last inserted class id
            $classId = $conn->lastInsertId();

            // Insert enrollments for the selected students
            foreach ($selected_students as $student_id) {
                $enroll_sql = "INSERT INTO enrollment (teacher_id, student_id, class_id) VALUES (:teacher_id, :student_id, :class_id)";
                $enroll_stmt = $conn->prepare($enroll_sql);
                $enroll_stmt->bindParam(':teacher_id', $teacherId);
                $enroll_stmt->bindParam(':student_id', $student_id);
                $enroll_stmt->bindParam(':class_id', $classId);
                $enroll_stmt->execute();
            }

            // Commit the transaction
            $conn->commit();

            echo "<h3>Class and enrollments were successfully created!</h3>";
            header('Location: ../public/teacher.php');
        } catch (PDOException $e) {
            // Rollback the transaction if any error occurs
            $conn->rollBack();
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "<h3>All fields are required and at least one student must be selected.</h3>";
    }
} else {
    echo "<h3>Invalid request method.</h3>";
}
?>
