
<?php

require_once __DIR__ . '/../includes/functions.php';

// Pastikan pengguna memiliki role masyarakat.
need_role(['masyarakat']);

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    try {
        // Ambil data dari formulir.
        $title = trim($_POST['judul'] ?? '');
        $desc  = trim($_POST['deskripsi'] ?? '');
        $loc   = trim($_POST['lokasi_kejadian'] ?? '');
        $date  = $_POST['tanggal_kejadian'] ?? '';
        $cat   = (int) ($_POST['id_kategori'] ?? 0);

        // Validasi data wajib.
        if (
            $title === '' ||
            $desc === '' ||
            $loc === '' ||
            !valid_report_date($date) ||
            $cat < 1
        ) {
            throw new RuntimeException(
                'Lengkapi semua kolom wajib dan gunakan tanggal kejadian yang valid.'
            );
        }

        // Pastikan kategori masih aktif.
        $check = db()->prepare(
            'SELECT COUNT(*)
             FROM categories
             WHERE id_kategori = ?
             AND status_kategori = 1'
        );

        $check->execute([$cat]);

        if (!(int) $check->fetchColumn()) {
            throw new RuntimeException(
                'Kategori tidak aktif atau tidak valid.'
            );
        }

        // Unggah foto bukti jika ada.
        $photo = upload_photo();

        $pdo = db();
        $pdo->beginTransaction();

        // Buat nomor laporan unik.
        $code = 'LW-' . date('Ymd') . '-'
              . strtoupper(bin2hex(random_bytes(3)));

        // Simpan laporan baru.
        $stmt = $pdo->prepare(
            "INSERT INTO reports (
                nomor_laporan,
                id_pelapor,
                id_kategori,
                judul,
                deskripsi,
                lokasi_kejadian,
                tanggal_kejadian,
                foto_bukti,
                status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Menunggu Verifikasi')"
        );

        $stmt->execute([
            $code,
            me()['id_user'],
            $cat,
            $title,
            $desc,
            $loc,
            $date,
            $photo
        ]);

        $rid = (int) $pdo->lastInsertId();

        // Catat riwayat pembuatan laporan.
        $history = $pdo->prepare(
            'INSERT INTO report_history (
                id_laporan,
                id_pengguna,
                status_sebelumnya,
                status_baru,
                keterangan
            ) VALUES (?, ?, NULL, ?, ?)'
        );

        $history->execute([
            $rid,
            me()['id_user'],
            'Menunggu Verifikasi',
            'Laporan dibuat oleh masyarakat'
        ]);

        // Ambil semua admin yang akun-nya aktif.
        $admins = $pdo->query(
            "SELECT id_user
             FROM users
             WHERE role = 'admin_utama'
             AND status_akun = 1"
        )->fetchAll();

        // Kirim notifikasi kepada admin.
        foreach ($admins as $admin) {
            notify(
                (int) $admin['id_user'],
                'Laporan baru',
                'Laporan ' . $code . ' menunggu penugasan.',
                '/laporwarga/admin-utama/laporan.php'
            );
        }

        // Simpan transaksi ke database.
        $pdo->commit();

        // Catat aktivitas pengguna.
        log_activity('buat_laporan', $code);

        // Tampilkan pesan berhasil.
        flash(
            'success',
            'Laporan berhasil dikirim dengan nomor ' . $code
        );

        go('daftar_laporan.php');

    } catch (Throwable $e) {
        // Batalkan transaksi jika terjadi kegagalan.
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }

        // Tampilkan pesan kesalahan.
        $err = $e instanceof RuntimeException
            ? $e->getMessage()
            : 'Laporan gagal disimpan. Periksa data dan koneksi database.';
    }
}

// Ambil kategori yang masih aktif.
$cats = db()->query(
    'SELECT id_kategori, nama_kategori
     FROM categories
     WHERE status_kategori = 1
     ORDER BY nama_kategori'
)->fetchAll();

// Tampilkan halaman.
header_page('Buat Pengaduan');
?>

<section class="card">
    <h1>Buat pengaduan</h1>

    <?php if ($err): ?>
        <div class="alert error">
            <?= e($err) ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <?= csrf_input() ?>

        <label>
            Judul laporan
            <input
                type="text"
                name="judul"
                maxlength="180"
                required
            >
        </label>

        <label>
            Kategori
            <select name="id_kategori" required>
                <option value="">Pilih kategori</option>

                <?php foreach ($cats as $c): ?>
                    <option value="<?= $c['id_kategori'] ?>">
                        <?= e($c['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Lokasi kejadian
            <input
                type="text"
                name="lokasi_kejadian"
                maxlength="255"
                required
            >
        </label>

        <label>
            Tanggal kejadian
            <input
                type="date"
                name="tanggal_kejadian"
                max="<?= date('Y-m-d') ?>"
                required
            >
        </label>

        <label>
            Deskripsi
            <textarea
                name="deskripsi"
                rows="5"
                required
            ></textarea>
        </label>

        <label>
            Foto bukti (opsional, JPG/PNG/WebP maks. 3 MB)
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

        <button class="btn" type="submit">
            Kirim laporan
        </button>
    </form>
</section>

<?php footer_page(); ?>
