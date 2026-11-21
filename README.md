# LaporWarga — Sistem Informasi Pelayanan dan Pengaduan Masyarakat Berbasis E-Government

Aplikasi PHP native, MySQL/MariaDB, PDO, session, dan XAMPP. Folder mengikuti rancangan pada dokumen spesifikasi.

## Instalasi lokal
1. Pasang XAMPP dan Visual Studio Code.
2. Ekstrak folder `laporwarga` ke `C:\xampp\htdocs\laporwarga`.
3. Jalankan Apache dan MySQL dari XAMPP Control Panel.
4. Buka `http://localhost/phpmyadmin`, pilih Import, lalu impor `database/db_laporwarga.sql`. **Penting:** file SQL berisi perintah penghapusan tabel untuk instalasi ulang; gunakan pada database baru/kosong karena impor ulang dapat menghapus data LaporWarga yang sudah ada.
5. Periksa `config/database.php`. Default: host `localhost`, database `db_laporwarga`, user `root`, password kosong (sesuaikan konfigurasi lokal).
6. Buka `http://localhost/laporwarga/setup_admin.php` dan buat admin pertama dengan kata sandi minimal 12 karakter.
7. Setelah berhasil, hapus `setup_admin.php` dari folder proyek.
8. Buka `http://localhost/laporwarga/`.
9. Daftarkan masyarakat melalui halaman Daftar. Buat akun petugas dari menu Admin Utama > Petugas.

## Role
- `masyarakat`: membuat laporan, melihat laporan sendiri, riwayat, tanggapan, notifikasi, profil.
- `petugas`: hanya menangani laporan yang ditugaskan, memperbarui status yang valid, memberi tanggapan.
- `admin_utama`: pengelolaan laporan dan status, penugasan, pengguna, petugas, kategori, pengumuman, statistik, ekspor CSV, log aktivitas, profil administrator, dan pengaturan dasar.

## Struktur utama
- `config/database.php`: koneksi PDO.
- `includes/`: autentikasi, CSRF, fungsi bersama dan komponen tampilan.
- `masyarakat/`, `petugas/`, `admin-utama/`: halaman tiap role.
- `actions/`: endpoint proses.
- `database/db_laporwarga.sql`: skema database.
- `assets/`: CSS dan JavaScript.
- `uploads/laporan/`: foto bukti dengan nama acak; eksekusi skrip PHP diblokir melalui `.htaccess`.

## Keamanan dan catatan
Kata sandi menggunakan `password_hash()`/`password_verify()`, query menggunakan PDO prepared statements, form POST menggunakan CSRF token, output di-escape, dan akses halaman diperiksa berdasarkan role serta kepemilikan/penugasan. Jangan mengekspos aplikasi langsung ke internet tanpa audit keamanan, konfigurasi HTTPS, pembatasan akses, backup, dan pengujian tambahan. Tema memiliki palet CSS light/dark/blue/green; preferensi tema disimpan sebagai preferensi tampilan browser, bukan sebagai penyimpanan data aplikasi. Pengaturan dasar aplikasi disimpan pada tabel `app_settings`. Tidak ada data pengaduan contoh hardcoded.

## Pengujian manual yang disarankan
- Import SQL dan cek koneksi melalui beranda.
- Buat admin pertama lalu hapus `setup_admin.php`.
- Daftar masyarakat dan login/logout.
- Kirim laporan dengan dan tanpa foto; cek nomor dan riwayat.
- Buat akun petugas sebagai admin, tugaskan laporan, lalu login sebagai petugas.
- Coba transisi status yang diizinkan dan tidak diizinkan.
- Login sebagai masyarakat dan pastikan laporan pengguna lain tidak dapat dibuka dengan mengganti ID.
- Cek notifikasi, kategori, pengumuman, log, statistik, ekspor CSV, dan tampilan ponsel.
- Uji database kosong, login gagal, CSRF tidak valid, serta file upload yang bukan gambar.

## Catatan pengujian
Pemeriksaan sintaks PHP dapat dilakukan menggunakan `php -l` pada setiap file. Integrasi database dan alur antarroIe tetap harus diuji langsung di XAMPP dengan data lokal, karena pengujian lingkungan XAMPP tidak dijalankan dari paket ini. Grafik memakai Chart.js CDN sehingga memerlukan internet saat memuat pustaka grafik.


## Cara menggunakan setiap role

### Masyarakat
1. Buka menu Daftar dan buat akun masyarakat.
2. Masuk menggunakan email dan kata sandi.
3. Pilih Buat Pengaduan, isi judul, kategori, lokasi, tanggal kejadian, deskripsi, dan foto opsional.
4. Simpan nomor laporan yang ditampilkan. Buka Daftar Laporan untuk melihat status, riwayat, dan tanggapan.
5. Jika status Perlu Perbaikan, buka detail laporan, perbarui data, lalu kirim ulang.
6. Laporan yang masih Menunggu Verifikasi dapat dibatalkan. Laporan pengguna lain tidak dapat dibuka.

### Petugas
1. Minta administrator membuat akun petugas.
2. Masuk menggunakan akun tersebut.
3. Buka Laporan Ditugaskan. Petugas hanya melihat laporan yang ditugaskan kepadanya.
4. Buka detail, pilih status yang tersedia sesuai alur, isi alasan jika meminta perbaikan/menolak, dan tambahkan tanggapan bila diperlukan.
5. Periksa menu Notifikasi dan Profil untuk pembaruan akun.

### Administrator utama
1. Buat akun pertama melalui `setup_admin.php`, lalu hapus file tersebut.
2. Masuk dan gunakan dashboard untuk melihat statistik serta laporan yang belum diperbarui selama lebih dari tujuh hari.
3. Buat akun petugas, tugaskan laporan, kelola akun masyarakat, kategori, pengumuman, profil, dan pengaturan dasar.
4. Buka detail laporan untuk melihat riwayat atau melakukan perubahan status yang valid.
5. Gunakan filter laporan lalu pilih Ekspor CSV. Ekspor petugas dibatasi pada laporan yang ditugaskan kepadanya.

## Daftar pengujian manual

| Pengujian | Hasil yang diharapkan |
|---|---|
| Buka beranda setelah SQL diimpor | Halaman publik terbuka dan statistik berasal dari database |
| Registrasi email baru | Akun masyarakat tersimpan dan kata sandi tersimpan sebagai hash |
| Registrasi email duplikat | Pesan gagal tampil tanpa menampilkan error SQL |
| Login dengan kata sandi salah | Login ditolak |
| Login pada akun nonaktif | Login ditolak |
| Logout | Sesi berakhir |
| Masyarakat membuka ID laporan milik orang lain | Akses ditolak / laporan tidak ditemukan |
| Unggah file bukan gambar atau lebih dari 3 MB | File ditolak |
| Petugas membuka laporan yang bukan penugasannya | Akses ditolak / laporan tidak ditemukan |
| Petugas mengubah status dengan transisi tidak valid | Perubahan ditolak |
| Petugas meminta perbaikan atau menolak tanpa alasan | Perubahan ditolak |
| Perubahan status berhasil | Riwayat dan notifikasi tersimpan |
| Admin membuat petugas dan menugaskan laporan | Akun/penugasan tersimpan dan notifikasi dibuat |
| Filter, pagination, dan ekspor CSV | Hasil mengikuti filter dan hak akses |
| Tema light/dark/biru/hijau | Warna antarmuka berubah |
| Database tanpa data laporan | Statistik menampilkan nol dan tabel menampilkan keadaan kosong |

## Masalah umum

- **Database belum dapat diakses:** pastikan MySQL berjalan di XAMPP, nama database `db_laporwarga` tersedia, dan kredensial di `config/database.php` sesuai.
- **Halaman tidak ditemukan:** ekstrak folder dengan nama persis `laporwarga` ke `C:\xampp\htdocs\laporwarga`, lalu buka `http://localhost/laporwarga/`.
- **Foto gagal diunggah:** pastikan format JPG/PNG/WebP, ukuran maksimal 3 MB, dan folder `uploads/laporan/` dapat ditulis oleh Apache.
- **Admin pertama tidak dapat dibuat:** periksa apakah akun `admin_utama` sudah ada. Jika sudah ada, `setup_admin.php` memang menolak pembuatan admin kedua.
- **Grafik tidak termuat saat offline:** dashboard menyediakan gambar grafik canvas sederhana sebagai fallback ketika Chart.js CDN tidak tersedia.
- **Error saat mengimpor SQL:** impor pada database baru/kosong. File SQL menurunkan tabel yang bernama sama agar instalasi ulang konsisten; jangan jalankan ulang pada database yang berisi data penting.

## Batas pengujian

Pemeriksaan sintaks PHP dilakukan pada seluruh file. Paket ini belum menjalani pengujian integrasi end-to-end menggunakan server MySQL/XAMPP di lingkungan pembuatan, sehingga lakukan seluruh daftar pengujian manual di atas sebelum digunakan untuk demonstrasi atau penilaian. Untuk penggunaan publik, diperlukan audit keamanan, HTTPS, backup, kebijakan privasi, dan penguatan konfigurasi server.
