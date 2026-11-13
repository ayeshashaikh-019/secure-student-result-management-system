CREATE DATABASE IF NOT EXISTS secure_result_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE secure_result_db;

CREATE TABLE users (
 user_id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(40) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('admin','teacher','student') NOT NULL,
 name VARCHAR(120) NOT NULL,
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 failed_attempts INT NOT NULL DEFAULT 0,
 locked_until DATETIME NULL,
 last_login DATETIME NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE students (
 student_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL UNIQUE,
 roll_number VARCHAR(40) NOT NULL UNIQUE,
 department VARCHAR(100) NOT NULL DEFAULT '',
 semester TINYINT NOT NULL DEFAULT 1,
 FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE teachers (
 teacher_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL UNIQUE,
 department VARCHAR(100) NOT NULL DEFAULT '',
 FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE subjects (
 subject_id INT AUTO_INCREMENT PRIMARY KEY,
 subject_name VARCHAR(120) NOT NULL,
 subject_code VARCHAR(30) NOT NULL UNIQUE,
 credit_hours TINYINT NOT NULL DEFAULT 3
) ENGINE=InnoDB;

CREATE TABLE classes (
 class_id INT AUTO_INCREMENT PRIMARY KEY,
 class_name VARCHAR(100) NOT NULL,
 department VARCHAR(100) NOT NULL,
 semester TINYINT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE enrollments (
 enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
 student_id INT NOT NULL,
 class_id INT NOT NULL,
 academic_session VARCHAR(30) NOT NULL,
 UNIQUE(student_id,class_id,academic_session),
 FOREIGN KEY(student_id) REFERENCES students(student_id) ON DELETE CASCADE,
 FOREIGN KEY(class_id) REFERENCES classes(class_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE teacher_subjects (
 assignment_id INT AUTO_INCREMENT PRIMARY KEY,
 teacher_id INT NOT NULL,
 subject_id INT NOT NULL,
 class_id INT NOT NULL,
 UNIQUE(teacher_id,subject_id,class_id),
 FOREIGN KEY(teacher_id) REFERENCES teachers(teacher_id) ON DELETE CASCADE,
 FOREIGN KEY(subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
 FOREIGN KEY(class_id) REFERENCES classes(class_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE exams (
 exam_id INT AUTO_INCREMENT PRIMARY KEY,
 exam_name VARCHAR(120) NOT NULL,
 semester TINYINT NOT NULL,
 academic_session VARCHAR(30) NOT NULL,
 exam_type VARCHAR(40) NOT NULL DEFAULT 'Final'
) ENGINE=InnoDB;

CREATE TABLE marks (
 mark_id INT AUTO_INCREMENT PRIMARY KEY,
 student_id INT NOT NULL,
 subject_id INT NOT NULL,
 exam_id INT NOT NULL,
 obtained_marks DECIMAL(8,2) NOT NULL,
 total_marks DECIMAL(8,2) NOT NULL,
 updated_by INT NOT NULL,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE(student_id,subject_id,exam_id),
 CHECK(obtained_marks >= 0 AND total_marks > 0 AND obtained_marks <= total_marks),
 FOREIGN KEY(student_id) REFERENCES students(student_id) ON DELETE CASCADE,
 FOREIGN KEY(subject_id) REFERENCES subjects(subject_id),
 FOREIGN KEY(exam_id) REFERENCES exams(exam_id),
 FOREIGN KEY(updated_by) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE results (
 result_id INT AUTO_INCREMENT PRIMARY KEY,
 student_id INT NOT NULL,
 exam_id INT NOT NULL,
 total_marks DECIMAL(10,2) NOT NULL DEFAULT 0,
 maximum_marks DECIMAL(10,2) NOT NULL DEFAULT 0,
 percentage DECIMAL(6,2) NOT NULL DEFAULT 0,
 grade VARCHAR(5) NOT NULL DEFAULT 'F',
 status ENUM('Pass','Fail') NOT NULL DEFAULT 'Fail',
 UNIQUE(student_id,exam_id),
 FOREIGN KEY(student_id) REFERENCES students(student_id) ON DELETE CASCADE,
 FOREIGN KEY(exam_id) REFERENCES exams(exam_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE grade_scales (
 grade_id INT AUTO_INCREMENT PRIMARY KEY,
 minimum_percentage DECIMAL(5,2) NOT NULL,
 maximum_percentage DECIMAL(5,2) NOT NULL,
 grade VARCHAR(5) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE result_history (
 history_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 student_id INT NOT NULL,
 subject_id INT NOT NULL,
 exam_id INT NOT NULL,
 old_marks DECIMAL(8,2) NULL,
 new_marks DECIMAL(8,2) NOT NULL,
 reason VARCHAR(255) NULL,
 changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(user_id),
 FOREIGN KEY(student_id) REFERENCES students(student_id),
 FOREIGN KEY(subject_id) REFERENCES subjects(subject_id),
 FOREIGN KEY(exam_id) REFERENCES exams(exam_id)
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
 log_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NULL,
 action VARCHAR(180) NOT NULL,
 old_value TEXT NULL,
 new_value TEXT NULL,
 target_id INT NULL,
 ip_address VARCHAR(45) NULL,
 timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO grade_scales(minimum_percentage,maximum_percentage,grade) VALUES
(90,100,'A+'),(80,89.99,'A'),(70,79.99,'B'),(60,69.99,'C'),(50,59.99,'D'),(40,49.99,'E'),(0,39.99,'F');

-- Demo login accounts are created by database/seed.php so their passwords are hashed by PHP.
