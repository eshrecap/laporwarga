<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

$err = '';

// Memproses formulir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $action = $_POST['action'] ?? 'toggle';
    $id = (int) ($_POST['id_user'] ?? 0);

    // Edit data masyarakat
    if ($action === 'save') {
        $name = trim($_POST['nama_lengkap'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['no_hp'] ?? '');
        $address = trim($_POST['alamat'] ?? '');

        if (
            $id <= 0 ||
            $name === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            $err = 'Nama dan email valid wajib diisi.';
        } else {
            try {
                $stmt = db()->prepare(
                    "UPDATE users
                     SET nama_lengkap = ?,
                         email = ?,
                         no_hp = ?,
                         alamat = ?,
                         updated_at = NOW()
                     WHERE id_user = ?
                       AND role = 'masyarakat'"
                );

                $stmt->execute([
                    $name,
                    $email,
                    $phone,
                    $address,
                    $id
                ]);

                log_activity(
                    'edit_masyarakat',
                    'id=' . $id
                );

                flash('success', 'Data masyarakat diperbarui.');
                go('pengguna.php');

            } catch (Throwable $e) {
                $err = 'Data gagal diperbarui. Periksa kemungkinan email sudah digunakan.';
            }
        }

    // Mengaktifkan atau menonaktifkan akun
    } elseif ($action === 'toggle' && $id > 0) {
        $active = (int) ($_POST['status_akun'] ?? -1);

        if (in_array($active, [0, 1], true)) {
            $stmt = db()->prepare(
                "UPDATE users
                 SET status_akun = ?,
                     updated_at = NOW()
                 WHERE id_user = ?
                   AND role = 'masyarakat'"
            );

            $stmt->execute([$active, $id]);

            flash('success', 'Status akun masyarakat diperbarui.');
            go('pengguna.php');
        }
    }
}

// Mengambil data masyarakat untuk diedit
$edit = null;

if (isset($_GET['edit'])) {
    $stmt = db()->prepare(
        "SELECT id_user, nama_lengkap, email, no_hp, alamat
         FROM users
         WHERE id_user = ?
           AND role = 'masyarakat'"
    );

    $stmt->execute([(int) $_GET['edit']]);
    $edit = $stmt->fetch();
}

// Pencarian dan filter status
$q = trim($_GET['q'] ?? '');
$status = $_GET['status'] ?? '';

$where = ["role = 'masyarakat'"];
$args = [];

if ($q !== '') {
    $where[] = '(nama_lengkap LIKE ? OR email LIKE ?)';

    $args[] = '%' . $q . '%';
    $args[] = '%' . $q . '%';
}

if ($status === 'aktif') {
    $where[] = 'status_akun = 1';
} elseif ($status === 'nonaktif') {
    $where[] = 'status_akun = 0';
}

// Mengambil daftar akun masyarakat saja
$sql = 'SELECT
            id_user,
            nama_lengkap,
            email,
            no_hp,
            status_akun,
            created_at
        FROM users
        WHERE ' . implode(' AND ', $where) . '
        ORDER BY created_at DESC';

$stmt = db()->prepare($sql);
$stmt->execute($args);

$rows = $stmt->fetchAll();

header_page('Kelola Akun Masyarakat');
?>

<h1>Kelola Akun Masyarakat</h1>

<!-- Pesan kesalahan -->
<?php if ($err !== ''): ?>
    <div class="alert error">
        <?= e($err) ?>
    </div>
<?php endif; ?>

<!-- Form edit masyarakat -->
<?php if ($edit): ?>

    <section class="card">
        <h2>Edit Data Masyarakat</h2>

        <form method="post">
            <?= csrf_input() ?>

            <input type="hidden" name="action" value="save">

            <input
                type="hidden"
                name="id_user"
                value="<?= (int) $edit['id_user'] ?>"
            >

            <label>
                Nama Lengkap
                <input
                    type="text"
                    name="nama_lengkap"
                    value="<?= e($edit['nama_lengkap']) ?>"
                    required
                >
            </label>

            <label>
                Email
                <input
                    type="email"
                    name="email"
                    value="<?= e($edit['email']) ?>"
                    required
                >
            </label>

            <label>
                Nomor HP
                <input
                    type="text"
                    name="no_hp"
                    value="<?= e($edit['no_hp']) ?>"
                >
            </label>

            <label>
                Alamat
                <textarea name="alamat"><?= e($edit['alamat']) ?></textarea>
            </label>

            <button class="btn" type="submit">
                Simpan
            </button>

            <a class="btn outline" href="pengguna.php">
                Batal
            </a>
        </form>
    </section>

<?php endif; ?>

<!-- Form pencarian dan filter -->
<form class="search" method="get">

    <input
        type="text"
        name="q"
        value="<?= e($q) ?>"
        placeholder="Cari nama atau email"
    >

    <select name="status">
        <option value="">Semua status</option>

        <option
            value="aktif"
            <?= $status === 'aktif' ? 'selected' : '' ?>
        >
            Aktif
        </option>

        <option
            value="nonaktif"
            <?= $status === 'nonaktif' ? 'selected' : '' ?>
        >
            Nonaktif
        </option>
    </select>

    <button class="btn" type="submit">
        Cari
    </button>

</form>

<!-- Tabel akun masyarakat -->
<div class="table-wrap">

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($rows as $r): ?>

                <tr>
                    <td><?= e($r['nama_lengkap']) ?></td>
                    <td><?= e($r['email']) ?></td>
                    <td><?= e($r['no_hp']) ?></td>

                    <td>
                        <?= $r['status_akun'] ? 'Aktif' : 'Nonaktif' ?>
                    </td>

                    <td>
                        <a href="?edit=<?= (int) $r['id_user'] ?>">
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
                                name="id_user"
                                value="<?= (int) $r['id_user'] ?>"
                            >

                            <input
                                type="hidden"
                                name="status_akun"
                                value="<?= $r['status_akun'] ? 0 : 1 ?>"
                            >

                            <button
                                type="submit"
                                class="linkbtn"
                                data-confirm="Ubah status akun masyarakat ini?"
                            >
                                <?= $r['status_akun']
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

<!-- Pesan jika data kosong -->
<?php if (!$rows): ?>

    <div class="empty">
        Tidak ada akun masyarakat yang cocok.
    </div>

<?php endif; ?>

<?php footer_page(); ?>
