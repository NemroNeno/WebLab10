<?php
require_once '../src/Classes.php';
session_start();

$sessionId = $_GET['session_id'] ?? null;

if (!$sessionId) {
    echo "Session ID not provided.";
    exit;
}

// Fetch attendance data
$attendanceData = getSessionAttendance($sessionId); // Implemented in Classes.php

?>
<!DOCTYPE html>
<html>
<head>
    <title>Session Details</title>
    <style>
        :root {
            --primary-color: #007BFF;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-top: 1rem;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
        }

        .status-present {
            color: var(--success-color);
            font-weight: bold;
        }

        .status-absent {
            color: var(--danger-color);
            font-weight: bold;
        }

        .back-btn {
            display: inline-block;
            margin: 1rem;
            padding: 0.5rem 1rem;
            background: var(--primary-color);
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="#" onclick="window.close();" class="back-btn">&larr; Back</a>
        <h2>Attendance Details for Session <?= htmlspecialchars($sessionId) ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Roll Number</th>
                    <th>Student Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendanceData as $attendance): ?>
                    <?php $statusClass = $attendance['status'] ? 'status-present' : 'status-absent'; ?>
                    <tr>
                        <td><?= htmlspecialchars($attendance['roll_number']) ?></td>
                        <td><?= htmlspecialchars($attendance['student_name']) ?></td>
                        <td class="<?= $statusClass ?>"><?= $attendance['status'] ? 'Present' : 'Absent' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>