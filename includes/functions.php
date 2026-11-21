<?php
require_once __DIR__.'/../config/database.php';
if(session_status()!==PHP_SESSION_ACTIVE){ session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS'])]); session_start(); }
function e($v):string{return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function go(string $url):never{header('Location: '.$url);exit;}
function me():?array{return $_SESSION['user']??null;}
function need_login():void{if(!me())go('/laporwarga/login.php');}
function need_role(array $roles):void{need_login();if(!in_array(me()['role'],$roles,true)){http_response_code(403);exit('Akses ditolak.');}}
function csrf():string{if(empty($_SESSION['_csrf']))$_SESSION['_csrf']=bin2hex(random_bytes(32));return $_SESSION['_csrf'];}
function csrf_input():string{return '<input type="hidden" name="_csrf" value="'.e(csrf()).'">';}
function check_csrf():void{if($_SERVER['REQUEST_METHOD']==='POST'&&!hash_equals($_SESSION['_csrf']??'',$_POST['_csrf']??'')){http_response_code(419);exit('Token keamanan tidak valid. Muat ulang halaman.');}}
function flash(string $type,string $msg):void{$_SESSION['flash']=[$type,$msg];}
function flashes():void{
    if(!isset($_SESSION['flash'])) return;
    [$type,$message]=$_SESSION['flash']; unset($_SESSION['flash']);
    $icon=in_array($type,['success','error','warning','info'],true)?$type:'info';
    echo '<div class="alert '.e($type).'">'.e($message).'</div>';
    echo '<script>window.addEventListener("DOMContentLoaded",function(){if(window.Swal){Swal.fire({icon:'.json_encode($icon).',text:'.json_encode($message,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES).',confirmButtonText:"Mengerti"});}});</script>';
}
function log_activity(string $activity,string $detail=''):void{try{$s=db()->prepare('INSERT INTO activity_logs(id_pengguna,aktivitas,detail_aktivitas,alamat_ip) VALUES(?,?,?,?)');$s->execute([me()['id_user']??null,$activity,$detail,$_SERVER['REMOTE_ADDR']??null]);}catch(Throwable $e){}}
function notify(int $user,string $title,string $message,?string $link=null):void{$s=db()->prepare('INSERT INTO notifications(id_pengguna,judul,pesan,link_tujuan) VALUES(?,?,?,?)');$s->execute([$user,$title,$message,$link]);}
function upload_photo(string $field='foto_bukti'):?string{if(empty($_FILES[$field])||$_FILES[$field]['error']===UPLOAD_ERR_NO_FILE)return null;$f=$_FILES[$field];if($f['error']!==UPLOAD_ERR_OK||$f['size']>3*1024*1024)throw new RuntimeException('Foto gagal diunggah atau melebihi 3 MB.');$mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);$ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime]??null;if(!$ext||!getimagesize($f['tmp_name']))throw new RuntimeException('Format foto harus JPG, PNG, atau WebP.');$name=bin2hex(random_bytes(18)).'.'.$ext;$dir=__DIR__.'/../uploads/laporan';if(!is_dir($dir))mkdir($dir,0755,true);if(!move_uploaded_file($f['tmp_name'],$dir.'/'.$name))throw new RuntimeException('Foto tidak dapat disimpan.');return $name;}
function status_options():array{return ['Menunggu Verifikasi','Perlu Perbaikan','Diterima','Sedang Diproses','Selesai','Ditolak','Dibatalkan'];}

function status_transitions():array{return ['Menunggu Verifikasi'=>['Perlu Perbaikan','Ditolak','Diterima'],'Perlu Perbaikan'=>['Diterima','Ditolak'],'Diterima'=>['Sedang Diproses','Selesai'],'Sedang Diproses'=>['Selesai'],'Ditolak'=>[],'Dibatalkan'=>[],'Selesai'=>[]];}
function valid_date_format(string $date):bool{$d=DateTime::createFromFormat('!Y-m-d',$date);return (bool)($d && $d->format('Y-m-d')===$date);}
function valid_report_date(string $date):bool{return valid_date_format($date)&&$date<=date('Y-m-d');}
function paginate(int $total,int $perPage=10):array{$page=max(1,(int)($_GET['page']??1));$pages=max(1,(int)ceil($total/$perPage));$page=min($page,$pages);return ['page'=>$page,'per_page'=>$perPage,'pages'=>$pages,'offset'=>($page-1)*$perPage,'total'=>$total];}
function pagination_links(array $pg):void{if($pg['pages']<=1)return;echo '<nav class="pagination" aria-label="Halaman">';for($i=1;$i<=$pg['pages'];$i++){if($i===$pg['page'])echo '<span class="current">'.$i.'</span>';else{$params=$_GET;$params['page']=$i;$url='?'.http_build_query($params);echo '<a href="'.e($url).'">'.$i.'</a>';}}echo '</nav>';}
function notify_report_parties(int $reportId,string $title,string $message):void{$s=db()->prepare('SELECT id_pelapor,id_petugas,nomor_laporan FROM reports WHERE id_laporan=?');$s->execute([$reportId]);$r=$s->fetch();if(!$r)return;$link='/laporwarga/masyarakat/detail_laporan.php?id='.$reportId;notify((int)$r['id_pelapor'],$title,$message,$link);if(!empty($r['id_petugas']))notify((int)$r['id_petugas'],$title,$message,'/laporwarga/petugas/detail_laporan.php?id='.$reportId);}
function header_page(string $title):void{
    $u=me();
    $unread=0;
    if($u){
        try{
            $n=db()->prepare('SELECT COUNT(*) FROM notifications WHERE id_pengguna=? AND is_read=0');
            $n->execute([$u['id_user']]);
            $unread=(int)$n->fetchColumn();
        }catch(Throwable $ex){}
    }
    $role=$u['role']??'guest';
    $menus=[];
    if($role==='masyarakat'){
        $menus=[
            ['Dashboard','/laporwarga/masyarakat/dashboard.php','gauge'],
            ['Buat Pengaduan','/laporwarga/masyarakat/buat_laporan.php','plus-circle'],
            ['Riwayat Laporan','/laporwarga/masyarakat/daftar_laporan.php','file-text'],
            ['Notifikasi'.($unread?' ('.$unread.')':''),'/laporwarga/notifikasi.php','bell'],
            ['Riwayat Aktivitas','/laporwarga/riwayat.php','history'],
            ['Profil','/laporwarga/masyarakat/profil.php','user']
        ];
    }elseif($role==='petugas'){
        $menus=[
            ['Dashboard','/laporwarga/petugas/dashboard.php','gauge'],
            ['Daftar Laporan','/laporwarga/petugas/daftar_laporan.php','clipboard-list'],
            ['Penugasan','/laporwarga/petugas/penugasan.php','clipboard-check'],
            ['Notifikasi'.($unread?' ('.$unread.')':''),'/laporwarga/notifikasi.php','bell'],
            ['Riwayat Aktivitas','/laporwarga/riwayat.php','history'],
            ['Profil','/laporwarga/petugas/profil.php','user']
        ];
    }elseif($role==='admin_utama'){
        $menus=[
            ['Dashboard','/laporwarga/admin-utama/dashboard.php','gauge'],
            ['Laporan','/laporwarga/admin-utama/laporan.php','files'],
            ['Penugasan','/laporwarga/admin-utama/penugasan.php','clipboard-check'],
            ['Pengguna','/laporwarga/admin-utama/pengguna.php','users'],
            ['Petugas','/laporwarga/admin-utama/petugas.php','user-cog'],
            ['Kategori','/laporwarga/admin-utama/kategori.php','layers'],
            ['Pengumuman','/laporwarga/admin-utama/pengumuman.php','megaphone'],
            ['Statistik','/laporwarga/admin-utama/laporan-statistik.php','chart'],
            ['Pengaturan','/laporwarga/admin-utama/pengaturan.php','settings'],
            ['Riwayat Aktivitas','/laporwarga/riwayat.php','history'],
            ['Notifikasi'.($unread?' ('.$unread.')':''),'/laporwarga/notifikasi.php','bell'],
            ['Profil','/laporwarga/admin-utama/profil.php','user']
        ];
    }else{
        $menus=[
            ['Beranda','/laporwarga/index.php','home'],
            ['Cara Kerja','#cara-kerja','info'],
            ['Kategori','#kategori','layers']
        ];
    }
    $current=$_SERVER['PHP_SELF']??'';
    ?><!doctype html>
    <html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="theme-color" content="#1769aa">
        <title><?=e($title)?> — LaporWarga</title>
        <link rel="stylesheet" href="/laporwarga/assets/css/style.css">
        <link rel="stylesheet" href="/laporwarga/assets/css/dashboard.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script><script defer src="/laporwarga/assets/js/script.js"></script>
    </head>
    <body>
    <header class="app-header">
        <a class="brand" href="<?= $u ? e(role_home()) : '/laporwarga/index.php' ?>">
            <span class="brand-symbol">LW</span>
            <span class="brand-copy">LaporWarga<small>LAYANAN PENGADUAN MASYARAKAT</small></span>
        </a>
        <button class="menu-toggle" type="button" data-menu-toggle aria-label="Buka navigasi" aria-expanded="false">☰</button>
        <nav class="main-nav" data-nav aria-label="Navigasi utama">
            <?php foreach($menus as $item):
                $active=(strpos($item[1],'#')!==0 && str_ends_with($current,basename($item[1])));
            ?>
                <a class="nav-item<?=$active?' active':''?>" href="<?=e($item[1])?>"><?=e($item[0])?></a>
            <?php endforeach; ?>
            <button class="theme-toggle" type="button" data-theme-toggle aria-label="Aktifkan mode gelap">
                <span class="theme-icon" aria-hidden="true">☾</span><span data-theme-label>Mode Gelap</span>
            </button>
            <?php if($u): ?>
                <span class="nav-user"><?=e($u['nama_lengkap'])?></span>
                <a class="nav-logout" href="/laporwarga/logout.php">Keluar</a>
            <?php else: ?>
                <a class="nav-item nav-login" href="/laporwarga/login.php">Masuk</a>
                <a class="nav-register" href="/laporwarga/register.php">Daftar</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="wrap">
    <?php flashes();
}
function footer_page():void{
    ?>
    </main>
    <footer class="site-footer">
        <div class="footer-main">
            <div class="footer-brand">
                <strong>LaporWarga</strong>
                <p>Sistem Informasi Pelayanan dan Pengaduan Masyarakat.</p>
            </div>
            <div class="team-watermark">
                <span class="team-label">Dikembangkan oleh · Kelompok 10</span>
                <div class="team-members">
                    <span>Nur Rizka Sasi Kirana <b>(241011401355)</b></span>
                    <span>Sabrina Reinkana Aulia W Alhasbi <b>(241011403370)</b></span>
                    <span>Ulfatun Nisya <b>(241011401644)</b></span>
                </div>
            </div>
        </div>
        <div class="footer-bottom">© <?=date('Y')?> LaporWarga · Kelompok 10</div>
    </footer>
    <?php if(me()): ?>
    <script>
    (() => {
      let lastNotification = 0;
      const poll = async () => {
        try {
          const response = await fetch('/laporwarga/actions/cek_notifikasi.php', {credentials:'same-origin',headers:{'Accept':'application/json'}});
          if (!response.ok) return;
          const data = await response.json();
          if (data && data.id > lastNotification) {
            lastNotification = data.id;
            if (window.Swal) Swal.fire({toast:true,position:'top-end',icon:'info',title:data.judul,text:data.pesan,timer:6500,showConfirmButton:false});
            else { const box=document.createElement('div');box.className='alert info';box.textContent=data.judul+': '+data.pesan;document.querySelector('main.wrap')?.prepend(box);setTimeout(()=>box.remove(),6500); }
          }
        } catch (e) {}
      };
      document.addEventListener('submit', event => {
        const form=event.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed==='1') return;
        event.preventDefault();
        const message=form.dataset.confirm || 'Apakah Anda yakin?';
        if (window.Swal) Swal.fire({icon:'warning',title:'Konfirmasi',text:message,showCancelButton:true,confirmButtonText:'Ya, lanjutkan',cancelButtonText:'Kembali'}).then(result=>{if(result.isConfirmed){form.dataset.confirmed='1';form.submit();}});
        else if(confirm(message)){form.dataset.confirmed='1';form.submit();}
      });
      poll(); setInterval(poll,15000);
    })();
    </script>
    <?php endif; ?>
    </body>
    </html>
    <?php
}
function status_badge(string $s):string{return '<span class="badge">'.e($s).'</span>';}
function role_home():string{return '/laporwarga/'.(me()['role']==='masyarakat'?'masyarakat':(me()['role']==='petugas'?'petugas':'admin-utama')).'/dashboard.php';}
