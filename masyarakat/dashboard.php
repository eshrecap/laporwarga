<?php
require_once __DIR__ . '/../includes/functions.php';
need_role(['masyarakat']);

$id = (int) me()['id_user'];
$stmt = db()->prepare('SELECT status, COUNT(*) AS jumlah FROM reports WHERE id_pelapor = ? GROUP BY status');
$stmt->execute([$id]);
$counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

$total = array_sum(array_map('intval', $counts));

// Laporan yang dibatalkan tetap dihitung dan tersedia di riwayat,
// tetapi tidak ditampilkan pada daftar laporan aktif di dashboard.
$stmt = db()->prepare("SELECT id_laporan, nomor_laporan, judul, status, created_at
                       FROM reports
                       WHERE id_pelapor = ? AND status <> 'Dibatalkan'
                       ORDER BY created_at DESC
                       LIMIT 8");
$stmt->execute([$id]);
$rows = $stmt->fetchAll();

header_page('Dashboard Masyarakat');
?>

<h1>Dashboard masyarakat</h1>
<p>Halo, <?= e(me()['nama_lengkap']) ?>. Pantau laporan yang kamu kirim di sini.</p>

<div class="stats" aria-label="Statistik laporan. Klik kartu untuk melihat daftar laporan sesuai status.">
    <a class="stat stat-link" href="daftar_laporan.php" title="Lihat semua laporan" aria-label="Lihat semua <?= $total ?> laporan">
        <b><?= $total ?></b>
        <span>Total laporan</span>
        <small class="stat-hint">Klik untuk melihat semua</small>
    </a>

    <?php foreach (status_options() as $status):
        $jumlah = (int) ($counts[$status] ?? 0);
    ?>
        <a class="stat stat-link"
           href="daftar_laporan.php?status=<?= rawurlencode($status) ?>"
           title="Lihat laporan dengan status <?= e($status) ?>"
           aria-label="Lihat <?= $jumlah ?> laporan berstatus <?= e($status) ?>">
            <b><?= $jumlah ?></b>
            <span><?= e($status) ?></span>
            <small class="stat-hint">Klik untuk melihat detail</small>
        </a>
    <?php endforeach; ?>
</div>

<p>
    <a class="btn" href="buat_laporan.php">+ Buat pengaduan</a>
    <a class="btn outline" href="notifikasi.php">Notifikasi</a>
    <a class="btn outline" href="profil.php">Profil</a>
</p>

<h2>Laporan terbaru</h2>
<?php if (!$rows): ?>
    <div class="empty">Belum ada laporan aktif. Silakan buat pengaduan atau periksa riwayat laporan.</div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nomor</th><th>Judul</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= e($r['nomor_laporan']) ?></td>
                    <td><?= e($r['judul']) ?></td>
                    <td><?= status_badge($r['status']) ?></td>
                    <td><?= e($r['created_at']) ?></td>
                    <td><a href="detail_laporan.php?id=<?= (int) $r['id_laporan'] ?>">Detail</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php footer_page(); ?>
