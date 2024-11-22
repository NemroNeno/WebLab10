<?php
require_once '../src/Classes.php';
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
        // Fetch classes for the current student
        $studentId = Auth::user()['id'];
        $classes = getStudentClasses($studentId); // You'll need to implement this function

        foreach ($classes as $class): 
            $attendancePercentage = calculateAttendancePercentage($studentId, $class['id']); // Implement this
            $percentageClass = $attendancePercentage >= 85 ? 'percentage-high' : 
                            ($attendancePercentage >= 75 ? 'percentage-medium' : 'percentage-low');
        ?>
            <div class="class-card" data-class-id="<?= $class['id'] ?>">
                <h3><?= htmlspecialchars($class['name']) ?></h3>
                <p><?= htmlspecialchars($class['course_code']) ?></p>
                <div class="attendance-stats">
                    <span>Total Sessions: <?= $class['total_sessions'] ?></span>
                    <span class="<?= $percentageClass ?>">
                        Attendance: <?= $attendancePercentage ?>%
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php foreach ($classes as $class): ?>
        <table class="attendance-table" id="attendance-<?= $class['id'] ?>">
            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sessions = getClassSessions($class['id'], $studentId); // Implement this
                foreach ($sessions as $session):
                    $statusClass = $session['status'] ? 'status-present' : 'status-absent';
                ?>
                    <tr>
                        <td><?= $session['id'] ?></td>
                        <td><?= date('d M Y', strtotime($session['date'])) ?></td>
                        <td class="<?= $statusClass ?>">
                            <?= $session['status'] ? 'Present' : 'Absent' ?>
                        </td>
                        <td><?= htmlspecialchars($session['notes'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
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