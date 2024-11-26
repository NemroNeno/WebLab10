CREATE TABLE enrollment (
    id SERIAL PRIMARY KEY,
    teacher_id INT REFERENCES users(id),
    student_id INT REFERENCES users(id),
    class_id INT REFERENCES classes(id),
);