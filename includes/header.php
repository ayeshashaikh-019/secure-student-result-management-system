<?php require_once __DIR__.'/functions.php'; require_login(); $u=current_user(); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e(APP_NAME) ?> · <?= e(ucfirst($u['role'])) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark topbar"><div class="container-fluid px-4">
<a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/dashboard.php"><span class="brand-mark"><i class="bi bi-shield-check"></i></span> ResultHub</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="nav"><div class="navbar-nav me-auto">
<a class="nav-link" href="<?= BASE_URL ?>/dashboard.php">Dashboard</a>
<?php if($u['role']==='admin'): ?>
<a class="nav-link" href="<?= BASE_URL ?>/users.php">Users</a><a class="nav-link" href="<?= BASE_URL ?>/subjects.php">Subjects</a>
<a class="nav-link" href="<?= BASE_URL ?>/classes.php">Classes</a><a class="nav-link" href="<?= BASE_URL ?>/exams.php">Exams</a><a class="nav-link" href="<?= BASE_URL ?>/assignments.php">Assignments</a><a class="nav-link" href="<?= BASE_URL ?>/enrollments.php">Enrollments</a><a class="nav-link" href="<?= BASE_URL ?>/reports.php">Reports</a><a class="nav-link" href="<?= BASE_URL ?>/audit.php">Audit logs</a>
<?php elseif($u['role']==='teacher'): ?>
<a class="nav-link" href="<?= BASE_URL ?>/marks.php">Marks entry</a><a class="nav-link" href="<?= BASE_URL ?>/history.php">My history</a>
<?php else: ?><a class="nav-link" href="<?= BASE_URL ?>/my-results.php">My results</a><?php endif; ?>
</div><div class="d-flex align-items-center gap-3 text-white"><div class="text-end d-none d-md-block"><div class="small fw-semibold"><?= e($u['name']) ?></div><div class="small opacity-75"><?= e(ucfirst($u['role'])) ?></div></div><a class="btn btn-sm btn-outline-light" href="<?= BASE_URL ?>/change-password.php"><i class="bi bi-key"></i></a><a class="btn btn-sm btn-light" href="<?= BASE_URL ?>/logout.php">Logout</a></div></div></div></nav>
<main class="container-fluid px-4 py-4"><div class="page-heading"><div><div class="eyebrow">SECURE ACADEMIC PORTAL</div><h1><?= e(ucfirst(str_replace('-', ' ', pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)))) ?></h1></div><span class="date-pill"><i class="bi bi-calendar3"></i> <?= date('d M Y') ?></span></div><?php show_flash(); ?>
