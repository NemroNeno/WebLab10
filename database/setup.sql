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
