
<?php

require_once __DIR__ . '/includes/functions.php';

// Jika pengguna sudah login, arahkan ke halaman sesuai role
if (me()) {
    go(role_home());
}

$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validasi CSRF
    check_csrf();

    // Ambil input dari form login
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Ambil data pengguna berdasarkan email dan status akun
    $stmt = db()->prepare(
        'SELECT
            id_user,
            nama_lengkap,
            email,
            `PASSWORD` AS password_hash,
            role
         FROM users
         WHERE email = ? AND status_akun = 1'
    );

    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verifikasi email dan kata sandi
    if ($user && password_verify($password, $user['password_hash'])) {

        // Perbarui ID session untuk keamanan
        session_regenerate_id(true);

        // Simpan informasi pengguna ke session
        $_SESSION['user'] = [
            'id_user'      => (int) $user['id_user'],
            'nama_lengkap' => $user['nama_lengkap'],
            'email'        => $user['email'],
            'role'         => $user['role']
        ];

        // Catat aktivitas login
        log_activity('login', 'Login berhasil');

        // Arahkan pengguna sesuai role
        go(role_home());

    } else {
        $err = 'Email atau kata sandi salah, atau akun tidak aktif.';
    }
}

// Tampilkan halaman login
header_page('Masuk');

?>

<section class="auth card">

    <h1>Masuk ke LaporWarga</h1>

    <?php if ($err): ?>
        <div class="alert error">
            <?= e($err) ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <?= csrf_input() ?>

        <label>
            Email
            <input
                type="email"
                name="email"
                required
                autofocus
            >
        </label>

        <label>
            Kata sandi
            <input
                type="password"
                name="password"
                required
            >
        </label>

        <button type="submit" class="btn">
            Masuk
        </button>

    </form>

    <p>
        Belum punya akun?
        <a href="register.php">Daftar masyarakat</a>
    </p>

</section>

<?php footer_page(); ?>
