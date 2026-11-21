<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

$err = '';

// Pengaturan bawaan aplikasi
$defaults = [
    'nama_aplikasi' => 'LaporWarga',
    'kontak_layanan' => '',
    'deskripsi_beranda' => 'Sistem Informasi Pelayanan dan Pengaduan Masyarakat Berbasis E-Government'
];

// Memproses formulir pengaturan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    try {
        $q = db()->prepare(
            'INSERT INTO app_settings (kunci, nilai)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)'
        );

        foreach ($defaults as $key => $default) {
            $value = trim((string) ($_POST[$key] ?? ''));

            // Nama aplikasi wajib diisi
            if ($key === 'nama_aplikasi' && $value === '') {
                throw new RuntimeException(
                    'Nama aplikasi wajib diisi.'
                );
            }

            $q->execute([$key, $value]);
        }

        // Mencatat aktivitas admin
        log_activity(
            'ubah_pengaturan',
            'Konfigurasi dasar aplikasi diperbarui'
        );

        flash('success', 'Pengaturan berhasil disimpan.');

        go('pengaturan.php');

    } catch (Throwable $e) {
        $err = $e instanceof RuntimeException
            ? $e->getMessage()
            : 'Pengaturan gagal disimpan. Pastikan tabel app_settings sudah diimpor.';
    }
}

// Mengambil pengaturan dari database
$settings = $defaults;

try {
    $rows = db()->query(
        'SELECT kunci, nilai FROM app_settings'
    );

    foreach ($rows as $r) {
        $settings[$r['kunci']] = $r['nilai'];
    }

} catch (Throwable $e) {
    // Tetap menggunakan pengaturan bawaan jika database gagal dibaca
}

header_page('Pengaturan Aplikasi');
?>

<section class="card">

    <h1>Pengaturan Dasar Aplikasi</h1>

    <p class="muted">
        Pengaturan ini disimpan di database.
        Pengaturan koneksi MySQL tetap dilakukan melalui
        config/database.php.
    </p>

    <!-- Pesan Kesalahan -->
    <?php if ($err): ?>

        <div class="alert error">
            <?= e($err) ?>
        </div>

    <?php endif; ?>

    <!-- Form Pengaturan -->
    <form method="post">

        <?= csrf_input() ?>

        <label>
            Nama Aplikasi

            <input
                type="text"
                name="nama_aplikasi"
                maxlength="100"
                value="<?= e($settings['nama_aplikasi']) ?>"
                required
            >
        </label>

        <label>
            Kontak Layanan (Telepon/Email)

            <input
                type="text"
                name="kontak_layanan"
                maxlength="255"
                value="<?= e($settings['kontak_layanan']) ?>"
            >
        </label>

        <label>
            Deskripsi Beranda

            <textarea
                name="deskripsi_beranda"
                rows="4"
            ><?= e($settings['deskripsi_beranda']) ?></textarea>
        </label>

        <button class="btn" type="submit">
            Simpan Pengaturan
        </button>

    </form>

</section>

<?php footer_page(); ?>