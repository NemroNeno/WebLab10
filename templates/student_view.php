<?php
session_set_cookie_params(['path' => '/', 'domain' => 'localhost', 'secure' => false, 'httponly' => true]);
require_once '../src/Classes.php';
require_once '../controllers/DataInjectionController.php';
require_once '../controllers/StudentAttendanceController.php';
session_start();
?>
<!-- templates/student_view.php -->
<style>
    .class-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .class-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .class-card:hover {
        transform: translateY(-5px);
    }

    .class-card.selected {
        border: 2px solid var(--primary-color);
    }

    .class-card h3 {
        margin-bottom: 0.5rem;
        color: var(--primary-color);
    }

    .attendance-stats {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
        font-size: 0.9rem;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: none;
    }

    .attendance-table.active {
        display: table;
    }

    .attendance-table th,
    .attendance-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .attendance-table th {
        background: #f8f9fa;
        font-weight: 600;
    }

    .status-present {
        color: var(--success-color);
    }

    .status-absent {
        color: var(--danger-color);
    }

    .percentage-high {
        color: var(--success-color);
    }

    .percentage-medium {
        color: var(--warning-color);
    }

    .percentage-low {
        color: var(--danger-color);
        font-weight: bold;
    }
</style>

<div class="container">
    <h2>Your Classes</h2>
    
    <div class="class-cards">
        <?php
        // Fetch courses and attendance data for the current student
        $studentId = $current_userId;
        $courses = getCoursesAndAttendancePercentage($studentId); // Fetching courses and attendance percentage
        foreach ($courses as $course): 
        ?>
            <div class="class-card" data-class-id="<?= $course['id'] ?>">
                <h3><?= htmlspecialchars($course['name']) ?></h3>
                <div class="attendance-stats">
                    <span class="<?= $course['is_present'] ?>">
                        StartTime: <?= $course['start_time'] ?> <br>
                        EndTime: <?= $course['end_time'] ?> <br>
                        <?= $course['is_present']   ==1?'Present':'Absent' ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.class-card');
    const tables = document.querySelectorAll('.attendance-table');

    cards.forEach(card => {
        card.addEventListener('click', function() {
            const classId = this.dataset.classId;
            
            // Update selected card
            cards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            
            // Show corresponding table
            tables.forEach(table => {
                if (table.id === `attendance-${classId}`) {
                    table.classList.add('active');
                } else {
                    table.classList.remove('active');
                }
            });
        });
    });

    // Show first class by default
    if (cards.length > 0) {
        cards[0].click();
    }
});
</script>
