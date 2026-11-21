<?php

require_once __DIR__ . '/../includes/functions.php';
need_role(['admin_utama']);

$pdo = db();

// Mengambil ID laporan dari URL
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(404);
    exit('ID laporan tidak valid.');
}

// Mengambil informasi laporan
$stmt = $pdo->prepare("
    SELECT
        r.*,
        r.STATUS AS status,
        c.nama_kategori,
        p.nama_lengkap AS nama_petugas,
        u.nama_lengkap AS nama_pelapor
    FROM reports r
    LEFT JOIN categories c
        ON c.id_kategori = r.id_kategori
    LEFT JOIN users u
        ON u.id_user = r.id_pelapor
    LEFT JOIN users p
        ON p.id_user = r.id_petugas
    WHERE r.id_laporan = ?
    LIMIT 1
");

$stmt->execute([$id]);
$laporan = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika laporan tidak ditemukan
if (!$laporan) {
    http_response_code(404);
    exit('Data laporan tidak ditemukan.');
}

// Mengambil riwayat perubahan laporan
$historyStmt = $pdo->prepare("
    SELECT
        h.*,
        u.nama_lengkap AS nama_pengguna
    FROM report_history h
    LEFT JOIN users u
        ON u.id_user = h.id_pengguna
    WHERE h.id_laporan = ?
    ORDER BY h.created_at DESC
");

$historyStmt->execute([$id]);
$riwayat = $historyStmt->fetchAll(PDO::FETCH_ASSOC);

// Mengambil tanggapan petugas
$responseStmt = $pdo->prepare("
    SELECT
        rs.*,
        u.nama_lengkap AS nama_petugas
    FROM responses rs
    LEFT JOIN users u
        ON u.id_user = rs.id_petugas
    WHERE rs.id_laporan = ?
    ORDER BY rs.created_at DESC
");

$responseStmt->execute([$id]);
$tanggapan = $responseStmt->fetchAll(PDO::FETCH_ASSOC);

header_page('Detail Laporan');

?>

<section class="section">

    <h1>Detail Laporan</h1>

    <p>Informasi lengkap pengaduan masyarakat.</p>

    <article class="card">

        <h2><?= e($laporan['judul']) ?></h2>

        <p>
            <b>Nomor Laporan:</b>
            <?= e($laporan['nomor_laporan']) ?>
        </p>

        <p>
            <b>Status:</b>
            <?= e($laporan['status']) ?>
        </p>

        <hr>

        <h3>Informasi Pelapor</h3>

        <p>
            <b>Nama Pelapor:</b>
            <?= e($laporan['nama_pelapor'] ?? '-') ?>
        </p>

        <hr>

        <h3>Informasi Pengaduan</h3>

        <p>
            <b>Kategori:</b>
            <?= e($laporan['nama_kategori'] ?? '-') ?>
        </p>

        <p>
            <b>Tanggal Kejadian:</b>
            <?= e($laporan['tanggal_kejadian'] ?? '-') ?>
        </p>

        <p>
            <b>Lokasi Kejadian:</b>
            <?= e($laporan['lokasi_kejadian'] ?? '-') ?>
        </p>

        <p>
            <b>Deskripsi:</b>
        </p>

        <p>
            <?= nl2br(e($laporan['deskripsi'])) ?>
        </p>

        <p>
            <b>Foto Bukti:</b>
        </p>

        <?php if (!empty($laporan['foto_bukti'])): ?>

            <p>
                <a
                    href="/laporwarga/uploads/laporan/<?= rawurlencode($laporan['foto_bukti']) ?>"
                    target="_blank"
                >
                    Lihat Foto Bukti
                </a>
            </p>

            <img
                src="/laporwarga/uploads/laporan/<?= rawurlencode($laporan['foto_bukti']) ?>"
                alt="Foto Bukti Laporan"
                style="max-width:100%; height:auto; border-radius:10px;"
            >

        <?php else: ?>

            <p class="muted">
                Tidak ada foto bukti.
            </p>

        <?php endif; ?>

        <hr>

        <h3>Informasi Petugas</h3>

        <p>
            <b>Petugas yang Ditugaskan:</b>
            <?= e($laporan['nama_petugas'] ?? 'Belum ditugaskan') ?>
        </p>

        <?php if (!empty($laporan['alasan_penolakan'])): ?>

            <p>
                <b>Alasan Penolakan / Perbaikan:</b>
            </p>

            <p>
                <?= nl2br(e($laporan['alasan_penolakan'])) ?>
            </p>

        <?php endif; ?>

    </article>

    <section class="card">

        <h2>Riwayat Laporan</h2>

        <?php if (!$riwayat): ?>

            <p class="muted">
                Belum ada riwayat perubahan laporan.
            </p>

        <?php else: ?>

            <?php foreach ($riwayat as $h): ?>

                <article class="card">

                    <p>
                        <b>Status Sebelumnya:</b>
                        <?= e($h['status_sebelumnya'] ?? '-') ?>
                    </p>

                    <p>
                        <b>Status Baru:</b>
                        <?= e($h['status_baru'] ?? '-') ?>
                    </p>

                    <p>
                        <b>Diubah Oleh:</b>
                        <?= e($h['nama_pengguna'] ?? '-') ?>
                    </p>

                    <p>
                        <b>Keterangan:</b>
                        <?= e($h['keterangan'] ?? '-') ?>
                    </p>

                    <small>
                        <?= e($h['created_at']) ?>
                    </small>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </section>

    <section class="card">

        <h2>Tanggapan Petugas</h2>

        <?php if (!$tanggapan): ?>

            <p class="muted">
                Belum ada tanggapan dari petugas.
            </p>

        <?php else: ?>

            <?php foreach ($tanggapan as $t): ?>

                <article class="card">

                    <p>
                        <b>Petugas:</b>
                        <?= e($t['nama_petugas'] ?? '-') ?>
                    </p>

                    <p>
                        <?= nl2br(e($t['isi_tanggapan'])) ?>
                    </p>

                    <small>
                        <?= e($t['created_at']) ?>
                    </small>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </section>

</section>

<?php footer_page(); ?>
