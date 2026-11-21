<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

// Mengambil data administrator yang sedang login
$q = db()->prepare(
    'SELECT *
     FROM users
     WHERE id_user = ?'
);

$q->execute([me()['id_user']]);

$u = $q->fetch();

$err = '';

// Mengambil daftar petugas
$daftar_petugas = db()->query(
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

// Memproses perubahan profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $name = trim($_POST['nama_lengkap'] ?? '');
    $phone = trim($_POST['no_hp'] ?? '');
    $address = trim($_POST['alamat'] ?? '');

    $old = $_POST['password_lama'] ?? '';
    $new = $_POST['password_baru'] ?? '';

    // Memeriksa nama administrator
    if ($name === '') {
        $err = 'Nama lengkap wajib diisi.';

    // Memeriksa kata sandi jika ingin menggantinya
    } elseif (
        $new !== '' &&
        (
            !password_verify($old, $u['password']) ||
            strlen($new) < 12
        )
    ) {
        $err = 'Untuk mengganti kata sandi, masukkan kata sandi lama yang benar dan kata sandi baru minimal 12 karakter.';

    } else {
        // Menyimpan profil sekaligus kata sandi baru
        if ($new !== '') {
            $q = db()->prepare(
                'UPDATE users
                 SET nama_lengkap = ?,
                     no_hp = ?,
                     alamat = ?,
                     password = ?,
                     updated_at = NOW()
                 WHERE id_user = ?'
            );

            $q->execute([
                $name,
                $phone,
                $address,
                password_hash($new, PASSWORD_DEFAULT),
                me()['id_user']
            ]);

        } else {
            // Menyimpan profil tanpa mengganti kata sandi
            $q = db()->prepare(
                'UPDATE users
                 SET nama_lengkap = ?,
                     no_hp = ?,
                     alamat = ?,
                     updated_at = NOW()
                 WHERE id_user = ?'
            );

            $q->execute([
                $name,
                $phone,
                $address,
                me()['id_user']
            ]);
        }

        // Memperbarui nama pada sesi login
        $_SESSION['user']['nama_lengkap'] = $name;

        // Mencatat aktivitas administrator
        log_activity(
            'ubah_profil_admin',
            'Profil administrator diperbarui'
        );

        flash('success', 'Profil administrator diperbarui.');

        go('profil.php');
    }
}

header_page('Profil Administrator');
?>

<!-- Form Profil Administrator -->
<section class="card">

    <h1>Profil Administrator</h1>

    <?php if ($err): ?>

        <div class="alert error">
            <?= e($err) ?>
        </div>

    <?php endif; ?>

    <form method="post">

        <?= csrf_input() ?>

        <label>
            Nama Lengkap

            <input
                type="text"
                name="nama_lengkap"
                maxlength="120"
                value="<?= e($u['nama_lengkap']) ?>"
                required
            >
        </label>

        <label>
            Email

            <input
                type="email"
                value="<?= e($u['email']) ?>"
                disabled
            >
        </label>

        <label>
            Nomor HP

            <input
                type="text"
                name="no_hp"
                maxlength="30"
                value="<?= e($u['no_hp'] ?? '') ?>"
            >
        </label>

        <label>
            Alamat

            <textarea
                name="alamat"
                rows="3"
            ><?= e($u['alamat'] ?? '') ?></textarea>
        </label>

        <hr>

        <h2>Ganti Kata Sandi</h2>

        <label>
            Kata Sandi Lama

            <input
                type="password"
                name="password_lama"
                autocomplete="current-password"
            >
        </label>

        <label>
            Kata Sandi Baru (Minimal 12 Karakter)

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

    </form>

</section>

<!-- Daftar Petugas -->
<section class="card">

    <h2>Daftar Petugas</h2>

    <p>
        Daftar akun petugas yang terdaftar di sistem LaporWarga.
    </p>

    <p>
        <a class="btn" href="petugas.php">
            Kelola Petugas
        </a>
    </p>

    <?php if ($daftar_petugas): ?>

        <div class="table-wrap">

            <table>

                <thead>
                    <tr>
                        <th>Nama Petugas</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($daftar_petugas as $petugas): ?>

                        <tr>

                            <td>
                                <?= e($petugas['nama_lengkap']) ?>
                            </td>

                            <td>
                                <?= e($petugas['email']) ?>
                            </td>

                            <td>
                                <?= e($petugas['no_hp'] ?? '-') ?>
                            </td>

                            <td>
                                <?= $petugas['status_akun']
                                    ? 'Aktif'
                                    : 'Nonaktif' ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="empty">
            Belum ada petugas yang terdaftar.
        </div>

    <?php endif; ?>

</section>

<?php footer_page(); ?>
