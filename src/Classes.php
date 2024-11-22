<?php
// src/Classes.php

function getStudentClasses($studentId) {
    // Mock data for testing
    return [
        [
            'id' => 1,
            'name' => 'Web Engineering',
            'course_code' => 'CS-351',
            'total_sessions' => 24,
            'attended_sessions' => 20
        ],
        [
            'id' => 2,
            'name' => 'Database Systems',
            'course_code' => 'CS-235',
            'total_sessions' => 30,
            'attended_sessions' => 21
        ],
        [
            'id' => 3,
            'name' => 'Software Engineering',
            'course_code' => 'SE-301',
            'total_sessions' => 28,
            'attended_sessions' => 25
        ],
        [
            'id' => 4,
            'name' => 'Operating Systems',
            'course_code' => 'CS-331',
            'total_sessions' => 26,
            'attended_sessions' => 18
        ]
    ];
}

function getTeacherClasses($teacherId) {
    // Mock data for testing teacher's classes
    return [
        [
            'id' => 1,
            'name' => 'Web Engineering Section A',
            'course_code' => 'CS-351',
            'student_count' => 35,
            'total_sessions' => 24,
            'description' => 'Web development fundamentals and modern frameworks',
            'semester' => '6th',
            'schedule' => 'Monday, Wednesday 10:00 AM'
        ],
        [
            'id' => 2,
            'name' => 'Database Systems Section B',
            'course_code' => 'CS-235',
            'student_count' => 40,
            'total_sessions' => 30,
            'description' => 'Database design and SQL fundamentals',
            'semester' => '4th',
            'schedule' => 'Tuesday, Thursday 11:30 AM'
        ],
        [
            'id' => 3,
            'name' => 'Software Engineering Section C',
            'course_code' => 'SE-301',
            'student_count' => 38,
            'total_sessions' => 28,
            'description' => 'Software development lifecycle and methodologies',
            'semester' => '5th',
            'schedule' => 'Wednesday, Friday 2:00 PM'
        ],
        [
            'id' => 4,
            'name' => 'Operating Systems Section A',
            'course_code' => 'CS-331',
            'student_count' => 42,
            'total_sessions' => 26,
            'description' => 'OS concepts and system programming',
            'semester' => '5th',
            'schedule' => 'Monday, Thursday 3:30 PM'
        ]
    ];
}

function fetchClassSessions($classId) {
    // Mock data for class sessions
    $sessions = [];
    $totalStudents = 35; // Mock fixed number for testing
    
    // Generate last 30 days of sessions
    for ($i = 0; $i < 30; $i++) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $present = rand(round($totalStudents * 0.7), $totalStudents); // Random attendance between 70-100%
        
        $sessions[] = [
            'id' => $i + 1,
            'date' => $date,
            'total_students' => $totalStudents,
            'present_count' => $present,
            'absent_count' => $totalStudents - $present
        ];
    }
    
    return $sessions;
}

function calculateAttendancePercentage($studentId, $classId) {
    $classes = getStudentClasses($studentId);
    foreach ($classes as $class) {
        if ($class['id'] == $classId) {
            return round(($class['attended_sessions'] / $class['total_sessions']) * 100);
        }
    }
    return 0;
}

function getClassSessions($classId, $studentId) {
    // Mock session data
    $sessions = [];
    $classes = getStudentClasses($studentId);
    
    foreach ($classes as $class) {
        if ($class['id'] == $classId) {
            $totalSessions = $class['total_sessions'];
            $attendedSessions = $class['attended_sessions'];
            
            for ($i = 1; $i <= $totalSessions; $i++) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $status = $i <= $attendedSessions;
                
                $sessions[] = [
                    'id' => $i,
                    'date' => $date,
                    'status' => $status,
                    'notes' => $status ? 'Present' : 'Absent'
                ];
            }
            break;
        }
    }
    
    return $sessions;
}