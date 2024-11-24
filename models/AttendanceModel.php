<?php
require '../config/db.php';

class AttendanceModel {
    public static function markAttendance($class_id, $student_id, $session_id, $is_present, $comments = '') {
        $conn = Database::connect();
        $stmt = $conn->prepare("
            INSERT INTO attendance (class_id, student_id, session_id, is_present, comments)
            VALUES (:class_id, :student_id, :session_id, :is_present, :comments)
        ");
        $stmt->execute([
            ':class_id' => $class_id,
            ':student_id' => $student_id,
            ':session_id' => $session_id,
            ':is_present' => $is_present,
            ':comments' => $comments,
        ]);
    }
}
?>
