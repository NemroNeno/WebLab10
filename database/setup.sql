-- database.sql
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT REFERENCES roles(id)
);

CREATE TABLE sessions (
    id SERIAL PRIMARY KEY,
    date DATE NOT NULL
);

CREATE TABLE attendance (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    session_id INT REFERENCES sessions(id),
    status BOOLEAN NOT NULL
);
CREATE TABLE IF NOT EXISTS "attendance" (
  "classid" INT NOT NULL,
  "studentid" INT NOT NULL,
  "isPresent" BOOLEAN NOT NULL,
  "comments" VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS "class" (
  "id" SERIAL PRIMARY KEY,
  "teacherid" INT NOT NULL,
  "starttime" TIME NOT NULL,
  "endtime" TIME NOT NULL,
  "credit_hours" INT NOT NULL
);

CREATE TABLE IF NOT EXISTS "user" (
  "id" SERIAL PRIMARY KEY,
  "fullname" VARCHAR(200) NOT NULL,
  "email" VARCHAR(200) NOT NULL,
  "class" VARCHAR(10) NOT NULL,
  "role" VARCHAR(50) NOT NULL
);
