<?php
require_once '../src/Classes.php';
session_start();
?>
<!-- templates/teacher_view.php -->
<style>
    .tabs {
        margin-bottom: 2rem;
    }

    .tab-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .tab-button {
        padding: 0.75rem 1.5rem;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
    }

    .tab-button.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .class-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .class-card {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .class-card:hover {
        transform: translateY(-5px);
    }

    .class-card.selected {
        border: 2px solid var(--primary-color);
    }

    .sessions-table,
    .students-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .sessions-table th,
    .sessions-table td,
    .students-table th,
    .students-table td {
        padding: 1rem;
        border: 1px solid #ddd;
    }

    .attendance-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .attendance-btn.present {
        background: var(--success-color);
        color: white;
    }

    .attendance-btn.absent {
        background: var(--danger-color);
        color: white;
    }

    .new-class-form {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .student-list {
        margin-top: 1rem;
    }

    .add-student-btn {
        margin-top: 1rem;
    }

    .save-btn {
        background: var(--primary-color);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<div class="tabs">
    <div class="tab-buttons">
        <button class="tab-button active" data-tab="existing-classes">Existing Classes</button>
        <button class="tab-button" data-tab="add-class">Add New Class</button>
    </div>

    <div class="tab-content active" id="existing-classes">
        <div class="class-cards">
            <?php
            $classes = getTeacherClasses(Auth::user()['id']); // Implement this function
            foreach ($classes as $class):
                ?>
                <div class="class-card" data-class-id="<?= $class['id'] ?>">
                    <h3><?= htmlspecialchars($class['name']) ?></h3>
                    <p>Course Code: <?= htmlspecialchars($class['course_code']) ?></p>
                    <p>Students: <?= $class['student_count'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="sessions-view" style="display: none;">
            <h3>Class Sessions</h3>
            <button id="new-session-btn" class="save-btn">Add New Session</button>
            <table class="sessions-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Students</th>
                        <th>Present</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="sessions-list"></tbody>
            </table>
        </div>

        <div id="attendance-view" style="display: none;">
            <h3>Mark Attendance</h3>
            <div class="form-group">
                <label>Date:</label>
                <input type="date" id="attendance-date">
            </div>
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Roll Number</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="students-list"></tbody>
            </table>
            <button id="save-attendance" class="save-btn">Save Attendance</button>
        </div>
    </div>

    <div class="tab-content" id="add-class">
        <form class="new-class-form" id="new-class-form">
            <div class="form-group">
                <label>Class Name:</label>
                <input type="text" name="class_name" required>
            </div>
            <div class="form-group">
                <label>Course Code:</label>
                <input type="text" name="course_code" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description"></textarea>
            </div>
            <div class="student-list">
                <h4>Students</h4>
                <div id="student-entries">
                    <div class="form-group">
                        <input type="text" name="student_roll[]" placeholder="Roll Number">
                        <input type="text" name="student_name[]" placeholder="Student Name">
                    </div>
                </div>
                <button type="button" class="add-student-btn" id="add-student">Add Student</button>
            </div>
            <button type="submit" class="save-btn">Create Class</button>
        </form>
    </div>
</div>

<script>
    // Add this function in your script section
    async function fetchClassSessions(classId) {
        // Simulating API call with promise
        return new Promise((resolve) => {
            setTimeout(() => {
                // Mock data for sessions
                const sessions = [];
                const totalStudents = 35;

                // Generate 30 days of mock session data
                for (let i = 0; i < 30; i++) {
                    const date = new Date();
                    date.setDate(date.getDate() - i);
                    const presentCount = Math.floor(Math.random() * (totalStudents - 25) + 25); // Random between 25-35

                    sessions.push({
                        id: i + 1,
                        date: date.toISOString().split('T')[0],
                        total_students: totalStudents,
                        present_count: presentCount,
                        absent_count: totalStudents - presentCount,
                        class_id: classId
                    });
                }

                resolve(sessions);
            }, 500); // Simulate network delay
        });
    }

    // Update class card click handler to use async/await
    document.querySelectorAll('.class-card').forEach(card => {
        card.addEventListener('click', async () => {
            const classId = card.dataset.classId;

            // Show loading state
            document.getElementById('sessions-list').innerHTML = '<tr><td colspan="4">Loading sessions...</td></tr>';

            // Fetch and display sessions
            const sessions = await fetchClassSessions(classId);
            displaySessions(sessions);

            document.getElementById('sessions-view').style.display = 'block';
            document.getElementById('attendance-view').style.display = 'none';
        });
    });
    function displaySessions(sessions) {
        const tbody = document.getElementById('sessions-list');
        tbody.innerHTML = '';

        sessions.forEach(session => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${session.date}</td>
            <td>${session.total_students}</td>
            <td>${session.present_count} (${Math.round(session.present_count / session.total_students * 100)}%)</td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="viewSessionDetails(${session.id})">
                    View Details
                </button>
                <button class="btn btn-warning btn-sm" onclick="editSession(${session.id})">
                    Edit
                </button>
            </td>
        `;
            tbody.appendChild(row);
        });
    }

    function viewSessionDetails(sessionId) {
        // Implement session details view
        console.log(`Viewing session ${sessionId}`);
    }

    function editSession(sessionId) {
        // Implement session editing
        console.log(`Editing session ${sessionId}`);
    }
    document.addEventListener('DOMContentLoaded', function () {
        // Tab switching
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.dataset.tab;

                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                button.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Class card selection
        const classCards = document.querySelectorAll('.class-card');
        classCards.forEach(card => {
            card.addEventListener('click', async () => {
                classCards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');

                console.log("clicked");

                const classId = card.dataset.classId;
                const sessions = await fetchClassSessions(classId); // Implement this
                displaySessions(sessions);

                document.getElementById('sessions-view').style.display = 'block';
                document.getElementById('attendance-view').style.display = 'none';
            });
        });

        // New session button
        document.getElementById('new-session-btn').addEventListener('click', async () => {
            const selectedCard = document.querySelector('.class-card.selected');
            if (selectedCard) {
                const classId = selectedCard.dataset.classId;
                const students = await fetchClassStudents(classId); // Implement this
                displayAttendanceForm(students);

                document.getElementById('sessions-view').style.display = 'none';
                document.getElementById('attendance-view').style.display = 'block';
            }
        });

        // Add student button in new class form
        document.getElementById('add-student').addEventListener('click', () => {
            const studentEntry = document.createElement('div');
            studentEntry.className = 'form-group';
            studentEntry.innerHTML = `
            <input type="text" name="student_roll[]" placeholder="Roll Number">
            <input type="text" name="student_name[]" placeholder="Student Name">
        `;
            document.getElementById('student-entries').appendChild(studentEntry);
        });

        // Save attendance
        document.getElementById('save-attendance').addEventListener('click', async () => {
            const selectedCard = document.querySelector('.class-card.selected');
            const date = document.getElementById('attendance-date').value;
            const attendance = [];

            document.querySelectorAll('.attendance-btn').forEach(btn => {
                attendance.push({
                    studentId: btn.dataset.studentId,
                    status: btn.classList.contains('present')
                });
            });

            await saveAttendance(selectedCard.dataset.classId, date, attendance); // Implement this
            // Show success message and return to sessions view
        });
    });

    // Toggle attendance status
    function toggleAttendance(btn) {
        btn.classList.toggle('present');
        btn.classList.toggle('absent');
        btn.textContent = btn.classList.contains('present') ? 'Present' : 'Absent';
    }
</script>