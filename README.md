# Secure Student Result Management System

A PHP + MySQL academic result portal with administrator, teacher and student roles.

## Requirements
- XAMPP (Apache + MySQL), PHP 8.1+, MySQL 5.7+/MariaDB
- Visual Studio Code
- Browser

## Setup
1. Copy this folder into `C:\xampp\htdocs\` (folder name should be `secure-student-result-management-system`).
2. Start Apache and MySQL from XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Import `database/schema.sql`.
5. Edit `config/config.php` if your MySQL username/password differs. Default XAMPP values are root and blank password.
6. Visit `http://localhost/secure-student-result-management-system/database/seed.php` once to create demo accounts and sample data.
7. Open `http://localhost/secure-student-result-management-system/public/login.php`.

## Demo logins
- Administrator: `admin` / `Admin@12345`
- Teacher: `teacher1` / `Teacher@12345`
- Student: `student1` / `Student@12345`

Change all demo passwords immediately. Remove or block access to `database/seed.php` after seeding.

## Important first-run configuration
`BASE_URL` in `config/config.php` is set for the folder name above. If you rename the folder, update it.

## Add student to a class
The current admin interface creates student accounts but class enrollment is intentionally kept as a database operation for this first version. In phpMyAdmin, use the `enrollments` table to add a row with the student's `student_id`, `class_id`, and academic session. Use `assignments.php` to assign teachers.

## Security notes
- Passwords use PHP `password_hash()` / `password_verify()`.
- SQL uses PDO prepared statements.
- Forms use CSRF tokens.
- Roles are checked on server-side pages.
- Login lockout is applied after repeated failures.
- Use HTTPS and disable public access to setup scripts in production.
- Restrict database permissions and configure scheduled backups before deployment.

## Scope notes
The implementation covers core proposal workflows. Email/SMS, 2FA, GPA/CGPA, mobile app, cloud backups and advanced analytics are listed as future enhancements in the proposal and are not part of this initial release.
