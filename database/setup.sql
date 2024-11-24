-- database.sql
-- Create roles table
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL

);

-- Create users table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    fullname VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT REFERENCES roles(id)
);

-- Create classes table
CREATE TABLE classes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    teacher_id INT REFERENCES users(id),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    credit_hours INT NOT NULL
);

-- Create sessions table
CREATE TABLE sessions (
    id SERIAL PRIMARY KEY,
    class_id INT REFERENCES classes(id),
    date DATE NOT NULL
);

-- Create attendance table
CREATE TABLE attendance (
    id SERIAL PRIMARY KEY,
    class_id INT REFERENCES classes(id),
    student_id INT REFERENCES users(id),
    session_id INT REFERENCES sessions(id),
    is_present BOOLEAN NOT NULL,
    comments VARCHAR(200)
);
