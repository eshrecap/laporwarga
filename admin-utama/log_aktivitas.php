<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

// Mengambil data log aktivitas
$sql = '
    SELECT
        l.*,
        u.nama_lengkap
    FROM activity_logs l
    LEFT JOIN users u
        ON u.id_user = l.id_pengguna
    ORDER BY l.created_at DESC
    LIMIT 500
';

$rows = db()->query($sql)->fetchAll();

header_page('Log Aktivitas');
?>

<h1>Log Aktivitas Pengguna</h1>

<!-- Tabel Log Aktivitas -->
<div class="table-wrap">

    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Pengguna</th>
                <th>Aktivitas</th>
                <th>Detail</th>
                <th>Alamat IP</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($rows as $r): ?>

                <tr>
                    <td>
                        <?= e($r['created_at']) ?>
                    </td>

                    <td>
                        <?= e($r['nama_lengkap'] ?? 'Sistem') ?>
                    </td>

                    <td>
                        <?= e($r['aktivitas']) ?>
                    </td>

                    <td>
                        <?= e($r['detail_aktivitas']) ?>
                    </td>

                    <td>
                        <?= e($r['alamat_ip']) ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

</div>

<!-- Pesan Jika Log Kosong -->
<?php if (!$rows): ?>

    <div class="empty">
        Belum ada aktivitas.
    </div>

<?php endif; ?>

<?php footer_page(); ?>