<?php
require_once __DIR__ . '/../config/config.php';

function e(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect(string $path): never { header('Location: '.BASE_URL.'/'.$path); exit; }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(): void { if (!current_user()) redirect('login.php'); }
function require_role(string ...$roles): void {
    require_login();
    if (!in_array(current_user()['role'], $roles, true)) {
        http_response_code(403); exit('403 — You are not authorized to perform this action.');
    }
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.e(csrf_token()).'">'; }
function verify_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419); exit('Your session token expired. Please go back and try again.');
    }
}
function flash(string $type, string $message): void { $_SESSION['flash'] = [$type, $message]; }
function show_flash(): void {
    if (!empty($_SESSION['flash'])) {
        [$type,$msg] = $_SESSION['flash']; unset($_SESSION['flash']);
        echo '<div class="alert alert-'.e($type).' alert-dismissible fade show">'.e($msg).'<button class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}
function audit(string $action, ?string $old = null, ?string $new = null, ?int $target = null): void {
    if (!current_user()) return;
    $stmt = db()->prepare('INSERT INTO audit_logs(user_id, action, old_value, new_value, target_id, ip_address) VALUES(?,?,?,?,?,?)');
    $stmt->execute([current_user()['user_id'], $action, $old, $new, $target, $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
}
function grade_for(float $percentage): string {
    $stmt = db()->prepare('SELECT grade FROM grade_scales WHERE ? BETWEEN minimum_percentage AND maximum_percentage ORDER BY minimum_percentage DESC LIMIT 1');
    $stmt->execute([$percentage]);
    return $stmt->fetchColumn() ?: 'F';
}
function result_status(float $percentage): string { return $percentage >= 40 ? 'Pass' : 'Fail'; }
function nav_active(string $file): string { return basename($_SERVER['PHP_SELF']) === $file ? 'active' : ''; }
function user_count(string $role): int {
    $s=db()->prepare('SELECT COUNT(*) FROM users WHERE role=?'); $s->execute([$role]); return (int)$s->fetchColumn();
}
