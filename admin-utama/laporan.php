<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

// Mengambil filter dari formulir
$q = trim($_GET['q'] ?? '');
$st = $_GET['status'] ?? '';
$cat = (int) ($_GET['kategori'] ?? 0);
$from = $_GET['dari'] ?? '';
$to = $_GET['sampai'] ?? '';

// Menyiapkan kondisi pencarian
$where = [
    '(r.nomor_laporan LIKE ?
      OR r.judul LIKE ?
      OR u.nama_lengkap LIKE ?)'
];

$args = [
    '%' . $q . '%',
    '%' . $q . '%',
    '%' . $q . '%'
];

// Filter berdasarkan status
if ($st === 'Diproses') {
    $where[] = "r.status IN ('Diterima','Sedang Diproses')";
} elseif (in_array($st, status_options(), true)) {
    $where[] = 'r.status = ?';
    $args[] = $st;
}

// Filter berdasarkan kategori
if ($cat > 0) {
    $where[] = 'r.id_kategori = ?';
    $args[] = $cat;
}

// Filter tanggal mulai
if ($from !== '' && valid_date_format($from)) {
    $where[] = 'DATE(r.created_at) >= ?';
    $args[] = $from;
}

// Filter tanggal akhir
if ($to !== '' && valid_date_format($to)) {
    $where[] = 'DATE(r.created_at) <= ?';
    $args[] = $to;
}

// Query utama laporan
$base = '
    FROM reports r
    JOIN users u
        ON u.id_user = r.id_pelapor
    LEFT JOIN users p
        ON p.id_user = r.id_petugas
    LEFT JOIN categories c
        ON c.id_kategori = r.id_kategori
    WHERE ' . implode(' AND ', $where);

// Menghitung total laporan
$count = db()->prepare(
    'SELECT COUNT(*) ' . $base
);

$count->execute($args);

$pg = paginate((int) $count->fetchColumn());

// Mengambil data laporan sesuai halaman
$sql = '
    SELECT
        r.id_laporan,
        r.nomor_laporan,
        r.judul,
        r.status,
        r.created_at,
        u.nama_lengkap AS pelapor,
        p.nama_lengkap AS petugas,
        c.nama_kategori
    ' . $base . '
    ORDER BY r.created_at DESC
    LIMIT ' . (int) $pg['per_page'] . '
    OFFSET ' . (int) $pg['offset'];

$stmt = db()->prepare($sql);
$stmt->execute($args);

$rows = $stmt->fetchAll();

// Mengambil daftar kategori untuk filter
$cats = db()->query(
    'SELECT id_kategori, nama_kategori
     FROM categories
     ORDER BY nama_kategori'
)->fetchAll();

// Menyiapkan parameter ekspor sesuai filter
$exportParams = array_intersect_key(
    $_GET,
    array_flip(['q', 'status', 'kategori', 'dari', 'sampai'])
);

header_page('Semua Laporan');
?>

<h1>Semua Laporan</h1>

<!-- Form Pencarian dan Filter -->
<form class="search" method="get">

    <input
        type="text"
        name="q"
        value="<?= e($q) ?>"
        placeholder="Cari nomor, judul, atau pelapor"
    >

    <select name="status">
        <option value="">Semua Status</option>

        <?php foreach (status_options() as $x): ?>

            <option
                value="<?= e($x) ?>"
                <?= $st === $x ? 'selected' : '' ?>
            >
                <?= e($x) ?>
            </option>

        <?php endforeach; ?>
    </select>

    <select name="kategori">
        <option value="0">Semua Kategori</option>

        <?php foreach ($cats as $c): ?>

            <option
                value="<?= (int) $c['id_kategori'] ?>"
                <?= $cat === (int) $c['id_kategori'] ? 'selected' : '' ?>
            >
                <?= e($c['nama_kategori']) ?>
            </option>

        <?php endforeach; ?>
    </select>

    <label>
        Dari
        <input
            type="date"
            name="dari"
            value="<?= e($from) ?>"
        >
    </label>

    <label>
        Sampai
        <input
            type="date"
            name="sampai"
            value="<?= e($to) ?>"
        >
    </label>

    <button class="btn" type="submit">
        Filter
    </button>

    <a class="btn outline" href="laporan.php">
        Reset
    </a>

</form>

<!-- Tombol Ekspor -->
<div style="display: flex; gap: 10px; align-items: center; margin-bottom: 15px;">

    <a
        class="btn outline"
        href="../reports_export.php?<?= e(http_build_query($exportParams)) ?>"
    >
        Ekspor CSV
    </a>

    <a
        class="btn outline"
        href="../reports_pdf.php?<?= e(http_build_query($exportParams)) ?>"
        target="_blank"
    >
        Ekspor PDF
    </a>

</div>

<!-- Tabel Semua Laporan -->
<div class="table-wrap">

    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Judul</th>
                <th>Pelapor</th>
                <th>Kategori</th>
                <th>Petugas</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($rows as $r): ?>

                <tr>
                    <td>
                        <?= e($r['nomor_laporan']) ?>
                    </td>

                    <td>
                        <?= e($r['judul']) ?>
                    </td>

                    <td>
                        <?= e($r['pelapor']) ?>
                    </td>

                    <td>
                        <?= e($r['nama_kategori'] ?? '-') ?>
                    </td>

                    <td>
                        <?= e($r['petugas'] ?? 'Belum ditugaskan') ?>
                    </td>

                    <td>
                        <?= status_badge($r['status']) ?>
                    </td>

                    <td>
                        <?= e($r['created_at']) ?>
                    </td>

                    <td>
                        <a
                            href="detail_laporan.php?id=<?= (int) $r['id_laporan'] ?>"
                        >
                            Detail
                        </a>

                        |

                        <a
                            href="penugasan.php?id=<?= (int) $r['id_laporan'] ?>"
                        >
                            Tugaskan
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

</div>

<!-- Pesan Jika Data Kosong -->
<?php if (!$rows): ?>

    <div class="empty">
        Belum ada laporan yang sesuai dengan filter.
    </div>

<?php endif; ?>

<!-- Navigasi Halaman -->
<?php pagination_links($pg); ?>

<?php footer_page(); ?>
