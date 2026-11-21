<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

$pdo = db();
$stats = [];

// Menghitung jumlah laporan berdasarkan status
foreach (status_options() as $st) {
    $s = $pdo->prepare(
        'SELECT COUNT(*) FROM reports WHERE status = ?'
    );

    $s->execute([$st]);
    $stats[$st] = (int) $s->fetchColumn();
}

// Menghitung jumlah masyarakat
$users = (int) $pdo->query(
    "SELECT COUNT(*) FROM users WHERE role = 'masyarakat'"
)->fetchColumn();

// Menghitung jumlah petugas aktif
$staff = (int) $pdo->query(
    "SELECT COUNT(*) FROM users
     WHERE role = 'petugas' AND status_akun = 1"
)->fetchColumn();

// Mengambil data laporan per bulan
$months = $pdo->query(
    "SELECT
        DATE_FORMAT(created_at, '%Y-%m') AS bulan,
        COUNT(*) AS total
     FROM reports
     WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
     GROUP BY bulan
     ORDER BY bulan"
)->fetchAll();

// Mengambil data laporan per kategori
$cats = $pdo->query(
    'SELECT
        c.nama_kategori,
        COUNT(r.id_laporan) AS total
     FROM categories c
     LEFT JOIN reports r
        ON r.id_kategori = c.id_kategori
     GROUP BY c.id_kategori
     ORDER BY total DESC'
)->fetchAll();

// Mengambil laporan yang belum selesai lebih dari 7 hari
$late = $pdo->query(
    "SELECT
        r.id_laporan,
        r.nomor_laporan,
        r.judul,
        r.status,
        r.updated_at,
        u.nama_lengkap AS pelapor
     FROM reports r
     JOIN users u
        ON u.id_user = r.id_pelapor
     WHERE r.status NOT IN ('Ditolak', 'Selesai')
       AND r.updated_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
     ORDER BY r.updated_at ASC
     LIMIT 10"
)->fetchAll();

header_page('Dashboard Administrator');
?>

<!-- Judul Dashboard -->
<h1>Dashboard Administrator Utama</h1>

<!-- Statistik Dashboard -->
<div class="stats">

    <a class="stat" href="laporan.php" title="Lihat seluruh laporan"><b><?= array_sum($stats) ?></b><span>Total laporan</span></a>
    <?php
    $cards = [
      'Pending' => ['Menunggu Verifikasi'],
      'Diproses' => ['Diterima','Sedang Diproses'],
      'Selesai' => ['Selesai'],
      'Ditolak' => ['Ditolak']
    ];
    foreach ($cards as $label => $statuses):
      $value = 0; foreach ($statuses as $statusName) $value += $stats[$statusName] ?? 0;
      $url = count($statuses) === 1 ? 'laporan.php?status='.rawurlencode($statuses[0]) : 'laporan.php?status=Diproses';
    ?>
      <a class="stat" href="<?=e($url)?>" title="Lihat laporan berstatus <?=$label?>"><b><?=$value?></b><span><?=e($label)?></span></a>
    <?php endforeach; ?>

    <div class="stat">
        <b><?= $users ?></b>
        <span>Masyarakat</span>
    </div>

    <div class="stat">
        <b><?= $staff ?></b>
        <span>Petugas aktif</span>
    </div>

</div>

<!-- Grafik Laporan -->
<div class="grid2">

    <section class="card">
        <h2>Laporan per Bulan</h2>

        <canvas
            id="monthChart"
            data-labels="<?= e(json_encode(array_column($months, 'bulan'))) ?>"
            data-values="<?= e(json_encode(array_map('intval', array_column($months, 'total')))) ?>"
        ></canvas>
    </section>

    <section class="card">
        <h2>Laporan per Kategori</h2>

        <canvas
            id="categoryChart"
            data-labels="<?= e(json_encode(array_column($cats, 'nama_kategori'))) ?>"
            data-values="<?= e(json_encode(array_map('intval', array_column($cats, 'total')))) ?>"
        ></canvas>
    </section>

</div>

<!-- Laporan yang Terlambat Ditangani -->
<section class="section">

    <h2>Laporan Belum Ditangani Lebih dari 7 Hari</h2>

    <?php if (!$late): ?>

        <div class="empty">
            Tidak ada laporan yang melewati batas 7 hari.
        </div>

    <?php else: ?>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Nomor</th>
                    <th>Judul</th>
                    <th>Pelapor</th>
                    <th>Status</th>
                    <th>Pembaruan terakhir</th>
                    <th>Aksi</th>
                </tr>

                <?php foreach ($late as $l): ?>
                    <tr>
                        <td><?= e($l['nomor_laporan']) ?></td>
                        <td><?= e($l['judul']) ?></td>
                        <td><?= e($l['pelapor']) ?></td>
                        <td><?= status_badge($l['status']) ?></td>
                        <td><?= e($l['updated_at']) ?></td>

                        <td>
                            <a href="detail_laporan.php?id=<?= (int) $l['id_laporan'] ?>">
                                Tinjau
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>
        </div>

    <?php endif; ?>

</section>

<!-- Menu Pintasan Administrator -->
<div class="cards">

    <a class="card" href="laporan.php">
        Kelola laporan
    </a>

    <a class="card" href="penugasan.php">
        Penugasan petugas
    </a>

    <a class="card" href="pengguna.php">
        Akun Masyarakat
    </a>

    <a class="card" href="petugas.php">
        Akun Petugas
    </a>

    <a class="card" href="kategori.php">
        Kategori
    </a>

    <a class="card" href="pengumuman.php">
        Pengumuman
    </a>

    <a class="card" href="log_aktivitas.php">
        Log aktivitas
    </a>

    <a class="card" href="laporan-statistik.php">
        Statistik &amp; ekspor
    </a>

    <a class="card" href="profil.php">
        Profil administrator
    </a>

    <a class="card" href="pengaturan.php">
        Pengaturan aplikasi
    </a>

</div>

<!-- Library Grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- JavaScript Dashboard -->
<script src="/laporwarga/assets/js/dashboard.js"></script>

<?php footer_page(); ?>
