<?php
require_once __DIR__.'/../includes/functions.php';
if(current_user()) redirect('dashboard.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf(); $username=trim($_POST['username']??''); $password=$_POST['password']??'';
 $q=db()->prepare('SELECT * FROM users WHERE username=? LIMIT 1'); $q->execute([$username]); $user=$q->fetch();
 if($user && $user['status']==='active' && $user['locked_until'] && strtotime($user['locked_until'])>time()){
   $error='Account temporarily locked. Try again later.';
 } elseif($user && $user['status']==='active' && password_verify($password,$user['password_hash'])){
   db()->prepare('UPDATE users SET failed_attempts=0,locked_until=NULL,last_login=NOW() WHERE user_id=?')->execute([$user['user_id']]);
   session_regenerate_id(true); $_SESSION['user']=['user_id'=>$user['user_id'],'username'=>$user['username'],'name'=>$user['name'],'role'=>$user['role']];
   audit('Successful login'); redirect('dashboard.php');
 } else {
   if($user){$attempts=(int)$user['failed_attempts']+1;$lock=$attempts>=MAX_LOGIN_ATTEMPTS?date('Y-m-d H:i:s',time()+LOCKOUT_MINUTES*60):null;
    db()->prepare('UPDATE users SET failed_attempts=?,locked_until=? WHERE user_id=?')->execute([$attempts,$lock,$user['user_id']]);}
   $error='Invalid credentials or inactive account.';
 }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Sign in · ResultHub</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"></head><body><div class="login-wrap"><div class="card login-card"><div class="text-center mb-4"><div class="brand-mark mx-auto mb-3" style="width:54px;height:54px;font-size:25px"><i class="bi bi-shield-check"></i></div><div class="login-brand">ResultHub</div><div class="text-secondary mt-1">Secure Student Result Management</div></div><?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post"><?= csrf_field() ?><div class="mb-3"><label class="form-label">Username</label><input class="form-control" name="username" required autocomplete="username"></div><div class="mb-4"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required autocomplete="current-password"></div><button class="btn btn-primary w-100 py-2">Sign in <i class="bi bi-arrow-right ms-1"></i></button></form><div class="text-center small text-secondary mt-4">Authorized users only · Your activity may be logged</div></div></div></body></html>