<?php
require_once __DIR__.'/includes/functions.php';
need_login();
$u=me(); $pdo=db();
$log=$pdo->prepare('SELECT aktivitas,detail_aktivitas,created_at FROM activity_logs WHERE id_pengguna=? ORDER BY created_at DESC LIMIT 100');
$log->execute([$u['id_user']]); $activities=$log->fetchAll();
if($u['role']==='admin_utama'){
 $q=$pdo->query('SELECT h.*,r.nomor_laporan,r.judul,u.nama_lengkap AS pelaku FROM report_history h JOIN reports r ON r.id_laporan=h.id_laporan LEFT JOIN users u ON u.id_user=h.id_pengguna ORDER BY h.created_at DESC LIMIT 100');
} elseif($u['role']==='petugas'){
 $q=$pdo->prepare('SELECT h.*,r.nomor_laporan,r.judul,u.nama_lengkap AS pelaku FROM report_history h JOIN reports r ON r.id_laporan=h.id_laporan LEFT JOIN users u ON u.id_user=h.id_pengguna WHERE r.id_petugas=? ORDER BY h.created_at DESC LIMIT 100'); $q->execute([$u['id_user']]);
} else {
 $q=$pdo->prepare('SELECT h.*,r.nomor_laporan,r.judul,u.nama_lengkap AS pelaku FROM report_history h JOIN reports r ON r.id_laporan=h.id_laporan LEFT JOIN users u ON u.id_user=h.id_pengguna WHERE r.id_pelapor=? ORDER BY h.created_at DESC LIMIT 100'); $q->execute([$u['id_user']]);
}
$history=$q->fetchAll(); header_page('Riwayat Aktivitas');
?>
<h1>Riwayat Aktivitas & Laporan</h1>
<p>Catatan aktivitas akun dan perubahan status laporan ditampilkan secara kronologis.</p>
<section class="card"><h2>Aktivitas akun</h2>
<?php if(!$activities): ?><div class="empty">Belum ada aktivitas akun yang tercatat.</div><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Aktivitas</th><th>Detail</th><th>Waktu</th></tr></thead><tbody>
<?php foreach($activities as $a): ?><tr><td><?=e($a['aktivitas'])?></td><td><?=e($a['detail_aktivitas']??'-')?></td><td><?=e($a['created_at'])?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<section class="card"><h2>Riwayat perubahan laporan</h2>
<?php if(!$history): ?><div class="empty">Belum ada perubahan status laporan.</div><?php else: ?>
<div class="table-wrap"><table><thead><tr><th>Nomor</th><th>Judul</th><th>Perubahan</th><th>Keterangan</th><th>Oleh</th><th>Waktu</th></tr></thead><tbody>
<?php foreach($history as $h): ?><tr><td><?=e($h['nomor_laporan'])?></td><td><?=e($h['judul'])?></td><td><?=e($h['status_sebelumnya']??'Laporan dibuat')?> → <?=e($h['status_baru'])?></td><td><?=e($h['keterangan']??'-')?></td><td><?=e($h['pelaku']??'Sistem')?></td><td><?=e($h['created_at'])?></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php footer_page(); ?>
