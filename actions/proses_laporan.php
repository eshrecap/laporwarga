<?php
require_once __DIR__.'/../includes/functions.php';
need_role(['masyarakat']);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') go('/laporwarga/masyarakat/daftar_laporan.php');
check_csrf();
if (($_POST['aksi'] ?? '') !== 'batalkan') go('/laporwarga/masyarakat/daftar_laporan.php');
$id=(int)($_POST['id_laporan'] ?? 0); $pdo=db();
$pdo->beginTransaction();
$stmt=$pdo->prepare("SELECT * FROM reports WHERE id_laporan=? AND id_pelapor=? AND status IN ('Menunggu Verifikasi','Perlu Perbaikan') FOR UPDATE");
$stmt->execute([$id,me()['id_user']]); $report=$stmt->fetch();
if (!$report) { $pdo->rollBack(); flash('error','Laporan tidak dapat dibatalkan karena sudah diproses atau bukan milik Anda.'); go('/laporwarga/masyarakat/daftar_laporan.php'); }
try {
 $pdo->prepare("UPDATE reports SET status='Dibatalkan',alasan_penolakan=NULL,updated_at=NOW() WHERE id_laporan=? AND id_pelapor=?")->execute([$id,me()['id_user']]);
 $pdo->prepare("INSERT INTO report_history(id_laporan,id_pengguna,status_sebelumnya,status_baru,keterangan) VALUES(?,?,?,?,?)")->execute([$id,me()['id_user'],$report['status'],'Dibatalkan','Dibatalkan oleh Pelapor']);
 $admins=$pdo->query("SELECT id_user FROM users WHERE role='admin_utama' AND status_akun=1")->fetchAll(PDO::FETCH_COLUMN);
 foreach($admins as $adminId) notify((int)$adminId,'Laporan dibatalkan',$report['nomor_laporan'].' dibatalkan oleh pelapor.','/laporwarga/admin-utama/laporan.php');
 if(!empty($report['id_petugas'])) notify((int)$report['id_petugas'],'Laporan dibatalkan',$report['nomor_laporan'].' dibatalkan oleh pelapor.','/laporwarga/petugas/daftar_laporan.php');
 $pdo->commit(); log_activity('batalkan_laporan',$report['nomor_laporan']); flash('success','Laporan berhasil dibatalkan. Riwayatnya tetap tersimpan.');
} catch(Throwable $e) { if($pdo->inTransaction())$pdo->rollBack(); error_log('Cancel report failed: '.$e->getMessage()); flash('error','Laporan gagal dibatalkan. Silakan coba lagi.'); }
go('/laporwarga/masyarakat/daftar_laporan.php');
