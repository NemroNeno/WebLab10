<?php
require_once '../src/Classes.php';
session_start();

$sessionId = $_GET['session_id'] ?? null;

if (!$sessionId) {
    echo "Session ID not provided.";
    exit;
}

// Fetch session details
$sessionDetails = getSessionDetails($sessionId);
$attendanceData = getSessionAttendance($sessionId); // Fetch attendance data for students

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $updatedAttendance = [];
    foreach ($_POST['attendance'] as $studentId => $status) {
        $updatedAttendance[] = [
            'student_id' => $studentId,
            'status' => $status === 'present'
        ];
    }

    $success = updateSessionAttendance($sessionId, $updatedAttendance);

    if ($success) {
        header('Location: teacher_view.php');
        exit;
    } else {
        $errorMessage = "Failed to update session attendance.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Session Attendance</title>
    <style>
        /* Add necessary styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: #fff;
            border-radius: 8px;
        }
        h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
        }
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .save-btn {
            background: var(--primary-color);
            color: #fff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .save-btn:hover {
            background: #0056b3;
        }
        .error-message {
            color: var(--danger-color);
            margin-bottom: 1rem;
        }
        .students-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .students-table th,
        .students-table td {
            padding: 1rem;
            border: 1px solid #ddd;
            text-align: left;
        }
        .attendance-select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Attendance for Session <?= htmlspecialchars($sessionId) ?></h2>
        <?php if (isset($errorMessage)): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <form method="POST">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Roll Number</th>
                        <th>Student Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendanceData as $attendance): ?>
                        <tr>
                            <td><?= htmlspecialchars($attendance['roll_number']) ?></td>
                            <td><?= htmlspecialchars($attendance['student_name']) ?></td>
                            <td>
                                <select name="attendance[<?= $attendance['student_id'] ?>]" class="attendance-select">
                                    <option value="present" <?= $attendance['status'] ? 'selected' : '' ?>>Present</option>
                                    <option value="absent" <?= !$attendance['status'] ? 'selected' : '' ?>>Absent</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="save-btn">Save Changes</button>
        </form>
    </div>
</body>
</html>