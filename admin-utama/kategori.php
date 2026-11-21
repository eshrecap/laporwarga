<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

$err = '';

// Memproses formulir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $action = $_POST['action'] ?? '';

    // Tambah atau edit kategori
    if ($action === 'save') {
        $id = (int) ($_POST['id_kategori'] ?? 0);
        $name = trim($_POST['nama_kategori'] ?? '');
        $desc = trim($_POST['deskripsi'] ?? '');

        if ($name === '') {
            $err = 'Nama kategori wajib diisi.';
        } else {
            try {
                if ($id > 0) {
                    // Memperbarui kategori
                    $stmt = db()->prepare(
                        'UPDATE categories
                         SET nama_kategori = ?,
                             deskripsi = ?
                         WHERE id_kategori = ?'
                    );

                    $stmt->execute([$name, $desc, $id]);

                } else {
                    // Menambahkan kategori baru
                    $stmt = db()->prepare(
                        'INSERT INTO categories
                            (nama_kategori, deskripsi)
                         VALUES (?, ?)'
                    );

                    $stmt->execute([$name, $desc]);
                }

                flash('success', 'Kategori berhasil disimpan.');
                go('kategori.php');

            } catch (Throwable $e) {
                $err = 'Kategori gagal disimpan. Pastikan nama belum digunakan.';
            }
        }

    // Mengaktifkan atau menonaktifkan kategori
    } elseif ($action === 'toggle') {
        $id = (int) ($_POST['id_kategori'] ?? 0);

        if ($id > 0) {
            $stmt = db()->prepare(
                'UPDATE categories
                 SET status_kategori = IF(status_kategori = 1, 0, 1)
                 WHERE id_kategori = ?'
            );

            $stmt->execute([$id]);

            flash('success', 'Status kategori diperbarui.');
            go('kategori.php');
        }
    }
}

// Mengambil data kategori untuk diedit
$edit = null;

if (isset($_GET['edit'])) {
    $stmt = db()->prepare(
        'SELECT *
         FROM categories
         WHERE id_kategori = ?'
    );

    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

// Mengambil seluruh kategori
$rows = db()->query(
    'SELECT *
     FROM categories
     ORDER BY nama_kategori'
)->fetchAll();

header_page('Kelola Kategori');
?>

<h1>Kelola Kategori</h1>

<!-- Pesan Kesalahan -->
<?php if ($err !== ''): ?>

    <div class="alert error">
        <?= e($err) ?>
    </div>

<?php endif; ?>

<!-- Form Tambah dan Edit Kategori -->
<section class="card">

    <h2>
        <?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?>
    </h2>

    <form method="post">
        <?= csrf_input() ?>

        <input
            type="hidden"
            name="action"
            value="save"
        >

        <input
            type="hidden"
            name="id_kategori"
            value="<?= (int) ($edit['id_kategori'] ?? 0) ?>"
        >

        <label>
            Nama Kategori

            <input
                type="text"
                name="nama_kategori"
                value="<?= e($edit['nama_kategori'] ?? '') ?>"
                placeholder="Masukkan nama kategori"
                required
            >
        </label>

        <label>
            Deskripsi

            <textarea
                name="deskripsi"
                placeholder="Masukkan deskripsi kategori"
            ><?= e($edit['deskripsi'] ?? '') ?></textarea>
        </label>

        <button class="btn" type="submit">
            Simpan
        </button>

        <?php if ($edit): ?>

            <a class="btn outline" href="kategori.php">
                Batal
            </a>

        <?php endif; ?>

    </form>

</section>

<!-- Tabel Daftar Kategori -->
<div class="table-wrap">

    <table>
        <thead>
            <tr>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($rows as $r): ?>

                <tr>
                    <td>
                        <?= e($r['nama_kategori']) ?>
                    </td>

                    <td>
                        <?= e($r['deskripsi']) ?>
                    </td>

                    <td>
                        <?= $r['status_kategori'] ? 'Aktif' : 'Nonaktif' ?>
                    </td>

                    <td>
                        <a href="?edit=<?= (int) $r['id_kategori'] ?>">
                            Edit
                        </a>

                        <form class="inline" method="post">
                            <?= csrf_input() ?>

                            <input
                                type="hidden"
                                name="action"
                                value="toggle"
                            >

                            <input
                                type="hidden"
                                name="id_kategori"
                                value="<?= (int) $r['id_kategori'] ?>"
                            >

                            <button
                                type="submit"
                                class="linkbtn"
                                data-confirm="Yakin ingin mengubah status kategori ini?"
                            >
                                <?= $r['status_kategori']
                                    ? 'Nonaktifkan'
                                    : 'Aktifkan' ?>
                            </button>
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

</div>

<!-- Pesan Jika Belum Ada Kategori -->
<?php if (!$rows): ?>

    <div class="empty">
        Belum ada kategori. Silakan tambahkan kategori baru.
    </div>

<?php endif; ?>

<?php footer_page(); ?>
