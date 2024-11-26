<?php
session_set_cookie_params(['path' => '/', 'domain' => 'localhost', 'secure' => false, 'httponly' => true]);
session_start();
require_once "../config/db.php"; // Assuming the database connection is here

// Method to get classes assigned to the current teacher
function getClassesForTeacher($teacherId) {
    global $conn;
    try {
        // Prepare SQL query to fetch classes based on teacher ID
        $sql = "SELECT DISTINCT c.id, c.name, c.start_time, c.end_time
                FROM classes c
                JOIN enrollment e ON c.id = e.class_id
                WHERE e.teacher_id = :teacher_id";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':teacher_id', $teacherId);
        $stmt->execute();
        
        // Fetch the results
        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        if ($classes) {
            return $classes;
        } else {
            return "No classes found for this teacher.";
        }
    } catch (PDOException $e) {
        return "Error fetching classes: " . $e->getMessage();
    }
}

// Method to get students in a particular class
function getStudentsInClass($classId) {
    global $conn;
    try {
        // Prepare SQL query to fetch students based on class ID
        $sql = "SELECT u.id AS student_id, u.fullname AS student_name
                FROM users u
                JOIN enrollment e ON u.id = e.student_id
                WHERE e.class_id = :class_id";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':class_id', $classId);
        $stmt->execute();
        
        // Fetch the results
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($students) {
            return $students;
        } else {
            return "No students found for this class.";
        }
    } catch (PDOException $e) {
        return "Error fetching students: " . $e->getMessage();
    }
}

function setAttendance($classId, $studentId, $teacherId, $attendance) {
    global $conn; // Assuming $conn is the PDO connection object
    
    try {
        // Find the enrollment_id
        $sql = "SELECT id 
                FROM enrollment 
                WHERE class_id = :class_id 
                  AND student_id = :student_id 
                  AND teacher_id = :teacher_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':class_id', $classId);
        $stmt->bindParam(':student_id', $studentId);
        $stmt->bindParam(':teacher_id', $teacherId);
        $stmt->execute();
        
        $enrollment = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$enrollment) {
            throw new Exception("Enrollment not found for the given class, student, and teacher.");
        }
        
        $enrollmentId = $enrollment['id'];

        // Insert into the attendance table
        $sql = "INSERT INTO attendance (enrollment_id, is_present) 
                VALUES (:enrollment_id, :is_present)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':enrollment_id', $enrollmentId);
        $stmt->bindParam(':is_present', $attendance, PDO::PARAM_BOOL);
        $stmt->execute();
        
        return "Attendance set successfully.";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}

function isAttendanceRecordedForClass($classId) {
    global $conn; // Assuming you are using a global database connection

    try {
        // SQL query to check if there are any attendance records for the given class ID
        $sql = "
            SELECT 1 
            FROM attendance a
            JOIN enrollment e ON a.enrollment_id = e.id
            WHERE e.class_id = :class_id
            LIMIT 1"; // LIMIT 1 for efficiency, as we only need to know if a record exists
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':class_id', $classId, PDO::PARAM_INT);
        $stmt->execute();

        // Check if a record is found
        return $stmt->fetchColumn() !== false;
    } catch (PDOException $e) {
        // Handle exceptions (optional: log the error)
        error_log("Error checking attendance for class ID $classId: " . $e->getMessage());
        return false;
    }
}

function getStudentsInClassWithAttendance($classId) {
    global $conn;
    try {
        // SQL query to fetch students and concatenate their name with attendance status
        $sql = "
            SELECT
                u.id AS student_id,
                u.fullname AS student_name,
                CASE
                    WHEN a.is_present = 1 THEN 'Present'
                    WHEN a.is_present = 0 THEN 'Absent'
                    ELSE 'Not Recorded' -- Optional: Handle NULL cases
                END AS student_status
            FROM users u
            JOIN enrollment e ON u.id = e.student_id
            LEFT JOIN attendance a ON a.enrollment_id = e.id
            WHERE e.class_id = :class_id
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':class_id', $classId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch the results
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($students) {
            return json_encode($students);  // Return the students with concatenated status
        } else {
            return json_encode(["error" => "No students found with the specified attendance status."]);
        }
    } catch (PDOException $e) {
        return json_encode(["error" => "Error fetching students: " . $e->getMessage()]);
    }
}




// Handle GET request for fetching students
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['class_id'])) {
        $classId = $_GET['class_id'];
        $attendanceStatus = $_GET['attendanceStatus'];

        if($attendanceStatus){
            $_SESSION['classId'] = $classId;
            $students = getStudentsInClassWithAttendance($classId);
            echo $students;
        }else {
            $_SESSION['classId'] = $classId;
            $students = getStudentsInClass($classId);
            echo json_encode($students);
        }

    } else if(isset($_GET['class_id_status'])) {
        $classId = $_GET['class_id_status'];
        $status = isAttendanceRecordedForClass($classId);
        echo json_encode($status);
    } else {
        echo json_encode(["error" => "Class ID not provided."]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacherId = $_SESSION['userId']; // Assuming teacher's ID is stored in session
    $classId = $_SESSION['classId']; // Add a hidden input in the form for class ID
    $attendanceData = $_POST['attendance']; // Array of student_id => attendance_status

    foreach ($attendanceData as $studentId => $status) {
        echo $teacherId . '-' . $classId . '-' . $studentId . '-' . $status . '<br>';

        // Normalize status to handle case-sensitivity
        $isPresent = strtolower($status) === 'present';

        // Call setAttendance method
        $result = setAttendance($classId, $studentId, $teacherId, $isPresent);
        if ($result !== "Attendance set successfully.") {
            echo "Error: $result";
            break;
        }
    }

    // Redirect back to the attendance view or show a success message
    header("Location: ../public/teacher.php");
    exit;
}


?>
