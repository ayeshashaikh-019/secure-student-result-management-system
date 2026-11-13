<?php
declare(strict_types=1);
define('APP_NAME', 'ResultHub');
define('BASE_URL', '/secure-student-result-management-system/public');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'secure_result_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_MINUTES', 15);

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_name('result_hub_session');
    session_start();
}
date_default_timezone_set('Asia/Karachi');

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
             PDO::ATTR_EMULATE_PREPARES => false]
        );
    }
    return $pdo;
}
