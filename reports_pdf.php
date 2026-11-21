
<?php

require_once __DIR__ . '/includes/functions.php';

need_role(['admin_utama']);

// Mengambil filter
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

// Filter status
if ($st === 'Diproses') {
    $where[] = "r.status IN ('Diterima','Sedang Diproses')";
} elseif (in_array($st, status_options(), true)) {
    $where[] = 'r.status = ?';
    $args[] = $st;
}

// Filter kategori
if ($cat > 0) {
    $where[] = 'r.id_kategori = ?';
    $args[] = $cat;
}

// Filter tanggal
if ($from !== '' && valid_date_format($from)) {
    $where[] = 'DATE(r.created_at) >= ?';
    $args[] = $from;
}

if ($to !== '' && valid_date_format($to)) {
    $where[] = 'DATE(r.created_at) <= ?';
    $args[] = $to;
}

// Query laporan
$sql = '
    SELECT
        r.nomor_laporan,
        r.judul,
        u.nama_lengkap AS pelapor,
        c.nama_kategori,
        p.nama_lengkap AS petugas,
        r.status,
        r.created_at
    FROM reports r
    JOIN users u ON u.id_user = r.id_pelapor
    LEFT JOIN users p ON p.id_user = r.id_petugas
    LEFT JOIN categories c ON c.id_kategori = r.id_kategori
    WHERE ' . implode(' AND ', $where) . '
    ORDER BY r.created_at DESC
';

$stmt = db()->prepare($sql);
$stmt->execute($args);
$rows = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan LaporWarga</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
        }

        h2, p {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #eeeeee;
        }

        .print-button {
            padding: 10px 18px;
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }

        @media print {
            .print-button {
                display: none;
            }

            body {
                margin: 10px;
            }
        }
    </style>
</head>

<body>

    <button class="print-button" onclick="window.print()">
        Cetak / Simpan PDF
    </button>

    <h2>LAPORAN PENGADUAN LAPORWARGA</h2>

    <p>Data Seluruh Laporan Pengaduan Masyarakat</p>

    <p>
        Tanggal Cetak:
        <?= date('d-m-Y H:i') ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Laporan</th>
                <th>Judul</th>
                <th>Pelapor</th>
                <th>Kategori</th>
                <th>Petugas</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>

        <tbody>

            <?php if ($rows): ?>

                <?php $no = 1; ?>

                <?php foreach ($rows as $r): ?>

                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($r['nomor_laporan']) ?></td>
                        <td><?= e($r['judul']) ?></td>
                        <td><?= e($r['pelapor']) ?></td>
                        <td><?= e($r['nama_kategori'] ?? '-') ?></td>
                        <td><?= e($r['petugas'] ?? 'Belum ditugaskan') ?></td>
                        <td><?= e($r['status']) ?></td>
                        <td><?= e($r['created_at']) ?></td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="8" style="text-align:center">
                        Tidak ada data laporan.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>
    </table>

</body>
</html>
