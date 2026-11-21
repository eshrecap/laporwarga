<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['masyarakat']);

$pdo = db();
$id = (int)($_GET['id'] ?? 0);

/*
|--------------------------------------------------------------------------
| MENGAMBIL DETAIL LAPORAN
|--------------------------------------------------------------------------
*/

$s = $pdo->prepare("
    SELECT
        r.*,
        r.STATUS AS status,
        c.nama_kategori,
        u.nama_lengkap AS petugas
    FROM reports r
    JOIN categories c ON c.id_kategori = r.id_kategori
    LEFT JOIN users u ON u.id_user = r.id_petugas
    WHERE r.id_laporan = ?
    AND r.id_pelapor = ?
");

$s->execute([$id, me()['id_user']]);

$r = $s->fetch(PDO::FETCH_ASSOC);

if (!$r) {
    http_response_code(404);
    exit('Laporan tidak ditemukan.');
}

$err = '';

/*
|--------------------------------------------------------------------------
| PROSES PERBAIKAN LAPORAN
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    check_csrf();

    if ($r['status'] !== 'Perlu Perbaikan') {
        http_response_code(403);
        exit('Laporan tidak memenuhi syarat untuk diedit.');
    }

    $title = trim($_POST['judul'] ?? '');
    $desc = trim($_POST['deskripsi'] ?? '');
    $loc = trim($_POST['lokasi_kejadian'] ?? '');
    $cat = (int)($_POST['id_kategori'] ?? 0);
    $date = $_POST['tanggal_kejadian'] ?? '';

    if (
        $title === '' ||
        $desc === '' ||
        $loc === '' ||
        $cat < 1 ||
        !valid_report_date($date)
    ) {

        $err = 'Lengkapi seluruh kolom wajib dan gunakan tanggal kejadian yang valid.';

    } else {

        /*
        |--------------------------------------------------------------
        | VALIDASI KATEGORI
        |--------------------------------------------------------------
        */

        $check = $pdo->prepare("
            SELECT COUNT(*)
            FROM categories
            WHERE id_kategori = ?
            AND status_kategori = 1
        ");

        $check->execute([$cat]);

        if (!(int)$check->fetchColumn()) {

            $err = 'Kategori tidak aktif atau tidak valid.';

        } else {

            try {

                $photo = upload_photo();

                $pdo->beginTransaction();

                /*
                |------------------------------------------------------
                | UPDATE DATA LAPORAN
                |------------------------------------------------------
                */

                $update = $pdo->prepare("
                    UPDATE reports SET
                        judul = ?,
                        deskripsi = ?,
                        lokasi_kejadian = ?,
                        id_kategori = ?,
                        tanggal_kejadian = ?,
                        foto_bukti = COALESCE(?, foto_bukti),
                        STATUS = 'Menunggu Verifikasi',
                        alasan_penolakan = NULL,
                        updated_at = NOW()
                    WHERE id_laporan = ?
                    AND id_pelapor = ?
                    AND STATUS = 'Perlu Perbaikan'
                ");

                $update->execute([
                    $title,
                    $desc,
                    $loc,
                    $cat,
                    $date,
                    $photo,
                    $id,
                    me()['id_user']
                ]);

                if ($update->rowCount() < 1) {
                    throw new RuntimeException(
                        'Laporan gagal diperbarui. Silakan muat ulang halaman.'
                    );
                }

                /*
                |------------------------------------------------------
                | MENYIMPAN RIWAYAT LAPORAN
                |------------------------------------------------------
                */

                $history = $pdo->prepare("
                    INSERT INTO report_history
                    (
                        id_laporan,
                        id_pengguna,
                        status_sebelumnya,
                        status_baru,
                        keterangan
                    )
                    VALUES (?, ?, ?, ?, ?)
                ");

                $history->execute([
                    $id,
                    me()['id_user'],
                    'Perlu Perbaikan',
                    'Menunggu Verifikasi',
                    'Pelapor memperbaiki informasi laporan'
                ]);

                /*
                |------------------------------------------------------
                | NOTIFIKASI ADMIN UTAMA
                |------------------------------------------------------
                */

                $admins = $pdo->query("
                    SELECT id_user
                    FROM users
                    WHERE role = 'admin_utama'
                    AND status_akun = 1
                ")->fetchAll(PDO::FETCH_ASSOC);

                foreach ($admins as $admin) {

                    notify(
                        (int)$admin['id_user'],
                        'Laporan diperbaiki',
                        'Laporan ' . $r['nomor_laporan'] . ' telah diperbaiki.',
                        '/laporwarga/admin-utama/laporan.php'
                    );

                }

                /*
                |------------------------------------------------------
                | SIMPAN TRANSAKSI
                |------------------------------------------------------
                */

                $pdo->commit();

                log_activity(
                    'perbaiki_laporan',
                    $r['nomor_laporan']
                );

                flash(
                    'success',
                    'Laporan diperbarui dan dikirim ulang untuk verifikasi.'
                );

                go('detail_laporan.php?id=' . $id);

            } catch (Throwable $e) {

                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                $err = $e instanceof RuntimeException
                    ? $e->getMessage()
                    : 'Laporan gagal diperbarui. Periksa data dan koneksi database.';

            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| MENGAMBIL RIWAYAT STATUS
|--------------------------------------------------------------------------
*/

$h = $pdo->prepare("
    SELECT
        h.*,
        u.nama_lengkap
    FROM report_history h
    LEFT JOIN users u ON u.id_user = h.id_pengguna
    WHERE h.id_laporan = ?
    ORDER BY h.created_at DESC
");

$h->execute([$id]);

$history = $h->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| MENGAMBIL TANGGAPAN PETUGAS
|--------------------------------------------------------------------------
*/

$q = $pdo->prepare("
    SELECT
        x.*,
        u.nama_lengkap
    FROM responses x
    JOIN users u ON u.id_user = x.id_petugas
    WHERE x.id_laporan = ?
    ORDER BY x.created_at DESC
");

$q->execute([$id]);

$res = $q->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| MENGAMBIL KATEGORI AKTIF
|--------------------------------------------------------------------------
*/

$cats = $pdo->query("
    SELECT
        id_kategori,
        nama_kategori
    FROM categories
    WHERE status_kategori = 1
    ORDER BY nama_kategori
")->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| TAMPILAN DETAIL LAPORAN
|--------------------------------------------------------------------------
*/

header_page('Detail Laporan');

?>

<h1>Detail Laporan</h1>

<section class="card">

    <p>
        <b>Nomor:</b>
        <?= e($r['nomor_laporan']) ?>
    </p>

    <h2><?= e($r['judul']) ?></h2>

    <p>
        <?= status_badge((string)$r['status']) ?>
    </p>

    <p>
        <b>Kategori:</b>
        <?= e($r['nama_kategori']) ?>
    </p>

    <p>
        <b>Petugas:</b>
        <?= e($r['petugas'] ?? 'Belum ditugaskan') ?>
    </p>

    <p>
        <b>Lokasi:</b>
        <?= e($r['lokasi_kejadian']) ?>
    </p>

    <p>
        <b>Tanggal kejadian:</b>
        <?= e($r['tanggal_kejadian']) ?>
    </p>

    <p>
        <?= nl2br(e($r['deskripsi'])) ?>
    </p>

    <?php if (!empty($r['foto_bukti'])): ?>

        <a
            href="/laporwarga/uploads/laporan/<?= e($r['foto_bukti']) ?>"
            target="_blank"
            rel="noopener"
        >
            Lihat Foto Bukti
        </a>

    <?php endif; ?>

    <?php if (!empty($r['alasan_penolakan'])): ?>

        <p>
            <b>Alasan Penolakan/Perbaikan:</b>
            <?= e($r['alasan_penolakan']) ?>
        </p>

    <?php endif; ?>

</section>


<!-- RIWAYAT STATUS -->

<h2>Riwayat Status</h2>

<?php if (!$history): ?>

    <div class="empty">
        Belum ada riwayat status.
    </div>

<?php endif; ?>

<?php foreach ($history as $x): ?>

    <article class="card">

        <b><?= e($x['status_baru']) ?></b>

        <p>
            <?= nl2br(e($x['keterangan'] ?? '')) ?>
        </p>

        <small>
            <?= e($x['nama_lengkap'] ?? 'Sistem') ?>
            ·
            <?= e($x['created_at']) ?>
        </small>

    </article>

<?php endforeach; ?>


<!-- TANGGAPAN PETUGAS -->

<h2>Tanggapan Petugas</h2>

<?php if (!$res): ?>

    <p class="muted">
        Belum ada tanggapan.
    </p>

<?php endif; ?>

<?php foreach ($res as $x): ?>

    <article class="card">

        <p>
            <?= nl2br(e($x['isi_tanggapan'])) ?>
        </p>

        <small>
            <?= e($x['nama_lengkap']) ?>
            ·
            <?= e($x['created_at']) ?>
        </small>

    </article>

<?php endforeach; ?>


<!-- FORM PERBAIKAN LAPORAN -->

<?php if ($r['status'] === 'Perlu Perbaikan'): ?>

    <section class="card">

        <h2>Perbaiki Laporan</h2>

        <?php if ($err): ?>

            <div class="alert error">
                <?= e($err) ?>
            </div>

        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">

            <?= csrf_input() ?>

            <label>
                Judul

                <input
                    type="text"
                    name="judul"
                    maxlength="180"
                    value="<?= e($r['judul']) ?>"
                    required
                >
            </label>

            <label>
                Kategori

                <select name="id_kategori" required>

                    <?php foreach ($cats as $c): ?>

                        <option
                            value="<?= (int)$c['id_kategori'] ?>"
                            <?= ((int)$r['id_kategori'] === (int)$c['id_kategori']) ? 'selected' : '' ?>
                        >
                            <?= e($c['nama_kategori']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>
            </label>

            <label>
                Lokasi

                <input
                    type="text"
                    name="lokasi_kejadian"
                    maxlength="255"
                    value="<?= e($r['lokasi_kejadian']) ?>"
                    required
                >
            </label>

            <label>
                Tanggal Kejadian

                <input
                    type="date"
                    name="tanggal_kejadian"
                    max="<?= date('Y-m-d') ?>"
                    value="<?= e($r['tanggal_kejadian']) ?>"
                    required
                >
            </label>

            <label>
                Deskripsi

                <textarea
                    name="deskripsi"
                    rows="5"
                    required
                ><?= e($r['deskripsi']) ?></textarea>
            </label>

            <label>
                Foto Bukti Baru (Opsional, JPG/PNG/WebP maks. 3 MB)

                <input
                    type="file"
                    name="foto_bukti"
                    accept="image/jpeg,image/png,image/webp"
                    data-preview
                >
            </label>

            <img
                data-image-preview
                class="preview"
                alt="Pratinjau foto"
                hidden
            >

            <button type="submit" class="btn">
                Simpan Perbaikan dan Kirim Ulang
            </button>

        </form>

    </section>

<?php endif; ?>


<!-- PEMBATALAN LAPORAN -->

<?php if ($r['status'] === 'Menunggu Verifikasi'): ?>

    <form
        class="card"
        method="post"
        action="../actions/proses_laporan.php"
        data-confirm="Batalkan laporan ini?"
    >

        <?= csrf_input() ?>

        <input
            type="hidden"
            name="aksi"
            value="hapus"
        >

        <input
            type="hidden"
            name="id_laporan"
            value="<?= $id ?>"
        >

        <p>
            Laporan yang masih menunggu verifikasi
            dapat dibatalkan.
        </p>

        <button type="submit" class="btn danger">
            Batalkan Laporan
        </button>

    </form>

<?php endif; ?>

<?php footer_page(); ?>
