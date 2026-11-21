<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

// Mengambil jumlah laporan berdasarkan status
$rows = db()->query(
    'SELECT
        status,
        COUNT(*) AS total
     FROM reports
     GROUP BY status
     ORDER BY status'
)->fetchAll();

header_page('Statistik Laporan');
?>

<!-- Judul Halaman -->
<h1>Statistik Laporan</h1>

<p class="muted">
    Berikut adalah jumlah laporan berdasarkan status penanganannya.
</p>

<!-- Tabel Statistik -->
<section class="section">

    <div class="table-wrap">

        <table>
            <thead>
                <tr>
                    <th>Status Laporan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($rows as $r): ?>

                    <tr>
                        <td>
                            <?= e($r['status']) ?>
                        </td>

                        <td>
                            <?= number_format((int) $r['total']) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    </div>

    <?php if (!$rows): ?>

        <div class="empty">
            Belum ada data laporan untuk ditampilkan.
        </div>

    <?php endif; ?>

</section>

<!-- Tombol Ekspor -->
<section class="section">

    <a class="btn" href="../reports_export.php">
        Ekspor CSV Semua Laporan
    </a>

</section>

<?php footer_page(); ?>
