<?php
require_once __DIR__.'/../includes/functions.php'; require_login(); verify_csrf();
if($_SERVER['REQUEST_METHOD']==='POST'){
 $old=$_POST['old_password']??'';$new=$_POST['new_password']??'';
 $q=db()->prepare('SELECT password_hash FROM users WHERE user_id=?');$q->execute([current_user()['user_id']]);$hash=$q->fetchColumn();
 if(!password_verify($old,$hash)) flash('danger','Current password is incorrect.');
 elseif(strlen($new)<8 || !preg_match('/[A-Za-z]/',$new) || !preg_match('/[0-9]/',$new)) flash('danger','Use at least 8 characters with letters and numbers.');
 else {db()->prepare('UPDATE users SET password_hash=? WHERE user_id=?')->execute([password_hash($new,PASSWORD_DEFAULT),current_user()['user_id']]);audit('Password changed');flash('success','Password updated successfully.');}
 redirect('change-password.php');
}
include __DIR__.'/../includes/header.php'; ?>
<div class="card" style="max-width:600px"><div class="card-header">Change password</div><div class="card-body"><form method="post"><?= csrf_field() ?><div class="mb-3"><label class="form-label">Current password</label><input class="form-control" type="password" name="old_password" required></div><div class="mb-3"><label class="form-label">New password</label><input class="form-control" type="password" name="new_password" minlength="8" required></div><button class="btn btn-primary">Update password</button></form></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>