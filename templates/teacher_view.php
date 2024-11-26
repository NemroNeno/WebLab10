<?php
session_set_cookie_params(['path' => '/', 'domain' => 'localhost', 'secure' => false, 'httponly' => true]);
session_start();
require_once '../src/Classes.php';
require_once '../controllers/DataInjectionController.php';
require_once '../controllers/TeacherAttandanceController.php';


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

    .action-btn {
        padding: 6px 12px;
        margin: 0 4px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .view-btn {
        background-color: var(--primary-color);
        color: white;
    }

    .view-btn:hover {
        background-color: #0056b3;
    }

    .edit-btn {
        background-color: var(--warning-color);
        color: #000;
    }

    .edit-btn:hover {
        background-color: #e0a800;
    }

    .action-btn i {
        font-size: 12px;
    }
</style>

<div class="tabs">
    <!-- Introduction -->
    <div style="display: flex; flex-direction: column;">
        <h1 style="color: #0056b3;"><?= htmlspecialchars($current_fullname) ?></h1>
        <h2 style="color: #e0a800;"><?= htmlspecialchars($current_email) ?></h2>
    </div>

    <div class="tab-buttons">
        <button class="tab-button active" data-tab="existing-classes">Existing Classes</button>
        <button class="tab-button" data-tab="add-class">Add New Class</button>
    </div>

    <div class="tab-content active" id="existing-classes">
        <div class="class-cards">
            <?php
            $classes = getClassesForTeacher($current_userId);
            foreach ($classes as $class):
                echo $class['enollment_id'];
            ?>
                <div class="class-card" data-class-id="<?= $class['id'] ?>">
                    <h3 style="color: red;"><?= htmlspecialchars($class['name']) ?></h3>
                    <p>Students: <?= count(getStudentsInClass($class['id'])) ?></p>
                    <p>Start time: <?= $class['start_time']?></p>
                    <p>End time: <?= $class['end_time']?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- <div id="sessions-view" style="display: none;">
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
        </div> -->

        <div id="attendance-view" style="display: none;">
            <form method="POST" action="../controllers/TeacherAttandanceController.php">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="students-list"></tbody>
                </table>
                <button id="save-attendance" type="submit" class="save-btn">Save Attendance</button>
            </form>
        </div>
    </div>

    <div class="tab-content" id="add-class">
        <form class="new-class-form" id="new-class-form" method="POST" action="../controllers/NewClassController.php">
            <div class="form-group">
                <label>Class Name:</label>
                <input type="text" name="class_name" required>
            </div>

            <!-- Start Time -->
            <div class="form-group">
                <label for="start-time">Start Time:</label>
                <input type="time" id="start-time" name="start_time" required>
            </div>

            <!-- End Time -->
            <div class="form-group">
                <label for="end-time">End Time:</label>
                <input type="time" id="end-time" name="end_time" required>
                <input name="userIdInput" value=`${$current_userId}` hidden/>
            </div>

            <!-- Dynamic population of students and then selecting them -->
            <div class="form-group student-list">
                <h4>Select Students</h4>
                    <ul>
                        <?php
                        // Replace this with your database connection
                        require_once '../config/db.php';

                        // Fetch students from the database
                        try {
                            $query = "SELECT id, fullname FROM users WHERE role_id = 2"; // Assuming `role_id = 2` is for students
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($students as $student) {
                                echo "
                                <li>
                                    <label>
                                        <input type='checkbox' name='selected_students[]' value='{$student['id']}'>
                                        {$student['fullname']}
                                    </label>
                                </li>";
                            }
                        } catch (PDOException $e) {
                            echo "Error fetching students: " . $e->getMessage();
                        }
                        ?>
                    </ul>
                </div>
            <button type="submit" class="save-btn">Create Class</button>
        </form>
    </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script>
    document.querySelector('[data-tab="existing-classes"]').addEventListener('click', () => {
        document.getElementById('existing-classes').classList.add('active');
        document.getElementById('add-class').classList.remove('active');
    });

    const addClassButton = document.querySelector('[data-tab="add-class"]').addEventListener('click', () => {
        document.getElementById('existing-classes').classList.remove('active');
        document.getElementById('add-class').classList.add('active');
    });

    document.querySelectorAll('.class-card').forEach(card => {
    card.addEventListener('click', async () => {
        const classId = card.getAttribute('data-class-id');

        let attendanceStatus = false;

        const attendanceView = document.getElementById('attendance-view');
        attendanceView.style.display = 'block';

        $.ajax({
            async: true, // Make the request asynchronous (default behavior)
            cache: false, // Don't cache the request
            type: "GET", // Use GET method
            url: "http://localhost/WebLab10/controllers/TeacherAttandanceController.php", // PHP script URL
            data: { class_id_status: classId }, // Send class_id as a query parameter
            success: function (status) {
                const status_check = JSON.parse(status);
                if(status_check) {
                    document.getElementById('save-attendance').style.display = "none";
                    attendanceStatus = true;
                    alert("Done attendance");
                }else {
                    document.getElementById('save-attendance').style.display = "block";
                    alert("Not done attendance");
                }
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.error("Error Determing status:", error);
                alert("Failed to check status.");
            }});

        // Use AJAX to send the classId to the PHP script and fetch the students
        $.ajax({
            async: true, // Make the request asynchronous (default behavior)
            cache: false, // Don't cache the request
            type: "GET", // Use GET method
            url: "http://localhost/WebLab10/controllers/TeacherAttandanceController.php", // PHP script URL
            data: { class_id: classId, attendanceStatus }, // Send class_id as a query parameter
            success: function (students) {
                // Parse the returned JSON response
                students = JSON.parse(students);

                // Get the table body element where student data will be populated
                const tableBody = attendanceView.querySelector('tbody');
                tableBody.innerHTML = ''; // Clear previous content

                // Check if students data is returned
                if (students && students.length > 0) {
                    let status = "";
                    // Populate the table with student data
                    students.forEach(student => {
                        if(attendanceStatus){
                            status = student.student_status;
                        }
                        tableBody.innerHTML += `
                            <tr>
                                <td><p>${student.student_name}</p> -  <p style="color: blue;">${status}</p></td>
                                <td style="display: flex; justify-content: center; align-items: center;">
                                    <!-- Toggle Button (Present/Absent) -->
                                     <button 
                                        style="background-color: green; padding: 4px;" 
                                        class="attendance-toggle" 
                                        data-status="present" 
                                        type="button">
                                        Present
                                    </button>
                                    <input type="hidden" name="attendance[${student.student_id}]" value="present">
                                </td>
                            </tr>
                        `;
                    });

                    const toggleButtons = document.querySelectorAll('.attendance-toggle');
                    toggleButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            event.preventDefault();
                            const input = button.nextElementSibling;
                            // Toggle button text and data-status attribute
                            if (button.getAttribute('data-status') === 'Present') {
                                button.textContent = 'Absent';
                                button.style.backgroundColor = 'red';
                                button.setAttribute('data-status', 'Absent');
                                input.value = "Absent";
                            } else {
                                button.textContent = 'Present';
                                button.style.backgroundColor = 'green';
                                button.setAttribute('data-status', 'Present');
                                input.value = "Present";
                            }
                        });

                    });

                } else {
                    tableBody.innerHTML = '<tr><td colspan="2">No students found.</td></tr>';
                }
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.error("Error fetching students:", error);
                alert("Failed to load students.");
            }
        });

        
    });
});

</script>