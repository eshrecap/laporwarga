<?php

require_once __DIR__ . '/includes/functions.php';

// Beranda dan dashboard disatukan: pengguna yang sudah masuk langsung ke dashboard sesuai peran.
if (me()) {
    go(role_home());
}

header_page('Beranda');

$pdo = db();

// Menghitung total laporan selain laporan ditolak
$total = (int) $pdo->query(
    "SELECT COUNT(*)
     FROM reports
     WHERE status <> 'Ditolak'"
)->fetchColumn();

// Menghitung laporan yang selesai
$done = (int) $pdo->query(
    "SELECT COUNT(*)
     FROM reports
     WHERE status = 'Selesai'"
)->fetchColumn();

// Mengambil kategori aktif
$cats = $pdo->query(
    "SELECT nama_kategori
     FROM categories
     WHERE status_kategori = 1
     ORDER BY nama_kategori"
)->fetchAll();

// Pengaturan bawaan aplikasi
$settings = [
    'nama_aplikasi' => 'LaporWarga',
    'kontak_layanan' => '',
    'deskripsi_beranda' =>
        'LaporWarga membantu masyarakat menyampaikan pengaduan lingkungan dan memantau tindak lanjut secara transparan.'
];

// Mengambil pengaturan dari database
try {
    $query = $pdo->query(
        'SELECT kunci, nilai FROM app_settings'
    );

    foreach ($query as $setting) {
        $settings[$setting['kunci']] = $setting['nilai'];
    }
} catch (Throwable $e) {
    // Gunakan pengaturan bawaan jika data tidak tersedia
}

// Mengambil pengumuman yang sudah dipublikasikan
$news = $pdo->query(
    "SELECT judul, isi, created_at
     FROM announcements
     WHERE status = 'Publikasi'
     ORDER BY created_at DESC
     LIMIT 4"
)->fetchAll();

?>

<!-- Bagian Beranda -->
<section class="hero">

    <div>
        <span class="eyebrow">
            Pelayanan publik digital
        </span>

        <h1>
            Sampaikan laporan,
            <em>bangun perubahan.</em>
        </h1>

        <p>
            <?= e(
                $settings['deskripsi_beranda']
                ?? 'LaporWarga membantu masyarakat menyampaikan pengaduan lingkungan dan memantau tindak lanjut secara transparan.'
            ) ?>
        </p>

        <a class="btn" href="login.php">
            Masuk
        </a>

        <a class="btn outline" href="register.php">
            Daftar masyarakat
        </a>
    </div>

    <div class="hero-card">
        <div class="hero-icon">✓</div>

        <h3>Suara warga berarti</h3>

        <p>
            Setiap laporan tercatat dan dapat dipantau
            sesuai hak akses.
        </p>
    </div>

</section>

<!-- Statistik Laporan -->
<section class="stats">

    <div class="stat">
        <b><?= number_format($total) ?></b>
        <span>Laporan tercatat</span>
    </div>

    <div class="stat">
        <b><?= number_format($done) ?></b>
        <span>Laporan selesai</span>
    </div>

    <div class="stat">
        <b><?= count($cats) ?></b>
        <span>Kategori aktif</span>
    </div>

</section>

<!-- Cara Kerja Pengaduan -->
<section class="section" id="cara-kerja">

    <h2>Cara Kerja Pengaduan</h2>

    <div class="cards">

        <article class="card">
            <b>01 · Kirim laporan</b>

            <p>
                Isi informasi kejadian dan lampirkan
                foto jika diperlukan.
            </p>
        </article>

        <article class="card">
            <b>02 · Verifikasi</b>

            <p>
                Petugas memeriksa laporan dan dapat
                meminta perbaikan informasi.
            </p>
        </article>

        <article class="card">
            <b>03 · Pantau tindak lanjut</b>

            <p>
                Pelapor dapat melihat status, riwayat,
                dan tanggapan petugas.
            </p>
        </article>

    </div>

</section>

<!-- Kategori Pengaduan -->
<section class="section" id="kategori">

    <h2>Kategori Pengaduan</h2>

    <div class="chips">

        <?php foreach ($cats as $c): ?>

            <span class="chip">
                <?= e($c['nama_kategori']) ?>
            </span>

        <?php endforeach; ?>

        <?php if (!$cats): ?>

            <p class="muted">
                Belum ada kategori pengaduan yang tersedia.
            </p>

        <?php endif; ?>

    </div>

</section>

<!-- Kontak Layanan -->
<?php if (!empty($settings['kontak_layanan'])): ?>

    <section class="section">

        <h2>Kontak Layanan</h2>

        <p>
            <?= e($settings['kontak_layanan']) ?>
        </p>

    </section>

<?php endif; ?>

<!-- Pengumuman -->
<section class="section">

    <h2>Pengumuman</h2>

    <?php if (!$news): ?>

        <p class="muted">
            Belum ada pengumuman yang dipublikasikan.
        </p>

    <?php else: ?>

        <div class="cards">

            <?php foreach ($news as $n): ?>

                <article class="card">

                    <b>
                        <?= e($n['judul']) ?>
                    </b>

                    <p>
                        <?= nl2br(e($n['isi'])) ?>
                    </p>

                    <small>
                        <?= e($n['created_at']) ?>
                    </small>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>

<?php footer_page(); ?>
