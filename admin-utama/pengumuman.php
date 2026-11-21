<?php

require_once __DIR__ . '/../includes/functions.php';

need_role(['admin_utama']);

$err = '';

// Memproses formulir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();

    $action = $_POST['action'] ?? 'save';
    $id = (int) ($_POST['id_pengumuman'] ?? 0);

    // Menghapus pengumuman
    if ($action === 'delete') {
        $q = db()->prepare(
            'DELETE FROM announcements
             WHERE id_pengumuman = ?'
        );

        $q->execute([$id]);

        log_activity('hapus_pengumuman', 'id=' . $id);

        flash('success', 'Pengumuman dihapus.');

        go('pengumuman.php');
    }

    // Mengambil data dari formulir
    $title = trim($_POST['judul'] ?? '');
    $body = trim($_POST['isi'] ?? '');
    $status = $_POST['status'] ?? 'Draft';

    // Memeriksa kelengkapan data
    if (
        $title === '' ||
        $body === '' ||
        !in_array($status, ['Draft', 'Publikasi'], true)
    ) {
        $err = 'Lengkapi judul, isi, dan status pengumuman.';
    } else {
        try {
            // Memperbarui pengumuman
            if ($id > 0) {
                $q = db()->prepare(
                    'UPDATE announcements
                     SET judul = ?,
                         isi = ?,
                         status = ?,
                         updated_at = NOW()
                     WHERE id_pengumuman = ?'
                );

                $q->execute([
                    $title,
                    $body,
                    $status,
                    $id
                ]);

            } else {
                // Menambahkan pengumuman baru
                $q = db()->prepare(
                    'INSERT INTO announcements
                        (judul, isi, id_admin, status)
                     VALUES (?, ?, ?, ?)'
                );

                $q->execute([
                    $title,
                    $body,
                    me()['id_user'],
                    $status
                ]);
            }

            // Mencatat aktivitas admin
            log_activity('simpan_pengumuman', $title);

            flash('success', 'Pengumuman berhasil disimpan.');

            go('pengumuman.php');

        } catch (Throwable $e) {
            $err = 'Pengumuman gagal disimpan.';
        }
    }
}

// Mengambil data pengumuman untuk diedit
$edit = null;

if (isset($_GET['edit'])) {
    $q = db()->prepare(
        'SELECT *
         FROM announcements
         WHERE id_pengumuman = ?'
    );

    $q->execute([(int) $_GET['edit']]);

    $edit = $q->fetch();
}

// Mengambil seluruh daftar pengumuman
$sql = '
    SELECT
        a.*,
        u.nama_lengkap AS admin
    FROM announcements a
    JOIN users u
        ON u.id_user = a.id_admin
    ORDER BY a.created_at DESC
';

$rows = db()->query($sql)->fetchAll();

header_page('Pengumuman');
?>

<h1>Kelola Pengumuman</h1>

<!-- Pesan Kesalahan -->
<?php if ($err): ?>

    <div class="alert error">
        <?= e($err) ?>
    </div>

<?php endif; ?>

<!-- Form Tambah dan Edit Pengumuman -->
<section class="card">

    <h2>
        <?= $edit ? 'Edit Pengumuman' : 'Buat Pengumuman' ?>
    </h2>

    <form method="post">

        <?= csrf_input() ?>

        <input
            type="hidden"
            name="id_pengumuman"
            value="<?= e($edit['id_pengumuman'] ?? '') ?>"
        >

        <label>
            Judul Pengumuman

            <input
                type="text"
                name="judul"
                maxlength="180"
                value="<?= e($edit['judul'] ?? '') ?>"
                required
            >
        </label>

        <label>
            Isi Pengumuman

            <textarea
                name="isi"
                rows="5"
                required
            ><?= e($edit['isi'] ?? '') ?></textarea>
        </label>

        <label>
            Status Pengumuman

            <select name="status">

                <option
                    value="Draft"
                    <?= ($edit['status'] ?? 'Draft') === 'Draft'
                        ? 'selected'
                        : '' ?>
                >
                    Draft
                </option>

                <option
                    value="Publikasi"
                    <?= ($edit['status'] ?? '') === 'Publikasi'
                        ? 'selected'
                        : '' ?>
                >
                    Publikasi
                </option>

            </select>
        </label>

        <button class="btn" type="submit">
            Simpan Pengumuman
        </button>

        <?php if ($edit): ?>

            <a
                class="btn outline"
                href="pengumuman.php"
            >
                Batal Edit
            </a>

        <?php endif; ?>

    </form>

</section>

<!-- Daftar Pengumuman -->
<h2>Daftar Pengumuman</h2>

<?php if (!$rows): ?>

    <div class="empty">
        Belum ada pengumuman.
    </div>

<?php endif; ?>

<?php foreach ($rows as $r): ?>

    <article class="card">

        <div class="row-between">

            <h3>
                <?= e($r['judul']) ?>
            </h3>

            <?= status_badge($r['status']) ?>

        </div>

        <p>
            <?= nl2br(e($r['isi'])) ?>
        </p>

        <small>
            <?= e($r['admin']) ?>
            ·
            <?= e($r['created_at']) ?>
        </small>

        <p>

            <a
                class="btn small outline"
                href="?edit=<?= (int) $r['id_pengumuman'] ?>"
            >
                Edit
            </a>

            <form
                class="inline"
                method="post"
                data-confirm="Hapus pengumuman ini?"
            >

                <?= csrf_input() ?>

                <input
                    type="hidden"
                    name="action"
                    value="delete"
                >

                <input
                    type="hidden"
                    name="id_pengumuman"
                    value="<?= (int) $r['id_pengumuman'] ?>"
                >

                <button
                    class="btn small danger"
                    type="submit"
                >
                    Hapus
                </button>

            </form>

        </p>

    </article>

<?php endforeach; ?>

<?php footer_page(); ?>
