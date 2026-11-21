<?php
require_once __DIR__.'/includes/functions.php';
$count=(int)db()->query("SELECT COUNT(*) FROM users WHERE role='admin_utama'")->fetchColumn();
if($count>=3){http_response_code(403);exit('Batas maksimal 3 akun Admin Utama telah tercapai.');}
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf(); $name=trim($_POST['nama_lengkap']??''); $email=strtolower(trim($_POST['email']??'')); $pw=$_POST['password']??'';
 if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pw)<12) $err='Nama/email harus valid dan kata sandi minimal 12 karakter.';
 else {
  try {
   $pdo=db(); $pdo->beginTransaction();
   $n=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin_utama' FOR UPDATE")->fetchColumn();
   if($n>=3){$pdo->rollBack();$err='Batas maksimal 3 akun Admin Utama telah tercapai.';}
   else {$q=$pdo->prepare("INSERT INTO users(nama_lengkap,email,password,role) VALUES(?,?,?,'admin_utama')");$q->execute([$name,$email,password_hash($pw,PASSWORD_DEFAULT)]);$pdo->commit();log_activity('setup_admin','Administrator dibuat');echo 'Administrator berhasil dibuat. Hapus setup_admin.php demi keamanan.';exit;}
  } catch(Throwable $e){if(isset($pdo)&&$pdo->inTransaction())$pdo->rollBack();$err='Email mungkin sudah digunakan atau akun gagal dibuat.';error_log($e->getMessage());}
 }
}
header_page('Setup Administrator');?><section class="auth card"><h1>Buat administrator</h1><p>Maksimal 3 akun Admin Utama. Hapus file ini setelah selesai.</p><?php if($err):?><div class="alert error"><?=e($err)?></div><?php endif;?><form method="post"><?=csrf_input()?><label>Nama lengkap<input name="nama_lengkap" required></label><label>Email<input type="email" name="email" required></label><label>Kata sandi (minimal 12 karakter)<input type="password" name="password" minlength="12" required></label><button class="btn">Buat administrator</button></form></section><?php footer_page();?>
