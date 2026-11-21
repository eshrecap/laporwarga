
<?php
require_once __DIR__ . '/../includes/functions.php';

need_role(['petugas']);

$id = (int)($_GET['id'] ?? 0);

// Aturan perubahan status laporan
$trans = [
    'Menunggu Verifikasi' => [
        'Perlu Perbaikan',
        'Ditolak',
        'Diterima'
    ],
    'Perlu Perbaikan' => [
        'Diterima',
        'Ditolak'
    ],
    'Diterima' => [
        'Sedang Diproses',
        'Selesai'
    ],
    'Sedang Diproses' => [
        'Selesai'
    ],
    'Ditolak' => [],
    'Selesai' => [],
    'Dibatalkan' => []
];

// Mengambil data laporan
$s = db()->prepare("
    SELECT
        r.*,
        r.STATUS AS status,
        c.nama_kategori,
        u.nama_lengkap AS pelapor
    FROM reports r
    JOIN categories c ON c.id_kategori = r.id_kategori
    JOIN users u ON u.id_user = r.id_pelapor
    WHERE r.id_laporan = ?
      AND r.id_petugas = ?
");

$s->execute([$id, me()['id_user']]);
$r = $s->fetch(PDO::FETCH_ASSOC);

if (!$r) {
    http_response_code(404);
    exit('Laporan tidak ditemukan atau bukan penugasan Anda.');
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $status = $_POST['status'] ?? '';
    $note = trim($_POST['keterangan'] ?? '');
    $response = trim($_POST['isi_tanggapan'] ?? '');

    $allowed = $trans[$r['status']] ?? [];

    if (!in_array($status, $allowed, true)) {
        $err = 'Perubahan status tidak diizinkan.';
    } elseif (
        in_array($status, ['Ditolak', 'Perlu Perbaikan'], true)
        && $note === ''
    ) {
        $err = 'Alasan penolakan atau permintaan perbaikan wajib diisi.';
    } else {
        try {
            $pdo = db();
            $pdo->beginTransaction();

            // Memperbarui status laporan
            $pdo->prepare("
                UPDATE reports
                SET STATUS = ?,
                    alasan_penolakan = ?,
                    updated_at = NOW()
                WHERE id_laporan = ?
                  AND id_petugas = ?
            ")->execute([
                $status,
                in_array($status, ['Ditolak', 'Perlu Perbaikan'], true)
                    ? $note
                    : null,
                $id,
                me()['id_user']
            ]);

            // Menyimpan riwayat perubahan status
            $pdo->prepare("
                INSERT INTO report_history
                (
                    id_laporan,
                    id_pengguna,
                    status_sebelumnya,
                    status_baru,
                    keterangan
                )
                VALUES (?, ?, ?, ?, ?)
            ")->execute([
                $id,
                me()['id_user'],
                $r['status'],
                $status,
                $note
            ]);

            // Menyimpan tanggapan petugas
            if ($response !== '') {
                $pdo->prepare("
                    INSERT INTO responses
                    (id_laporan, id_petugas, isi_tanggapan)
                    VALUES (?, ?, ?)
                ")->execute([
                    $id,
                    me()['id_user'],
                    $response
                ]);
            }

            // Memberitahukan masyarakat
            notify(
                (int)$r['id_pelapor'],
                'Perkembangan laporan ' . $r['nomor_laporan'],
                'Status laporan berubah menjadi ' . $status,
                '/laporwarga/masyarakat/detail_laporan.php?id=' . $id
            );

            // Memberitahukan admin utama
            $admins = $pdo->query("
                SELECT id_user
                FROM users
                WHERE role = 'admin_utama'
                  AND status_akun = 1
            ")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($admins as $a) {
                notify(
                    (int)$a['id_user'],
                    'Status laporan diperbarui',
                    'Petugas memperbarui laporan ' .
                    $r['nomor_laporan'] . ' menjadi ' . $status,
                    '/laporwarga/admin-utama/detail_laporan.php?id=' . $id
                );
            }

            $pdo->commit();

            log_activity(
                'ubah_status',
                $r['nomor_laporan'] . ' -> ' . $status
            );

            flash('success', 'Perubahan berhasil disimpan.');

            go('detail_laporan.php?id=' . $id);

        } catch (Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log('Gagal memperbarui laporan: ' . $e->getMessage());

            $err = 'Gagal menyimpan perubahan. Silakan periksa log error.';
        }
    }
}

header_page('Penanganan Laporan');
?>

<h1>Penanganan Laporan</h1>

<?php if ($err): ?>
    <div class="alert error">
        <?= e($err) ?>
    </div>
<?php endif; ?>

<section class="card">
    <h2><?= e($r['judul']) ?></h2>

    <p>
        <b><?= e($r['nomor_laporan']) ?></b>
        · <?= e($r['status']) ?>
    </p>

    <p>
        Pelapor: <?= e($r['pelapor']) ?>
        · Kategori: <?= e($r['nama_kategori']) ?>
    </p>

    <p>
        Lokasi: <?= e($r['lokasi_kejadian']) ?>
        · Tanggal: <?= e($r['tanggal_kejadian']) ?>
    </p>

    <p><?= nl2br(e($r['deskripsi'])) ?></p>

    <?php if (!empty($r['foto_bukti'])): ?>
        <a
            target="_blank"
            href="/laporwarga/uploads/laporan/<?= e($r['foto_bukti']) ?>"
        >
            Lihat foto bukti
        </a>
    <?php endif; ?>
</section>

<section class="card">
    <h2>Perbarui Penanganan</h2>

    <?php $pilihanStatus = $trans[$r['status']] ?? []; ?>

    <?php if (!empty($pilihanStatus)): ?>

        <form method="post">
            <?= csrf_input() ?>

            <label>Status</label>
            <select name="status" required>
                <?php foreach ($pilihanStatus as $o): ?>
                    <option value="<?= e($o) ?>">
                        <?= e($o) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>
                Keterangan / Alasan
                (wajib untuk penolakan atau perbaikan)
            </label>
            <textarea name="keterangan"></textarea>

            <label>Tanggapan untuk Masyarakat</label>
            <textarea name="isi_tanggapan"></textarea>

            <button type="submit" class="btn">
                Simpan Tindak Lanjut
            </button>
        </form>

    <?php else: ?>

        <p class="alert">
            Laporan ini sudah tidak memiliki perubahan status yang tersedia.
        </p>

    <?php endif; ?>
</section>

<?php footer_page(); ?>
