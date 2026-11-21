
<?php
require_once __DIR__.'/../includes/functions.php';

// Khusus masyarakat
need_role(['masyarakat']);

$q = trim($_GET['q'] ?? '');
$st = $_GET['status'] ?? '';
$cat = (int)($_GET['kategori'] ?? 0);
$from = $_GET['dari'] ?? '';
$to = $_GET['sampai'] ?? '';

// Hanya menampilkan laporan milik masyarakat yang login
$where = ['r.id_pelapor = ?'];
$args = [me()['id_user']];

// Pencarian laporan
if ($q !== '') {
    $where[] = '(r.nomor_laporan LIKE ? OR r.judul LIKE ?)';
    array_push($args, '%'.$q.'%', '%'.$q.'%');
}

// Filter status
if (in_array($st, status_options(), true)) {
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

$base = ' FROM reports r
          JOIN categories c ON c.id_kategori = r.id_kategori
          WHERE '.implode(' AND ', $where);

// Menghitung jumlah laporan
$cnt = db()->prepare('SELECT COUNT(*)'.$base);
$cnt->execute($args);

$pg = paginate((int)$cnt->fetchColumn());

// Mengambil laporan masyarakat
$sql = 'SELECT
        r.id_laporan,
        r.nomor_laporan,
        r.judul,
        r.status,
        r.created_at,
        c.nama_kategori
        '.$base.'
        ORDER BY r.created_at DESC
        LIMIT '.$pg['per_page'].'
        OFFSET '.$pg['offset'];

$s = db()->prepare($sql);
$s->execute($args);
$rows = $s->fetchAll();

// Mengambil kategori
$cats = db()->query(
    'SELECT id_kategori, nama_kategori
     FROM categories
     WHERE status_kategori = 1
     ORDER BY nama_kategori'
)->fetchAll();

header_page('Riwayat Laporan Saya');
?>

<h1>Riwayat Laporan Saya</h1>
<p>Pantau perkembangan laporan pengaduan yang telah Anda kirimkan.</p>

<form class="search" method="get">

    <input
        name="q"
        placeholder="Cari nomor atau judul laporan"
        value="<?=e($q)?>"
    >

    <select name="status">
        <option value="">Semua Status</option>
        <?php foreach(status_options() as $o): ?>
            <option value="<?=e($o)?>" <?=($st===$o?'selected':'')?>>
                <?=e($o)?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="kategori">
        <option value="0">Semua Kategori</option>
        <?php foreach($cats as $c): ?>
            <option
                value="<?=$c['id_kategori']?>"
                <?=($cat===(int)$c['id_kategori']?'selected':'')?>>
                <?=e($c['nama_kategori'])?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>
        Dari
        <input type="date" name="dari" value="<?=e($from)?>">
    </label>

    <label>
        Sampai
        <input type="date" name="sampai" value="<?=e($to)?>">
    </label>

    <button class="btn">Filter</button>

</form>

<div class="table-wrap">
<table>
    <tr>
        <th>Nomor Laporan</th>
        <th>Judul</th>
        <th>Kategori</th>
        <th>Status</th>
        <th>Tanggal</th>
        <th>Aksi</th>
    </tr>

    <?php foreach($rows as $r): ?>
    <tr>
        <td><?=e($r['nomor_laporan'])?></td>
        <td><?=e($r['judul'])?></td>
        <td><?=e($r['nama_kategori'])?></td>
        <td><?=status_badge($r['status'])?></td>
        <td><?=e($r['created_at'])?></td>
        <td>
            <a href="detail_laporan.php?id=<?=$r['id_laporan']?>" title="Lihat detail dan kronologi laporan">Lihat Detail</a>
            <?php if(in_array($r['status'], ['Menunggu Verifikasi','Perlu Perbaikan'], true)): ?>
            <form method="post" action="../actions/proses_laporan.php" style="display:inline" data-confirm="Yakin ingin membatalkan laporan ini? Riwayatnya tetap tersimpan dan status berubah menjadi Dibatalkan.">
                <?=csrf_input()?>
                <input type="hidden" name="aksi" value="batalkan">
                <input type="hidden" name="id_laporan" value="<?=$r['id_laporan']?>">
                <button class="btn danger" type="submit" title="Batalkan laporan sebelum diproses">Batalkan Laporan</button>
            </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>
</div>

<?php if(!$rows): ?>
    <div class="empty">
        Belum ada laporan yang ditemukan.
        Silakan buat laporan pengaduan terlebih dahulu.
    </div>
<?php endif; ?>

<?php
pagination_links($pg);
footer_page();
?>
