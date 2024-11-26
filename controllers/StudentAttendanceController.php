<?php
function getCoursesAndAttendancePercentage($studentId) {
    global $conn;
    try {
        // SQL query to get courses and calculate attendance percentage for the student
        $sql = "
            SELECT 
                *
            FROM classes c
            JOIN enrollment e ON e.class_id = c.id
            LEFT JOIN attendance a ON a.enrollment_id = e.id
            WHERE e.student_id = :student_id
            GROUP BY c.id;
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':student_id', $studentId);
        $stmt->execute();
        
        // Fetch the results
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($courses) {
            return $courses;
        } else {
            return "No courses found for this student.";
        }
    } catch (PDOException $e) {
        return "Error fetching courses and attendance percentage: " . $e->getMessage();
    }
}

?>