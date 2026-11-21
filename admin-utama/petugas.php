<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

$err = '';

// Memproses formulir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id_user'] ?? 0);

    // Membuat akun petugas
    if ($action === 'create') {
        $name = trim($_POST['nama_lengkap'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $pw = $_POST['password'] ?? '';
        $phone = trim($_POST['no_hp'] ?? '');
        $address = trim($_POST['alamat'] ?? '');

        if (
            $name === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($pw) < 12
        ) {
            $err = 'Nama dan email valid serta kata sandi minimal 12 karakter wajib diisi.';
        } else {
            try {
                $q = db()->prepare(
                    "INSERT INTO users
                        (nama_lengkap, email, password, no_hp, alamat, role)
                     VALUES (?, ?, ?, ?, ?, 'petugas')"
                );

                $q->execute([
                    $name,
                    $email,
                    password_hash($pw, PASSWORD_DEFAULT),
                    $phone,
                    $address
                ]);

                log_activity('buat_akun_petugas', $email);

                flash('success', 'Akun petugas berhasil dibuat.');

                go('petugas.php');

            } catch (Throwable $e) {
                $err = 'Akun gagal dibuat. Periksa apakah email sudah digunakan.';
            }
        }

    // Mengedit data petugas
    } elseif ($action === 'save') {
        $name = trim($_POST['nama_lengkap'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['no_hp'] ?? '');
        $address = trim($_POST['alamat'] ?? '');
        $pw = $_POST['password_baru'] ?? '';

        if (
            $id <= 0 ||
            $name === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            ($pw !== '' && strlen($pw) < 12)
        ) {
            $err = 'Nama dan email valid wajib diisi. Kata sandi baru harus minimal 12 karakter.';
        } else {
            try {
                // Memperbarui data sekaligus kata sandi
                if ($pw !== '') {
                    $q = db()->prepare(
                        "UPDATE users
                         SET nama_lengkap = ?,
                             email = ?,
                             no_hp = ?,
                             alamat = ?,
                             password = ?,
                             updated_at = NOW()
                         WHERE id_user = ?
                           AND role = 'petugas'"
                    );

                    $q->execute([
                        $name,
                        $email,
                        $phone,
                        $address,
                        password_hash($pw, PASSWORD_DEFAULT),
                        $id
                    ]);

                } else {
                    // Memperbarui data tanpa mengganti kata sandi
                    $q = db()->prepare(
                        "UPDATE users
                         SET nama_lengkap = ?,
                             email = ?,
                             no_hp = ?,
                             alamat = ?,
                             updated_at = NOW()
                         WHERE id_user = ?
                           AND role = 'petugas'"
                    );

                    $q->execute([
                        $name,
                        $email,
                        $phone,
                        $address,
                        $id
                    ]);
                }

                // Memastikan akun petugas memang ditemukan
                if ($q->rowCount() === 0) {
                    $check = db()->prepare(
                        "SELECT id_user
                         FROM users
                         WHERE id_user = ?
                           AND role = 'petugas'"
                    );

                    $check->execute([$id]);

                    if (!$check->fetch()) {
                        $err = 'Akun petugas tidak ditemukan.';
                    }
                }

                if ($err === '') {
                    log_activity(
                        'edit_akun_petugas',
                        'id=' . $id
                    );

                    flash('success', 'Data petugas berhasil diperbarui.');

                    go('petugas.php');
                }

            } catch (Throwable $e) {
                $err = 'Data petugas gagal diperbarui. Periksa apakah email sudah digunakan.';
            }
        }

    // Mengaktifkan atau menonaktifkan akun
    } elseif ($action === 'toggle') {
        $q = db()->prepare(
            "UPDATE users
             SET status_akun = IF(status_akun = 1, 0, 1),
                 updated_at = NOW()
             WHERE id_user = ?
               AND role = 'petugas'"
        );

        $q->execute([$id]);

        if ($q->rowCount() > 0) {
            log_activity(
                'ubah_status_petugas',
                'id=' . $id
            );

            flash('success', 'Status petugas berhasil diperbarui.');

        } else {
            flash('error', 'Akun petugas tidak ditemukan atau status tidak berubah.');
        }

        go('petugas.php');
    }
}

// Mengambil data petugas untuk diedit
$edit = null;

if (isset($_GET['edit'])) {
    $q = db()->prepare(
        "SELECT
            id_user,
            nama_lengkap,
            email,
            no_hp,
            alamat
         FROM users
         WHERE id_user = ?
           AND role = 'petugas'"
    );

    $q->execute([(int) $_GET['edit']]);

    $edit = $q->fetch();

    if (!$edit) {
        $err = 'Akun petugas tidak ditemukan.';
    }
}

// Mengambil daftar seluruh petugas
$rows = db()->query(
    "SELECT
        id_user,
        nama_lengkap,
        email,
        no_hp,
        status_akun,
        created_at
     FROM users
     WHERE role = 'petugas'
     ORDER BY created_at DESC"
)->fetchAll();

header_page('Kelola Petugas');
?>

<h1>Kelola Petugas</h1>

<!-- Pesan Kesalahan -->
<?php if ($err): ?>

    <div class="alert error">
        <?= e($err) ?>
    </div>

<?php endif; ?>

<!-- Form Edit Petugas -->
<?php if ($edit): ?>

    <section class="card">

        <h2>Edit Akun Petugas</h2>

        <form method="post">

            <?= csrf_input() ?>

            <input
                type="hidden"
                name="action"
                value="save"
            >

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
                    value="<?= e($edit['no_hp'] ?? '') ?>"
                >
            </label>

            <label>
                Alamat

                <textarea
                    name="alamat"
                    rows="3"
                ><?= e($edit['alamat'] ?? '') ?></textarea>
            </label>

            <label>
                Kata Sandi Baru (Opsional, minimal 12 karakter)

                <input
                    type="password"
                    name="password_baru"
                    minlength="12"
                    autocomplete="new-password"
                >
            </label>

            <button class="btn" type="submit">
                Simpan Perubahan
            </button>

            <a
                class="btn outline"
                href="petugas.php"
            >
                Batal
            </a>

        </form>

    </section>

<?php endif; ?>

<!-- Form Membuat Akun Petugas -->
<section class="card">

    <h2>Buat Akun Petugas</h2>

    <form method="post">

        <?= csrf_input() ?>

        <input
            type="hidden"
            name="action"
            value="create"
        >

        <label>
            Nama Lengkap

            <input
                type="text"
                name="nama_lengkap"
                required
            >
        </label>

        <label>
            Email

            <input
                type="email"
                name="email"
                required
            >
        </label>

        <label>
            Nomor HP

            <input
                type="text"
                name="no_hp"
            >
        </label>

        <label>
            Alamat

            <textarea
                name="alamat"
                rows="3"
            ></textarea>
        </label>

        <label>
            Kata Sandi Awal (minimal 12 karakter)

            <input
                type="password"
                name="password"
                minlength="12"
                autocomplete="new-password"
                required
            >
        </label>

        <button class="btn" type="submit">
            Buat Petugas
        </button>

    </form>

</section>

<!-- Tabel Daftar Petugas -->
<h2>Daftar Akun Petugas</h2>

<div class="table-wrap">

    <table>

        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Nomor HP</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($rows as $r): ?>

                <tr>

                    <td>
                        <?= e($r['nama_lengkap']) ?>
                    </td>

                    <td>
                        <?= e($r['email']) ?>
                    </td>

                    <td>
                        <?= e($r['no_hp'] ?? '-') ?>
                    </td>

                    <td>
                        <?= $r['status_akun'] ? 'Aktif' : 'Nonaktif' ?>
                    </td>

                    <td>

                        <a href="?edit=<?= (int) $r['id_user'] ?>">
                            Edit
                        </a>

                        <form
                            class="inline"
                            method="post"
                        >

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

                            <button
                                class="linkbtn"
                                type="submit"
                                data-confirm="Ubah status petugas ini?"
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

<!-- Pesan Jika Belum Ada Petugas -->
<?php if (!$rows): ?>

    <div class="empty">
        Belum ada petugas.
    </div>

<?php endif; ?>

<?php footer_page(); ?>
