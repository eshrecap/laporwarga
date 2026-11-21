<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

$err = '';

// Memproses penugasan laporan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $rid = (int) ($_POST['id_laporan'] ?? 0);
    $uid = (int) ($_POST['id_petugas'] ?? 0);

    // Memeriksa apakah petugas aktif dan valid
    $q = db()->prepare(
        "SELECT id_user
         FROM users
         WHERE id_user = ?
           AND role = 'petugas'
           AND status_akun = 1"
    );

    $q->execute([$uid]);

    if (!$q->fetch()) {
        $err = 'Pilih petugas aktif yang valid.';

    } else {
        // Mengambil data laporan
        $s = db()->prepare(
            'SELECT id_pelapor, nomor_laporan
             FROM reports
             WHERE id_laporan = ?'
        );

        $s->execute([$rid]);

        $r = $s->fetch();

        if ($r) {
            // Menyimpan penugasan petugas
            $update = db()->prepare(
                'UPDATE reports
                 SET id_petugas = ?,
                     updated_at = NOW()
                 WHERE id_laporan = ?'
            );

            $update->execute([$uid, $rid]);

            // Memberi notifikasi kepada petugas
            notify(
                $uid,
                'Penugasan Laporan Baru',
                'Anda mendapat tugas untuk laporan ' . $r['nomor_laporan'],
                '/laporwarga/petugas/daftar_laporan.php'
            );

            // Memberi notifikasi kepada pelapor
            notify(
                (int) $r['id_pelapor'],
                'Laporan Ditugaskan',
                'Laporan ' . $r['nomor_laporan'] . ' telah ditugaskan kepada petugas.',
                '/laporwarga/masyarakat/daftar_laporan.php'
            );

            // Mencatat aktivitas admin
            log_activity(
                'penugasan',
                $r['nomor_laporan'] . ' kepada petugas ' . $uid
            );

            flash('success', 'Penugasan berhasil disimpan.');

            go('penugasan.php');

        } else {
            $err = 'Laporan tidak ditemukan.';
        }
    }
}

// Mengambil ID laporan yang dipilih
$selected = (int) ($_GET['id'] ?? 0);

// Mengambil seluruh data laporan
$reports = db()->query(
    'SELECT
        id_laporan,
        nomor_laporan,
        judul,
        status,
        id_petugas
     FROM reports
     ORDER BY created_at DESC'
)->fetchAll();

// Mengambil daftar petugas yang aktif
$staff = db()->query(
    "SELECT id_user, nama_lengkap
     FROM users
     WHERE role = 'petugas'
       AND status_akun = 1
     ORDER BY nama_lengkap"
)->fetchAll();

// Menyiapkan nama petugas berdasarkan ID
$staffNames = [];

foreach ($staff as $x) {
    $staffNames[(int) $x['id_user']] = $x['nama_lengkap'];
}

header_page('Penugasan Laporan');
?>

<h1>Penugasan Laporan</h1>

<!-- Pesan Kesalahan -->
<?php if ($err): ?>

    <div class="alert error">
        <?= e($err) ?>
    </div>

<?php endif; ?>

<!-- Tabel Penugasan Laporan -->
<div class="table-wrap">

    <table>

        <thead>
            <tr>
                <th>Nomor Laporan</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Petugas</th>
                <th>Simpan Penugasan</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($reports as $r): ?>

                <tr
                    <?= $selected === (int) $r['id_laporan']
                        ? 'class="selected-row"'
                        : '' ?>
                >

                    <td>
                        <?= e($r['nomor_laporan']) ?>
                    </td>

                    <td>
                        <?= e($r['judul']) ?>
                    </td>

                    <td>
                        <?= e($r['status']) ?>
                    </td>

                    <td>
                        <?php
                        $currentStaff = $staffNames[
                            (int) ($r['id_petugas'] ?? 0)
                        ] ?? 'Belum ditugaskan';
                        ?>

                        <?= e($currentStaff) ?>
                    </td>

                    <td>

                        <form method="post">

                            <?= csrf_input() ?>

                            <input
                                type="hidden"
                                name="id_laporan"
                                value="<?= (int) $r['id_laporan'] ?>"
                            >

                            <select
                                name="id_petugas"
                                required
                            >
                                <option value="">
                                    Pilih Petugas
                                </option>

                                <?php foreach ($staff as $x): ?>

                                    <option
                                        value="<?= (int) $x['id_user'] ?>"
                                        <?= (int) $r['id_petugas'] === (int) $x['id_user']
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        <?= e($x['nama_lengkap']) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <button
                                class="btn small"
                                type="submit"
                            >
                                Simpan
                            </button>

                        </form>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<!-- Pesan Jika Belum Ada Laporan -->
<?php if (!$reports): ?>

    <div class="empty">
        Belum ada laporan yang dapat ditugaskan.
    </div>

<?php endif; ?>

<?php footer_page(); ?>
