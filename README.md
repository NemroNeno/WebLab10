## Lab Overview
The project is an Attendance Management System developed using PHP for the backend and AJAX for front-end interactions. The system tracks the attendance of students in their respective classes. It provides functionalities for both students and teachers to view attendance data, update attendance status, and calculate attendance percentages.

The project is built using the following technologies:

PHP: Backend logic for handling data processing, querying the database, and sending responses to the frontend.
AJAX: Asynchronous requests for fetching and updating attendance data without requiring full page reloads.
HTML, CSS, JavaScript: For building the frontend user interface and handling dynamic interactions.
SQL (MySQL): To store and manage student, class, attendance, and enrollment data.
Core Functionality:
1. Database Structure:
The system relies on the following tables in the database:

users: Stores student and teacher details.
classes: Stores class information like name, credit hours, and the teacher associated with the class.
enrollment: Relates students to the classes they are enrolled in.
attendance: Tracks the attendance for each student in each class, storing whether a student was present or absent during a specific session.
2. Classes and Enrollment:
The enrollment table connects students to their respective classes through a class_id.
The classes table contains information about each class, such as the teacher, class name, credit hours, and more.
3. Attendance Tracking:
Each record in the attendance table stores a student's presence status (is_present) for a given class session. The enrollment_id links the attendance record to a specific student and class combination.
4. Attendance Calculation:
For each class, the system calculates the attendance percentage by counting the total attended sessions and dividing them by the total number of sessions. This percentage is used to show the student’s performance in the course.
The attendance percentage is calculated with the formula:
(Attended Sessions / Total Sessions) * 100

Workflow:

Starting with the http://localhost/WebLab10/public/register.php where one can register and login,
Then by viewing Student.php using templated Student_view.php and same for Teacher.php using templated Teacher_view.php for respective views.

Then by using and leveraging the controllers written and tested in php for tracking database changes helped alot in this lab.



## Student View Having Shown Attandance Session

https://github.com/user-attachments/assets/79a0a15f-98cc-460f-824a-8aabd8bfa82d


## Teacher View Creating new class

https://github.com/user-attachments/assets/12146a04-568a-4658-8ed0-eb8a06d544ab


## Teacher View taking attandance

https://github.com/user-attachments/assets/9a21507c-a17d-4357-af71-d2241c9c22d7


## Database representing realtime plus tesing data

https://github.com/user-attachments/assets/35d15dfd-a107-4f83-9dac-9ac3999626e5




