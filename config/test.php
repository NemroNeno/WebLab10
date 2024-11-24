<?php
require_once 'db.php';
// ...existing code...
$conn = Database::connect();


// Insert roles
$roles = ['Student', 'Teacher'];
$roleStmt = $conn->prepare("INSERT INTO roles (name) VALUES (:name)");
foreach ($roles as $role) {
    $roleStmt->execute([':name' => $role]);
}

// Get role IDs
$roleIds = [];
$roleQuery = $conn->query("SELECT id, name FROM roles");
foreach ($roleQuery as $row) {
    $roleIds[$row['name']] = $row['id'];
}

// Insert users
$userStmt = $conn->prepare("
    INSERT INTO users (fullname, email, password, role_id)
    VALUES (:fullname, :email, :password, :role_id)
");

$teachers = [];
for ($i = 1; $i <= 5; $i++) {
    $fullname = "Teacher $i";
    $email = "teacher$i@example.com";
    $password = password_hash('password', PASSWORD_BCRYPT);
    $role_id = $roleIds['Teacher'];
    $userStmt->execute([
        ':fullname' => $fullname,
        ':email' => $email,
        ':password' => $password,
        ':role_id' => $role_id,
    ]);
    $teachers[] = $conn->lastInsertId();
}

$students = [];
for ($i = 1; $i <= 10; $i++) {
    $fullname = "Student $i";
    $email = "student$i@example.com";
    $password = password_hash('password', PASSWORD_BCRYPT);
    $role_id = $roleIds['Student'];
    $userStmt->execute([
        ':fullname' => $fullname,
        ':email' => $email,
        ':password' => $password,
        ':role_id' => $role_id,
    ]);
    $students[] = $conn->lastInsertId();
}

// Insert classes
$classStmt = $conn->prepare("
    INSERT INTO classes (name, teacher_id, start_time, end_time, credit_hours)
    VALUES (:name, :teacher_id, :start_time, :end_time, :credit_hours)
");

$classNames = ['Mathematics', 'Science', 'History'];
$classes = [];
foreach ($classNames as $index => $name) {
    $teacher_id = $teachers[$index % count($teachers)];
    $classStmt->execute([
        ':name' => $name,
        ':teacher_id' => $teacher_id,
        ':start_time' => '09:00:00',
        ':end_time' => '10:30:00',
        ':credit_hours' => 3,
    ]);
    $classes[] = $conn->lastInsertId();
}

// Insert sessions
$sessionStmt = $conn->prepare("
    INSERT INTO sessions (class_id, date)
    VALUES (:class_id, :date)
");

$sessions = [];
foreach ($classes as $class_id) {
    for ($d = 0; $d < 5; $d++) {
        $date = date('Y-m-d', strtotime("+$d days"));
        $sessionStmt->execute([
            ':class_id' => $class_id,
            ':date' => $date,
        ]);
        $sessions[] = $conn->lastInsertId();
    }
}

// Insert attendance
$attendanceStmt = $conn->prepare("
    INSERT INTO attendance (class_id, student_id, session_id, is_present, comments)
    VALUES (:class_id, :student_id, :session_id, :is_present, :comments)
");

foreach ($sessions as $session_id) {
    $class_id = $conn->query("SELECT class_id FROM sessions WHERE id = $session_id")->fetchColumn();
    foreach ($students as $student_id) {
        $is_present = rand(0, 1);
        $comments = $is_present ? '' : 'Absent';
        $attendanceStmt->execute([
            ':class_id' => $class_id,
            ':student_id' => $student_id,
            ':session_id' => $session_id,
            ':is_present' => $is_present,
            ':comments' => $comments,
        ]);
    }
}

// ...existing code...
echo "Dummy data inserted successfully.";
?>