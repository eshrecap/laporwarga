<?php
require_once __DIR__.'/../includes/functions.php';
need_login();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
$stmt=db()->prepare('SELECT id_notifikasi AS id,judul,pesan FROM notifications WHERE id_pengguna=? AND is_read=0 ORDER BY id_notifikasi DESC LIMIT 1');
$stmt->execute([me()['id_user']]);
echo json_encode($stmt->fetch() ?: ['id'=>0], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
