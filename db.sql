-- db.sql
DROP DATABASE IF EXISTS smart_exam_cell;
CREATE DATABASE smart_exam_cell CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smart_exam_cell;

-- users: shared table for admin/faculty/student with role field (normalized)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) UNIQUE,
  role ENUM('admin','faculty','student') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- students table (holds student-specific data)
CREATE TABLE students (
  student_id INT PRIMARY KEY, -- matches users.id for role=student
  roll_no VARCHAR(30) NOT NULL UNIQUE,
  course_year VARCHAR(20),
  department VARCHAR(80),
  semester INT,
  phone VARCHAR(30),
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- faculty table
CREATE TABLE faculty (
  faculty_id INT PRIMARY KEY, -- matches users.id for role=faculty
  employee_no VARCHAR(50) UNIQUE,
  department VARCHAR(80),
  phone VARCHAR(30),
  FOREIGN KEY (faculty_id) REFERENCES users(id) ON DELETE CASCADE
);

-- courses
CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20) NOT NULL UNIQUE,
  title VARCHAR(200) NOT NULL,
  credits DECIMAL(3,1) DEFAULT 3.0,
  semester INT,
  department VARCHAR(80)
);

-- enrollments (student-course many-to-many)
CREATE TABLE enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  enrolled_on DATE DEFAULT CURRENT_DATE,
  UNIQUE(student_id, course_id),
  FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- exams (an exam event for a course)
CREATE TABLE exams (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT NOT NULL,
  exam_date DATE NOT NULL,
  total_marks INT NOT NULL DEFAULT 100,
  exam_type ENUM('Internal','External','Supplementary') DEFAULT 'External',
  invigilator_id INT, -- faculty assigned
  venue VARCHAR(120),
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  FOREIGN KEY (invigilator_id) REFERENCES faculty(faculty_id) ON DELETE SET NULL
);

-- results (student marks per exam)
CREATE TABLE results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  exam_id INT NOT NULL,
  student_id INT NOT NULL, -- reference to students.student_id
  marks_obtained DECIMAL(5,2) DEFAULT 0,
  grade VARCHAR(5),
  remarks VARCHAR(255),
  UNIQUE(exam_id, student_id),
  FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Audit table for transactions (simple concurrency/transaction demo)
CREATE TABLE audit_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  action VARCHAR(100),
  performed_by INT,
  performed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  details TEXT,
  FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Simple indexes
CREATE INDEX idx_enroll_student ON enrollments(student_id);
CREATE INDEX idx_results_student ON results(student_id);
-- minimal sample users (temporary placeholders; replace via seed.php)
INSERT INTO users (username, password_hash, full_name, email, role)
VALUES
('admin','temp','Principal Admin','admin@example.com','admin'),
('prof1','temp','Dr. Hari Kumar','hkumar@example.com','faculty'),
('stud1','temp','Ravi Kumar','ravi@example.com','student');

-- sample students (student_id matches user id 3 above)
INSERT INTO students (student_id, roll_no, course_year, department, semester, phone)
VALUES
(3, '23CS101', '2023-2026', 'Computer Science', 3, '9876543210');

-- sample faculty (id 2)
INSERT INTO faculty (faculty_id, employee_no, department, phone)
VALUES
(2, 'FAC2023-01', 'Computer Science', '9123456780');

-- sample courses
INSERT INTO courses (code, title, credits, semester, department) VALUES
('CS301', 'Database Management Systems', 4.0, 3, 'Computer Science'),
('CS302', 'Operating Systems', 4.0, 3, 'Computer Science');

-- enroll the student in CS301
INSERT INTO enrollments (student_id, course_id) VALUES (3, 1);

-- sample exam
INSERT INTO exams (course_id, exam_date, total_marks, exam_type, invigilator_id, venue)
VALUES (1, '2025-10-10', 100, 'External', 2, 'Hall A');

-- sample result
INSERT INTO results (exam_id, student_id, marks_obtained, grade, remarks) VALUES (1,3,78.5,'A','Good');

