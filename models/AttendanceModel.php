<?php
require '../config/db.php';

class AttendanceModel {
    public static function markAttendance($classid, $studentid, $isPresent) {
        $conn = Database::connect();
        $stmt = $conn->prepare("INSERT INTO \"attendance\" (classid, studentid, isPresent) VALUES (:classid, :studentid, :isPresent)");
        $stmt->execute(['classid' => $classid, 'studentid' => $studentid, 'isPresent' => $isPresent]);
    }
}
?>
