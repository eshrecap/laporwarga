-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Nov 2026 pada 07.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_laporwarga1`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id_log` bigint(20) UNSIGNED NOT NULL,
  `id_pengguna` int(10) UNSIGNED DEFAULT NULL,
  `aktivitas` varchar(100) NOT NULL,
  `detail_aktivitas` text DEFAULT NULL,
  `alamat_ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id_log`, `id_pengguna`, `aktivitas`, `detail_aktivitas`, `alamat_ip`, `created_at`) VALUES
(1, NULL, 'setup_admin', 'Administrator dibuat', '::1', '2026-11-16 04:09:54'),
(2, NULL, 'setup_admin', 'Administrator dibuat', '::1', '2026-11-16 04:13:03'),
(3, NULL, 'setup_admin', 'Administrator dibuat', '::1', '2026-11-16 04:14:10'),
(4, 5, 'login', 'Login berhasil', '::1', '2026-11-16 04:20:59'),
(5, 5, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:21:10'),
(6, 1, 'login', 'Login berhasil', '::1', '2026-11-16 04:23:13'),
(7, 6, 'login', 'Login berhasil', '::1', '2026-11-16 04:33:58'),
(8, 6, 'buat_laporan', 'LW-20261116-D4EA9E', '::1', '2026-11-16 04:41:48'),
(9, 6, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:41:57'),
(10, 7, 'login', 'Login berhasil', '::1', '2026-11-16 04:42:23'),
(11, 7, 'buat_laporan', 'LW-20261116-191291', '::1', '2026-11-16 04:46:15'),
(12, 7, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:46:18'),
(13, 8, 'login', 'Login berhasil', '::1', '2026-11-16 04:46:33'),
(14, 8, 'buat_laporan', 'LW-20261116-5448D2', '::1', '2026-11-16 04:47:41'),
(15, 8, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:47:53'),
(16, 9, 'login', 'Login berhasil', '::1', '2026-11-16 04:49:43'),
(17, 9, 'buat_laporan', 'LW-20261116-5E5881', '::1', '2026-11-16 04:50:40'),
(18, 9, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:50:47'),
(19, 10, 'login', 'Login berhasil', '::1', '2026-11-16 04:51:04'),
(20, 10, 'buat_laporan', 'LW-20261116-0DE484', '::1', '2026-11-16 04:51:42'),
(21, 10, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:51:46'),
(22, 11, 'login', 'Login berhasil', '::1', '2026-11-16 04:51:57'),
(23, 11, 'buat_laporan', 'LW-20261116-F046B1', '::1', '2026-11-16 04:52:29'),
(24, 11, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:52:33'),
(25, 12, 'login', 'Login berhasil', '::1', '2026-11-16 04:52:45'),
(26, 12, 'buat_laporan', 'LW-20261116-B89828', '::1', '2026-11-16 04:53:20'),
(27, 12, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:53:24'),
(28, 13, 'login', 'Login berhasil', '::1', '2026-11-16 04:54:19'),
(29, 13, 'buat_laporan', 'LW-20261116-D1718A', '::1', '2026-11-16 04:54:51'),
(30, 13, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:54:56'),
(31, 14, 'login', 'Login berhasil', '::1', '2026-11-16 04:55:05'),
(32, 14, 'buat_laporan', 'LW-20261116-3E13C3', '::1', '2026-11-16 04:56:08'),
(33, 14, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:56:22'),
(34, 15, 'login', 'Login berhasil', '::1', '2026-11-16 04:56:58'),
(35, 15, 'buat_laporan', 'LW-20261116-74539C', '::1', '2026-11-16 04:57:33'),
(36, 15, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:57:38'),
(37, 16, 'login', 'Login berhasil', '::1', '2026-11-16 04:58:03'),
(38, 16, 'buat_laporan', 'LW-20261116-C175F5', '::1', '2026-11-16 04:58:43'),
(39, 16, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:58:49'),
(40, 17, 'login', 'Login berhasil', '::1', '2026-11-16 04:58:59'),
(41, 17, 'buat_laporan', 'LW-20261116-B2B862', '::1', '2026-11-16 04:59:37'),
(42, 17, 'logout', 'Pengguna keluar', '::1', '2026-11-16 04:59:42'),
(43, 18, 'login', 'Login berhasil', '::1', '2026-11-16 05:00:02'),
(44, 18, 'buat_laporan', 'LW-20261116-749C74', '::1', '2026-11-16 05:00:38'),
(45, 18, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:00:42'),
(46, 19, 'login', 'Login berhasil', '::1', '2026-11-16 05:00:51'),
(47, 19, 'buat_laporan', 'LW-20261116-BBE109', '::1', '2026-11-16 05:01:20'),
(48, 19, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:01:23'),
(49, 20, 'login', 'Login berhasil', '::1', '2026-11-16 05:01:35'),
(50, 20, 'buat_laporan', 'LW-20261116-743BFB', '::1', '2026-11-16 05:02:13'),
(51, 20, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:02:17'),
(52, 21, 'login', 'Login berhasil', '::1', '2026-11-16 05:02:25'),
(53, 21, 'buat_laporan', 'LW-20261116-B7D7DE', '::1', '2026-11-16 05:03:05'),
(54, 21, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:03:08'),
(55, 22, 'login', 'Login berhasil', '::1', '2026-11-16 05:03:21'),
(56, 22, 'buat_laporan', 'LW-20261116-AF8634', '::1', '2026-11-16 05:03:50'),
(57, 22, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:03:55'),
(58, 23, 'login', 'Login berhasil', '::1', '2026-11-16 05:04:22'),
(59, 23, 'buat_laporan', 'LW-20261116-5E1377', '::1', '2026-11-16 05:05:08'),
(60, 23, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:05:16'),
(61, 24, 'login', 'Login berhasil', '::1', '2026-11-16 05:05:38'),
(62, 24, 'buat_laporan', 'LW-20261116-208399', '::1', '2026-11-16 05:06:17'),
(63, 24, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:06:39'),
(64, 24, 'login', 'Login berhasil', '::1', '2026-11-16 05:07:47'),
(65, 24, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:08:03'),
(66, 25, 'login', 'Login berhasil', '::1', '2026-11-16 05:08:28'),
(67, 25, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:12:24'),
(68, 25, 'login', 'Login berhasil', '::1', '2026-11-16 05:12:33'),
(69, 25, 'buat_laporan', 'LW-20261116-EB976E', '::1', '2026-11-16 05:13:43'),
(70, 25, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:14:47'),
(71, 26, 'login', 'Login berhasil', '::1', '2026-11-16 05:15:12'),
(72, 26, 'buat_laporan', 'LW-20261116-432B4F', '::1', '2026-11-16 05:15:47'),
(73, 26, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:15:51'),
(74, 27, 'login', 'Login berhasil', '::1', '2026-11-16 05:16:01'),
(75, 27, 'buat_laporan', 'LW-20261116-9A47BD', '::1', '2026-11-16 05:16:42'),
(76, 27, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:16:45'),
(77, 28, 'login', 'Login berhasil', '::1', '2026-11-16 05:17:04'),
(78, 28, 'buat_laporan', 'LW-20261116-92A084', '::1', '2026-11-16 05:17:50'),
(79, 28, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:17:53'),
(80, 29, 'login', 'Login berhasil', '::1', '2026-11-16 05:18:08'),
(81, 29, 'buat_laporan', 'LW-20261116-516F80', '::1', '2026-11-16 05:18:48'),
(82, 29, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:18:51'),
(83, 30, 'login', 'Login berhasil', '::1', '2026-11-16 05:19:12'),
(84, 30, 'buat_laporan', 'LW-20261116-EAA7EC', '::1', '2026-11-16 05:19:53'),
(85, 30, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:20:01'),
(86, 31, 'login', 'Login berhasil', '::1', '2026-11-16 05:20:17'),
(87, 31, 'buat_laporan', 'LW-20261116-2661FA', '::1', '2026-11-16 05:21:03'),
(88, 31, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:21:07'),
(89, 32, 'login', 'Login berhasil', '::1', '2026-11-16 05:22:02'),
(90, 32, 'buat_laporan', 'LW-20261116-A45661', '::1', '2026-11-16 05:22:43'),
(91, 32, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:22:49'),
(92, 33, 'login', 'Login berhasil', '::1', '2026-11-16 05:23:13'),
(93, 33, 'buat_laporan', 'LW-20261116-A812F0', '::1', '2026-11-16 05:23:49'),
(94, 33, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:23:52'),
(95, 34, 'login', 'Login berhasil', '::1', '2026-11-16 05:24:09'),
(96, 34, 'buat_laporan', 'LW-20261116-A22659', '::1', '2026-11-16 05:24:40'),
(97, 34, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:24:46'),
(98, 35, 'login', 'Login berhasil', '::1', '2026-11-16 05:27:02'),
(99, 35, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:27:07'),
(100, 35, 'login', 'Login berhasil', '::1', '2026-11-16 05:27:50'),
(101, 35, 'buat_laporan', 'LW-20261116-2FD56D', '::1', '2026-11-16 05:32:26'),
(102, 35, 'buat_laporan', 'LW-20261116-88B3CB', '::1', '2026-11-16 05:32:26'),
(103, 35, 'login', 'Login berhasil', '::1', '2026-11-16 05:45:41'),
(104, 35, 'logout', 'Pengguna keluar', '::1', '2026-11-16 05:48:29'),
(105, 1, 'login', 'Login berhasil', '::1', '2026-11-16 05:51:46'),
(106, 1, 'buat_akun_petugas', 'kiranasasi@gmail.com', '::1', '2026-11-16 05:53:20'),
(107, 1, 'buat_akun_petugas', 'rainkana@gmail.com', '::1', '2026-11-16 05:55:06'),
(108, 1, 'penugasan', 'LW-20261116-88B3CB kepada petugas 36', '::1', '2026-11-16 05:55:17'),
(109, 1, 'penugasan', 'LW-20261116-2FD56D kepada petugas 37', '::1', '2026-11-16 05:55:20'),
(110, 1, 'penugasan', 'LW-20261116-A22659 kepada petugas 37', '::1', '2026-11-16 05:55:23'),
(111, 1, 'penugasan', 'LW-20261116-A812F0 kepada petugas 36', '::1', '2026-11-16 05:55:27'),
(112, 1, 'penugasan', 'LW-20261116-A45661 kepada petugas 37', '::1', '2026-11-16 05:55:30'),
(113, 1, 'penugasan', 'LW-20261116-2661FA kepada petugas 36', '::1', '2026-11-16 05:55:39'),
(114, 1, 'penugasan', 'LW-20261116-EAA7EC kepada petugas 37', '::1', '2026-11-16 05:55:43'),
(115, 1, 'penugasan', 'LW-20261116-D4EA9E kepada petugas 37', '::1', '2026-11-16 05:56:00'),
(116, 37, 'login', 'Login berhasil', '::1', '2026-11-16 06:12:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `announcements`
--

CREATE TABLE `announcements` (
  `id_pengumuman` int(10) UNSIGNED NOT NULL,
  `judul` varchar(180) NOT NULL,
  `isi` text NOT NULL,
  `id_admin` int(10) UNSIGNED NOT NULL,
  `STATUS` enum('Draft','Publikasi') NOT NULL DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `app_settings`
--

CREATE TABLE `app_settings` (
  `id_setting` int(10) UNSIGNED NOT NULL,
  `kunci` varchar(100) NOT NULL,
  `nilai` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `app_settings`
--

INSERT INTO `app_settings` (`id_setting`, `kunci`, `nilai`, `updated_at`) VALUES
(1, 'nama_aplikasi', 'LaporWarga', '2026-11-16 03:42:11'),
(2, 'kontak_layanan', '', '2026-11-16 03:42:11'),
(3, 'deskripsi_beranda', 'Sistem Informasi Pelayanan dan Pengaduan Masyarakat Berbasis E-Government', '2026-11-16 03:42:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `status_kategori` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id_kategori`, `nama_kategori`, `deskripsi`, `status_kategori`, `created_at`) VALUES
(1, 'Infrastruktur', 'Jalan, jembatan, drainase, dan fasilitas umum', 1, '2026-11-16 03:42:11'),
(2, 'Kebersihan', 'Sampah dan kebersihan lingkungan', 1, '2026-11-16 03:42:11'),
(3, 'Keamanan', 'Gangguan keamanan dan ketertiban', 1, '2026-11-16 03:42:11'),
(4, 'Pelayanan Publik', 'Pelayanan administrasi dan fasilitas pelayanan', 1, '2026-11-16 03:42:11'),
(5, 'Lingkungan', 'Pencemaran dan kondisi lingkungan', 1, '2026-11-16 03:42:11'),
(6, 'Lainnya', 'Pengaduan di luar kategori yang tersedia', 1, '2026-11-16 03:42:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id_notifikasi` bigint(20) UNSIGNED NOT NULL,
  `id_pengguna` int(10) UNSIGNED NOT NULL,
  `judul` varchar(180) NOT NULL,
  `pesan` text NOT NULL,
  `link_tujuan` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id_notifikasi`, `id_pengguna`, `judul`, `pesan`, `link_tujuan`, `is_read`, `created_at`) VALUES
(1, 1, 'Laporan baru', 'Laporan LW-20261116-D4EA9E menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:41:48'),
(2, 5, 'Laporan baru', 'Laporan LW-20261116-D4EA9E menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:41:48'),
(3, 1, 'Laporan baru', 'Laporan LW-20261116-191291 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:46:15'),
(4, 5, 'Laporan baru', 'Laporan LW-20261116-191291 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:46:15'),
(5, 1, 'Laporan baru', 'Laporan LW-20261116-5448D2 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:47:41'),
(6, 5, 'Laporan baru', 'Laporan LW-20261116-5448D2 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:47:41'),
(7, 1, 'Laporan baru', 'Laporan LW-20261116-5E5881 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:50:40'),
(8, 5, 'Laporan baru', 'Laporan LW-20261116-5E5881 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:50:40'),
(9, 1, 'Laporan baru', 'Laporan LW-20261116-0DE484 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:51:42'),
(10, 5, 'Laporan baru', 'Laporan LW-20261116-0DE484 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:51:42'),
(11, 1, 'Laporan baru', 'Laporan LW-20261116-F046B1 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:52:29'),
(12, 5, 'Laporan baru', 'Laporan LW-20261116-F046B1 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:52:29'),
(13, 1, 'Laporan baru', 'Laporan LW-20261116-B89828 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:53:20'),
(14, 5, 'Laporan baru', 'Laporan LW-20261116-B89828 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:53:20'),
(15, 1, 'Laporan baru', 'Laporan LW-20261116-D1718A menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:54:51'),
(16, 5, 'Laporan baru', 'Laporan LW-20261116-D1718A menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:54:51'),
(17, 1, 'Laporan baru', 'Laporan LW-20261116-3E13C3 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:56:08'),
(18, 5, 'Laporan baru', 'Laporan LW-20261116-3E13C3 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:56:08'),
(19, 1, 'Laporan baru', 'Laporan LW-20261116-74539C menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:57:33'),
(20, 5, 'Laporan baru', 'Laporan LW-20261116-74539C menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:57:33'),
(21, 1, 'Laporan baru', 'Laporan LW-20261116-C175F5 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:58:43'),
(22, 5, 'Laporan baru', 'Laporan LW-20261116-C175F5 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:58:43'),
(23, 1, 'Laporan baru', 'Laporan LW-20261116-B2B862 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:59:37'),
(24, 5, 'Laporan baru', 'Laporan LW-20261116-B2B862 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 04:59:37'),
(25, 1, 'Laporan baru', 'Laporan LW-20261116-749C74 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:00:38'),
(26, 5, 'Laporan baru', 'Laporan LW-20261116-749C74 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:00:38'),
(27, 1, 'Laporan baru', 'Laporan LW-20261116-BBE109 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:01:20'),
(28, 5, 'Laporan baru', 'Laporan LW-20261116-BBE109 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:01:20'),
(29, 1, 'Laporan baru', 'Laporan LW-20261116-743BFB menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:02:13'),
(30, 5, 'Laporan baru', 'Laporan LW-20261116-743BFB menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:02:13'),
(31, 1, 'Laporan baru', 'Laporan LW-20261116-B7D7DE menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:03:05'),
(32, 5, 'Laporan baru', 'Laporan LW-20261116-B7D7DE menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:03:05'),
(33, 1, 'Laporan baru', 'Laporan LW-20261116-AF8634 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:03:50'),
(34, 5, 'Laporan baru', 'Laporan LW-20261116-AF8634 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:03:50'),
(35, 1, 'Laporan baru', 'Laporan LW-20261116-5E1377 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:05:08'),
(36, 5, 'Laporan baru', 'Laporan LW-20261116-5E1377 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:05:08'),
(37, 1, 'Laporan baru', 'Laporan LW-20261116-208399 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:06:17'),
(38, 5, 'Laporan baru', 'Laporan LW-20261116-208399 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:06:17'),
(39, 1, 'Laporan baru', 'Laporan LW-20261116-EB976E menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:13:43'),
(40, 5, 'Laporan baru', 'Laporan LW-20261116-EB976E menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:13:43'),
(41, 1, 'Laporan baru', 'Laporan LW-20261116-432B4F menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:15:47'),
(42, 5, 'Laporan baru', 'Laporan LW-20261116-432B4F menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:15:47'),
(43, 1, 'Laporan baru', 'Laporan LW-20261116-9A47BD menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:16:42'),
(44, 5, 'Laporan baru', 'Laporan LW-20261116-9A47BD menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:16:42'),
(45, 1, 'Laporan baru', 'Laporan LW-20261116-92A084 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:17:50'),
(46, 5, 'Laporan baru', 'Laporan LW-20261116-92A084 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:17:50'),
(47, 1, 'Laporan baru', 'Laporan LW-20261116-516F80 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:18:48'),
(48, 5, 'Laporan baru', 'Laporan LW-20261116-516F80 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:18:48'),
(49, 1, 'Laporan baru', 'Laporan LW-20261116-EAA7EC menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:19:53'),
(50, 5, 'Laporan baru', 'Laporan LW-20261116-EAA7EC menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:19:53'),
(51, 1, 'Laporan baru', 'Laporan LW-20261116-2661FA menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:21:03'),
(52, 5, 'Laporan baru', 'Laporan LW-20261116-2661FA menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:21:03'),
(53, 1, 'Laporan baru', 'Laporan LW-20261116-A45661 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:22:43'),
(54, 5, 'Laporan baru', 'Laporan LW-20261116-A45661 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:22:43'),
(55, 1, 'Laporan baru', 'Laporan LW-20261116-A812F0 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:23:49'),
(56, 5, 'Laporan baru', 'Laporan LW-20261116-A812F0 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:23:49'),
(57, 1, 'Laporan baru', 'Laporan LW-20261116-A22659 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:24:40'),
(58, 5, 'Laporan baru', 'Laporan LW-20261116-A22659 menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:24:40'),
(59, 1, 'Laporan baru', 'Laporan LW-20261116-2FD56D menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:32:26'),
(60, 5, 'Laporan baru', 'Laporan LW-20261116-2FD56D menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:32:26'),
(61, 1, 'Laporan baru', 'Laporan LW-20261116-88B3CB menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:32:26'),
(62, 5, 'Laporan baru', 'Laporan LW-20261116-88B3CB menunggu penugasan.', '/laporwarga/admin-utama/laporan.php', 0, '2026-11-16 05:32:26'),
(63, 36, 'Penugasan Laporan Baru', 'Anda mendapat tugas untuk laporan LW-20261116-88B3CB', '/laporwarga/petugas/daftar_laporan.php', 0, '2026-11-16 05:55:17'),
(64, 35, 'Laporan Ditugaskan', 'Laporan LW-20261116-88B3CB telah ditugaskan kepada petugas.', '/laporwarga/masyarakat/daftar_laporan.php', 0, '2026-11-16 05:55:17'),
(65, 37, 'Penugasan Laporan Baru', 'Anda mendapat tugas untuk laporan LW-20261116-2FD56D', '/laporwarga/petugas/daftar_laporan.php', 0, '2026-11-16 05:55:20'),
(66, 35, 'Laporan Ditugaskan', 'Laporan LW-20261116-2FD56D telah ditugaskan kepada petugas.', '/laporwarga/masyarakat/daftar_laporan.php', 0, '2026-11-16 05:55:20'),
(67, 37, 'Penugasan Laporan Baru', 'Anda mendapat tugas untuk laporan LW-20261116-A22659', '/laporwarga/petugas/daftar_laporan.php', 0, '2026-11-16 05:55:23'),
(68, 34, 'Laporan Ditugaskan', 'Laporan LW-20261116-A22659 telah ditugaskan kepada petugas.', '/laporwarga/masyarakat/daftar_laporan.php', 0, '2026-11-16 05:55:23'),
(69, 36, 'Penugasan Laporan Baru', 'Anda mendapat tugas untuk laporan LW-20261116-A812F0', '/laporwarga/petugas/daftar_laporan.php', 0, '2026-11-16 05:55:27'),
(70, 33, 'Laporan Ditugaskan', 'Laporan LW-20261116-A812F0 telah ditugaskan kepada petugas.', '/laporwarga/masyarakat/daftar_laporan.php', 0, '2026-11-16 05:55:27'),
(71, 37, 'Penugasan Laporan Baru', 'Anda mendapat tugas untuk laporan LW-20261116-A45661', '/laporwarga/petugas/daftar_laporan.php', 0, '2026-11-16 05:55:30'),
(72, 32, 'Laporan Ditugaskan', 'Laporan LW-20261116-A45661 telah ditugaskan kepada petugas.', '/laporwarga/masyarakat/daftar_laporan.php', 0, '2026-11-16 05:55:30'),
(73, 36, 'Penugasan Laporan Baru', 'Anda mendapat tugas untuk laporan LW-20261116-2661FA', '/laporwarga/petugas/daftar_laporan.php', 0, '2026-11-16 05:55:39'),
(74, 31, 'Laporan Ditugaskan', 'Laporan LW-20261116-2661FA telah ditugaskan kepada petugas.', '/laporwarga/masyarakat/daftar_laporan.php', 0, '2026-11-16 05:55:39'),
(75, 37, 'Penugasan Laporan Baru', 'Anda mendapat tugas untuk laporan LW-20261116-EAA7EC', '/laporwarga/petugas/daftar_laporan.php', 0, '2026-11-16 05:55:43'),
(76, 30, 'Laporan Ditugaskan', 'Laporan LW-20261116-EAA7EC telah ditugaskan kepada petugas.', '/laporwarga/masyarakat/daftar_laporan.php', 0, '2026-11-16 05:55:43'),
(77, 37, 'Penugasan Laporan Baru', 'Anda mendapat tugas untuk laporan LW-20261116-D4EA9E', '/laporwarga/petugas/daftar_laporan.php', 0, '2026-11-16 05:56:00'),
(78, 6, 'Laporan Ditugaskan', 'Laporan LW-20261116-D4EA9E telah ditugaskan kepada petugas.', '/laporwarga/masyarakat/daftar_laporan.php', 0, '2026-11-16 05:56:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reports`
--

CREATE TABLE `reports` (
  `id_laporan` bigint(20) UNSIGNED NOT NULL,
  `nomor_laporan` varchar(40) NOT NULL,
  `id_pelapor` int(10) UNSIGNED NOT NULL,
  `id_kategori` int(10) UNSIGNED NOT NULL,
  `id_petugas` int(10) UNSIGNED DEFAULT NULL,
  `judul` varchar(180) NOT NULL,
  `deskripsi` text NOT NULL,
  `lokasi_kejadian` varchar(255) NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `STATUS` enum('Menunggu Verifikasi','Perlu Perbaikan','Ditolak','Diterima','Sedang Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu Verifikasi',
  `alasan_penolakan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reports`
--

INSERT INTO `reports` (`id_laporan`, `nomor_laporan`, `id_pelapor`, `id_kategori`, `id_petugas`, `judul`, `deskripsi`, `lokasi_kejadian`, `tanggal_kejadian`, `foto_bukti`, `STATUS`, `alasan_penolakan`, `created_at`, `updated_at`) VALUES
(1, 'LW-20261116-D4EA9E', 6, 1, 37, 'Jalan Berlubang di Lingkungan RT 01 RW 01', 'Terdapat beberapa lubang pada jalan di Jalan Melati RT 01/RW 01 yang mengganggu aktivitas masyarakat. Kondisi jalan semakin memburuk terutama setelah hujan karena lubang tertutup genangan air. Hal ini dapat membahayakan pengendara sepeda motor maupun kendaraan lainnya. Warga berharap pihak terkait segera melakukan perbaikan jalan.', 'Jalan Melati, RT 01/RW 01, Lingkungan Permukiman Warga', '2026-11-10', '2068cdec39c651b38d381cd41dd8721bc139.jpg', 'Menunggu Verifikasi', NULL, '2026-11-16 04:41:48', '2026-11-16 05:56:00'),
(2, 'LW-20261116-191291', 7, 5, NULL, 'Lampu Penerangan Jalan Mati', 'Lampu penerangan jalan di Jalan Kenanga RT 02/RW 01 sudah tidak berfungsi selama beberapa hari. Kondisi tersebut menyebabkan jalan menjadi gelap pada malam hari dan mengurangi kenyamanan masyarakat saat beraktivitas. Warga berharap lampu jalan segera diperbaiki agar lingkungan kembali terang dan aman.', 'Jalan Kenanga, RT 02/RW 01, Lingkungan Permukiman Warga', '2026-11-10', '957263ed774a5c976909661b2d8c2b220cf9.webp', 'Menunggu Verifikasi', NULL, '2026-11-16 04:46:15', '2026-11-16 04:46:15'),
(3, 'LW-20261116-5448D2', 8, 2, NULL, 'Penumpukan Sampah di Pinggir Jalan', 'Terjadi penumpukan sampah rumah tangga di pinggir Jalan Anggrek RT 03/RW 01. Sampah tersebut menimbulkan bau tidak sedap dan mengganggu kenyamanan warga sekitar. Kondisi ini juga berpotensi mengundang lalat dan menyebabkan lingkungan menjadi tidak sehat. Warga berharap petugas kebersihan segera mengangkut sampah tersebut.', 'Jalan Anggrek, RT 03/RW 01, Lingkungan Permukiman Warga', '2026-11-10', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:47:41', '2026-11-16 04:47:41'),
(4, 'LW-20261116-5E5881', 9, 1, NULL, 'Saluran Drainase Tersumbat', 'Saluran drainase di Jalan Mawar RT 04/RW 01 mengalami penyumbatan akibat tumpukan lumpur dan sampah. Ketika hujan turun, air tidak dapat mengalir dengan lancar sehingga menyebabkan genangan di sekitar rumah warga. Diharapkan pihak terkait segera membersihkan saluran drainase agar aliran air kembali normal.', 'Jalan Mawar, RT 04/RW 01, Lingkungan Permukiman Warga', '2026-11-10', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:50:40', '2026-11-16 04:50:40'),
(5, 'LW-20261116-0DE484', 10, 3, NULL, 'Kehilangan Sepeda Motor di Lingkungan Warga', 'Salah seorang warga melaporkan kehilangan sepeda motor yang sebelumnya diparkir di area parkir Jalan Flamboyan RT 05/RW 02. Kejadian tersebut menimbulkan kekhawatiran mengenai keamanan lingkungan. Warga berharap pengurus lingkungan meningkatkan pengawasan dan mengaktifkan kembali kegiatan ronda malam.', 'Area Parkir Jalan Flamboyan, RT 05/RW 02, Lingkungan Permukiman Warga', '2026-11-10', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:51:42', '2026-11-16 04:51:42'),
(6, 'LW-20261116-F046B1', 11, 2, NULL, 'Sampah Menumpuk di Selokan', 'Ditemukan banyak sampah plastik dan sampah rumah tangga yang menumpuk di selokan Jalan Cempaka RT 06/RW 02. Tumpukan tersebut menghambat aliran air dan menimbulkan bau tidak sedap. Warga berharap dilakukan pembersihan selokan secara menyeluruh agar tidak terjadi penyumbatan dan genangan air.', 'Jalan Cempaka, RT 06/RW 02, Lingkungan Permukiman Warga', '2026-11-10', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:52:29', '2026-11-16 04:52:29'),
(7, 'LW-20261116-B89828', 12, 1, NULL, 'Kerusakan Jalan Menuju Permukiman', 'Kondisi jalan menuju permukiman warga di Jalan Dahlia RT 07/RW 02 mengalami kerusakan berupa permukaan jalan yang retak dan berlubang. Kerusakan tersebut menghambat kendaraan yang melintas, terutama pada saat hujan. Warga mengharapkan adanya perbaikan jalan untuk meningkatkan kenyamanan dan keselamatan pengguna jalan.', 'Jalan Dahlia, RT 07/RW 02, Lingkungan Permukiman Warga', '2026-11-11', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:53:20', '2026-11-16 04:53:20'),
(8, 'LW-20261116-D1718A', 13, 3, NULL, 'Gangguan Keamanan pada Malam Hari', 'Warga mengeluhkan adanya aktivitas mencurigakan yang terjadi pada malam hari di sekitar Jalan Teratai RT 08/RW 02. Kejadian tersebut membuat masyarakat merasa kurang aman ketika beraktivitas pada malam hari. Warga berharap petugas keamanan dan pengurus lingkungan meningkatkan patroli serta mengaktifkan kegiatan ronda malam.', 'Jalan Teratai, RT 08/RW 02, Lingkungan Permukiman Warga', '2026-11-09', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:54:51', '2026-11-16 04:54:51'),
(9, 'LW-20261116-3E13C3', 14, 5, NULL, 'Pohon Tumbang Menghalangi Jalan', 'Sebuah pohon tumbang di Jalan Merpati RT 09/RW 03 setelah terjadi hujan disertai angin kencang. Batang dan ranting pohon menghalangi sebagian jalan sehingga mengganggu akses kendaraan dan pejalan kaki. Warga berharap petugas segera melakukan evakuasi pohon agar akses jalan dapat digunakan kembali.', 'alan Merpati, RT 09/RW 03, Lingkungan Permukiman Warga', '2026-10-01', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:56:08', '2026-11-16 04:56:08'),
(10, 'LW-20261116-74539C', 15, 4, NULL, 'Kantor Pelayanan Lingkungan, Jalan Merdeka, RT 10/RW 03', 'Warga mengeluhkan keterlambatan proses pelayanan administrasi di kantor pelayanan lingkungan. Pengurusan surat yang seharusnya dapat diselesaikan dalam waktu singkat membutuhkan waktu lebih lama dari perkiraan. Warga berharap pelayanan administrasi dapat ditingkatkan agar proses pengurusan dokumen menjadi lebih cepat dan efisien.', 'Kantor Pelayanan Lingkungan, Jalan Merdeka, RT 10/RW 03', '2026-11-11', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:57:33', '2026-11-16 04:57:33'),
(11, 'LW-20261116-C175F5', 16, 1, NULL, 'Kerusakan Fasilitas Pos Ronda', 'Fasilitas pos ronda di Jalan Pinus RT 01/RW 03 mengalami kerusakan pada bagian atap dan beberapa tempat duduk. Kondisi tersebut membuat kegiatan ronda malam menjadi kurang nyaman. Warga berharap fasilitas pos ronda segera diperbaiki agar dapat digunakan kembali untuk mendukung keamanan lingkungan.', 'Pos Ronda Jalan Pinus, RT 01/RW 03, Lingkungan Permukiman Warga', '0026-11-11', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:58:43', '2026-11-16 04:58:43'),
(12, 'LW-20261116-B2B862', 17, 5, NULL, 'Pembakaran Sampah yang Mengganggu Warga', 'Terdapat aktivitas pembakaran sampah di sekitar permukiman Jalan Kamboja RT 02/RW 03. Asap dari pembakaran tersebut masuk ke rumah warga dan menimbulkan gangguan pernapasan serta ketidaknyamanan. Warga berharap pengurus lingkungan memberikan imbauan agar masyarakat tidak membakar sampah sembarangan dan menggunakan tempat pembuangan yang telah disediakan.', 'Jalan Kamboja, RT 02/RW 03, Lingkungan Permukiman Warga', '0026-11-11', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 04:59:37', '2026-11-16 04:59:37'),
(13, 'LW-20261116-749C74', 18, 1, NULL, 'Kebocoran Pipa Air Bersih', 'Terjadi kebocoran pipa saluran air bersih di Jalan Bougenville RT 03/RW 04. Air terus mengalir keluar dari bagian pipa yang rusak sehingga menyebabkan jalan menjadi basah dan licin. Warga berharap petugas terkait segera melakukan pemeriksaan dan perbaikan agar tidak terjadi pemborosan air.', 'Jalan Bougenville, RT 03/RW 04, Lingkungan Permukiman Warga', '2026-11-12', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:00:38', '2026-11-16 05:00:38'),
(14, 'LW-20261116-BBE109', 19, 3, NULL, 'Gangguan Ketertiban pada Malam Hari', 'Warga mengeluhkan adanya sekelompok orang yang sering berkumpul hingga larut malam di Jalan Rajawali RT 04/RW 04 dan menimbulkan kebisingan. Aktivitas tersebut mengganggu waktu istirahat masyarakat sekitar. Warga berharap pengurus lingkungan dapat memberikan teguran dan meningkatkan pengawasan agar ketertiban tetap terjaga.', 'Jalan Rajawali, RT 04/RW 04, Lingkungan Permukiman Warga', '2026-11-13', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:01:20', '2026-11-16 05:01:20'),
(15, 'LW-20261116-743BFB', 20, 2, NULL, 'Penumpukan Sampah di Area Pasar', 'Terjadi penumpukan sampah di area pasar lingkungan Jalan Pahlawan RT 05/RW 04. Sampah yang berasal dari aktivitas pedagang belum diangkut secara rutin sehingga menimbulkan bau tidak sedap. Warga dan pedagang berharap jadwal pengangkutan sampah dapat ditingkatkan agar kebersihan pasar tetap terjaga.', 'Area Pasar Lingkungan, Jalan Pahlawan, RT 05/RW 04', '2025-11-13', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:02:13', '2026-11-16 05:02:13'),
(16, 'LW-20261116-B7D7DE', 21, 1, NULL, 'Jalan Lingkungan Tergenang Air', 'Jalan Cemara RT 06/RW 04 sering mengalami genangan air ketika hujan turun. Kondisi tersebut diduga disebabkan oleh permukaan jalan yang lebih rendah dan sistem drainase yang kurang optimal. Genangan mengganggu aktivitas warga dan menyulitkan kendaraan melintas. Warga berharap dilakukan pemeriksaan serta perbaikan sistem drainase', 'Jalan Cemara, RT 06/RW 04, Lingkungan Permukiman Warga', '2026-11-13', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:03:05', '2026-11-16 05:03:05'),
(17, 'LW-20261116-AF8634', 22, 5, NULL, 'Hewan Liar Berkeliaran di Permukiman', 'Warga melaporkan adanya beberapa hewan liar yang sering berkeliaran di sekitar Jalan Akasia RT 07/RW 01. Keberadaan hewan tersebut dikhawatirkan mengganggu kenyamanan dan keselamatan masyarakat, khususnya anak-anak. Warga berharap pihak terkait dapat melakukan penanganan yang tepat agar lingkungan tetap aman dan nyaman.', 'Jalan Akasia, RT 07/RW 01, Lingkungan Permukiman Warga', '2026-11-14', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:03:50', '2026-11-16 05:03:50'),
(18, 'LW-20261116-5E1377', 23, 5, NULL, 'Lampu Jalan di Gang Tidak Berfungsi', 'Lampu penerangan di Gang Melur RT 08/RW 01 tidak menyala sehingga kondisi gang menjadi gelap pada malam hari. Hal ini menyulitkan warga yang hendak keluar masuk lingkungan, terutama pada malam hari. Warga berharap lampu penerangan segera diperiksa dan diperbaiki.', 'Gang Melur, RT 08/RW 01, Lingkungan Permukiman Warga', '2026-11-14', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:05:08', '2026-11-16 05:05:08'),
(19, 'LW-20261116-208399', 24, 2, NULL, 'Pembuangan Sampah Sembarangan', 'Ditemukan sampah rumah tangga yang dibuang sembarangan di lahan kosong sekitar Jalan Nusa Indah RT 09/RW 01. Kondisi tersebut menyebabkan lingkungan terlihat kotor dan menimbulkan bau tidak sedap. Warga berharap pengurus lingkungan memberikan imbauan kepada masyarakat serta menyediakan pengelolaan sampah yang lebih baik.', 'Jalan Nusa Indah, RT 09/RW 01, Lingkungan Permukiman Warga', '2026-11-14', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:06:17', '2026-11-16 05:06:17'),
(20, 'LW-20261116-EB976E', 25, 6, NULL, 'Kerusakan Papan Informasi Lingkungan', 'Papan informasi lingkungan yang berada di Jalan Merdeka RT 10/RW 01 mengalami kerusakan pada bagian rangka dan tulisan sehingga informasi yang ditampilkan sulit dibaca. Papan tersebut sebelumnya digunakan untuk menyampaikan informasi kegiatan masyarakat. Warga berharap fasilitas tersebut segera diperbaiki agar dapat digunakan kembali.', 'Jalan Merdeka, RT 10/RW 01, Lingkungan Permukiman Warga', '2026-11-16', 'a30f47416d2465b6e31f48a228097173bd6d.jpg', 'Menunggu Verifikasi', NULL, '2026-11-16 05:13:43', '2026-11-16 05:13:43'),
(21, 'LW-20261116-432B4F', 26, 1, NULL, 'Saluran Air Meluap Saat Hujan', 'Saluran air di Jalan Wijaya Kusuma RT 01/RW 02 sering meluap ketika hujan deras. Air meluber hingga ke badan jalan dan mengganggu aktivitas warga. Kondisi tersebut diduga disebabkan oleh kapasitas saluran yang tidak memadai serta adanya penyumbatan. Warga berharap dilakukan pemeriksaan dan perbaikan saluran air.', 'Jalan Wijaya Kusuma, RT 01/RW 02, Lingkungan Permukiman Warga', '2026-11-16', 'f7cf8f0a1f2edf266ee2f75d0c38cfc23e8d.jpg', 'Menunggu Verifikasi', NULL, '2026-11-16 05:15:47', '2026-11-16 05:15:47'),
(22, 'LW-20261116-9A47BD', 27, 5, NULL, 'Kebisingan yang Mengganggu Warga', 'Warga mengeluhkan suara musik dengan volume tinggi yang berasal dari salah satu area di Jalan Cemara RT 02/RW 02 hingga larut malam. Kebisingan tersebut mengganggu waktu istirahat masyarakat sekitar. Warga berharap pengurus lingkungan dapat memberikan imbauan agar kegiatan masyarakat tetap memperhatikan kenyamanan warga lainnya.', 'Jalan Cemara, RT 02/RW 02, Lingkungan Permukiman Warga', '2026-11-16', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:16:42', '2026-11-16 05:16:42'),
(23, 'LW-20261116-92A084', 28, 4, NULL, 'Pelayanan Surat Pengantar Terlambat', 'Warga mengalami keterlambatan dalam memperoleh surat pengantar yang diajukan melalui kantor pengurus lingkungan di Jalan Melati RT 03/RW 02. Keterlambatan tersebut menyebabkan warga kesulitan melanjutkan proses administrasi yang diperlukan. Warga berharap proses pelayanan surat pengantar dapat dilakukan secara lebih cepat dan teratur.', 'Kantor Pengurus Lingkungan, Jalan Melati, RT 03/RW 02', '2026-11-16', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:17:50', '2026-11-16 05:17:50'),
(24, 'LW-20261116-516F80', 29, 2, NULL, 'keruskan tempat pembuangan sampah', 'Tempat pembuangan sampah sementara di Jalan Anggrek RT 04/RW 02 mengalami kerusakan pada bagian penutup dan dindingnya. Akibatnya, sampah mudah berserakan dan menimbulkan bau tidak sedap. Warga berharap tempat pembuangan tersebut segera diperbaiki agar kebersihan lingkungan dapat terjaga.', 'Jalan Anggrek, RT 04/RW 02, Lingkungan Permukiman Warga', '2026-11-15', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:18:48', '2026-11-16 05:18:48'),
(25, 'LW-20261116-EAA7EC', 30, 3, 37, 'Hilangnya Fasilitas Umum Lingkungan', 'Warga melaporkan hilangnya salah satu perlengkapan fasilitas umum yang sebelumnya berada di area taman lingkungan Jalan Dahlia RT 05/RW 03. Kejadian tersebut menimbulkan kekhawatiran terhadap keamanan fasilitas umum yang digunakan bersama. Warga berharap pengurus lingkungan melakukan pengecekan dan meningkatkan pengawasan terhadap fasilitas tersebut.', 'taman Lingkungan Jalan Dahlia, RT 05/RW 03, Lingkungan Permukiman', '2026-11-15', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:19:53', '2026-11-16 05:55:43'),
(26, 'LW-20261116-2661FA', 31, 1, 36, 'Kerusakan Jembatan Kecil Permukiman', 'Jembatan kecil yang menghubungkan dua bagian jalan di Jalan Flamboyan RT 06/RW 03 mengalami kerusakan pada bagian permukaannya. Kondisi tersebut membuat warga harus lebih berhati-hati ketika melintas, terutama pengguna sepeda motor. Warga berharap jembatan segera diperbaiki untuk menghindari kecelakaan.', 'Jalan Flamboyan, RT 06/RW 03, Lingkungan Permukiman Warga', '2026-11-15', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:21:03', '2026-11-16 05:55:39'),
(27, 'LW-20261116-A45661', 32, 1, 37, 'Genangan Air Akibat Saluran Tersumbat', 'Terjadi genangan air di sekitar Jalan Kenanga RT 07/RW 03 akibat saluran pembuangan air yang tersumbat. Genangan tersebut mengganggu akses jalan dan berpotensi menjadi tempat berkembangnya nyamuk. Warga berharap saluran segera dibersihkan agar air dapat mengalir dengan lancar dan lingkungan tetap sehat.', 'Jalan Kenanga, RT 07/RW 03, Lingkungan Permukiman Warga', '2026-11-15', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:22:43', '2026-11-16 05:55:30'),
(28, 'LW-20261116-A812F0', 33, 5, 36, 'Penebangan Pohon Tanpa Pemberitahuan', 'Warga menemukan adanya penebangan pohon di sekitar Jalan Pahlawan RT 08/RW 03 yang menyebabkan berkurangnya area penghijauan lingkungan. Masyarakat khawatir tindakan tersebut dapat mengurangi kenyamanan dan kesejukan lingkungan. Warga berharap pengurus lingkungan melakukan pengecekan serta memberikan perhatian terhadap pemeliharaan ruang terbuka hijau.', 'Jalan Pahlawan, RT 08/RW 03, Lingkungan Permukiman Warga', '2026-11-16', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:23:49', '2026-11-16 05:55:27'),
(29, 'LW-20261116-A22659', 34, 6, 37, 'Kerusakan Fasilitas Taman Warga', 'Beberapa fasilitas di taman warga Jalan Cempaka RT 09/RW 04 mengalami kerusakan, seperti bangku taman dan tempat bermain anak. Kondisi tersebut mengurangi kenyamanan masyarakat yang menggunakan taman sebagai tempat beraktivitas dan berkumpul. Warga berharap fasilitas taman segera diperbaiki agar dapat digunakan kembali dengan aman.', 'Taman Warga Jalan Cempaka, RT 09/RW 04, Lingkungan Permukiman Warga', '2026-11-16', NULL, 'Menunggu Verifikasi', NULL, '2026-11-16 05:24:39', '2026-11-16 05:55:23'),
(30, 'LW-20261116-2FD56D', 35, 4, 37, 'Pelayanan Pengaduan Warga Belum Ditindaklanjuti', 'Warga mengeluhkan laporan permasalahan lingkungan yang sebelumnya telah disampaikan kepada pengurus lingkungan, tetapi belum mendapatkan tindak lanjut. Kondisi tersebut membuat masyarakat merasa kurang mendapatkan kepastian mengenai penyelesaian permasalahan yang dilaporkan. Warga berharap setiap pengaduan dapat dicatat, diperiksa, dan ditindaklanjuti secara transparan.', 'Kantor Pengurus Lingkungan, Jalan Mawar, RT 10/RW 04', '2026-11-16', 'ecc2454d82378116488c00db0cf1d9052f7d.jpg', 'Menunggu Verifikasi', NULL, '2026-11-16 05:32:26', '2026-11-16 05:55:20'),
(31, 'LW-20261116-88B3CB', 35, 4, 36, 'Pelayanan Pengaduan Warga Belum Ditindaklanjuti', 'Warga mengeluhkan laporan permasalahan lingkungan yang sebelumnya telah disampaikan kepada pengurus lingkungan, tetapi belum mendapatkan tindak lanjut. Kondisi tersebut membuat masyarakat merasa kurang mendapatkan kepastian mengenai penyelesaian permasalahan yang dilaporkan. Warga berharap setiap pengaduan dapat dicatat, diperiksa, dan ditindaklanjuti secara transparan.', 'Kantor Pengurus Lingkungan, Jalan Mawar, RT 10/RW 04', '2026-11-16', '4a175ce767e86e76ab115b972546bf73f12a.jpg', 'Menunggu Verifikasi', NULL, '2026-11-16 05:32:26', '2026-11-16 05:55:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `report_history`
--

CREATE TABLE `report_history` (
  `id_history` bigint(20) UNSIGNED NOT NULL,
  `id_laporan` bigint(20) UNSIGNED NOT NULL,
  `id_pengguna` int(10) UNSIGNED DEFAULT NULL,
  `status_sebelumnya` varchar(40) DEFAULT NULL,
  `status_baru` varchar(40) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `report_history`
--

INSERT INTO `report_history` (`id_history`, `id_laporan`, `id_pengguna`, `status_sebelumnya`, `status_baru`, `keterangan`, `created_at`) VALUES
(1, 1, 6, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:41:48'),
(2, 2, 7, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:46:15'),
(3, 3, 8, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:47:41'),
(4, 4, 9, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:50:40'),
(5, 5, 10, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:51:42'),
(6, 6, 11, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:52:29'),
(7, 7, 12, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:53:20'),
(8, 8, 13, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:54:51'),
(9, 9, 14, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:56:08'),
(10, 10, 15, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:57:33'),
(11, 11, 16, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:58:43'),
(12, 12, 17, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 04:59:37'),
(13, 13, 18, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:00:38'),
(14, 14, 19, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:01:20'),
(15, 15, 20, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:02:13'),
(16, 16, 21, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:03:05'),
(17, 17, 22, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:03:50'),
(18, 18, 23, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:05:08'),
(19, 19, 24, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:06:17'),
(20, 20, 25, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:13:43'),
(21, 21, 26, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:15:47'),
(22, 22, 27, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:16:42'),
(23, 23, 28, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:17:50'),
(24, 24, 29, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:18:48'),
(25, 25, 30, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:19:53'),
(26, 26, 31, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:21:03'),
(27, 27, 32, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:22:43'),
(28, 28, 33, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:23:49'),
(29, 29, 34, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:24:40'),
(30, 30, 35, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:32:26'),
(31, 31, 35, NULL, 'Menunggu Verifikasi', 'Laporan dibuat oleh masyarakat', '2026-11-16 05:32:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `responses`
--

CREATE TABLE `responses` (
  `id_tanggapan` bigint(20) UNSIGNED NOT NULL,
  `id_laporan` bigint(20) UNSIGNED NOT NULL,
  `id_petugas` int(10) UNSIGNED NOT NULL,
  `isi_tanggapan` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `no_hp` varchar(30) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('masyarakat','petugas','admin_utama') NOT NULL DEFAULT 'masyarakat',
  `status_akun` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `PASSWORD`, `no_hp`, `alamat`, `role`, `status_akun`, `created_at`, `updated_at`) VALUES
(1, 'ulfatun', 'ulfatunnisya@gmail.com', '$2y$10$eXu4cLmCSFdPhPz/BIflG.GvTGKkEm5LkxTKJchYkFLf2ubZTa1pm', NULL, NULL, 'admin_utama', 1, '2026-11-16 04:09:54', '2026-11-16 04:09:54'),
(5, 'sasi', 'sasi@gmail.com', '$2y$10$Ud8hwVr.0EZ7WNhyEzy48eE0qt4PYTBr5M42nTkNT/OfG9RQWvKQK', NULL, NULL, 'admin_utama', 1, '2026-11-16 04:14:10', '2026-11-16 04:14:10'),
(6, 'Dika', 'dika@gmail.com', '$2y$12$V6GJ9ds2LSOnFQTIa5hQAOu.fw43FATn.AZhNsdqoypdV4AVw9e2O', '080100000001', 'Lingkungan RT 01, RW 01', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(7, 'Nisya', 'nisya@gmail.com', '$2y$12$bFZNuL9NBHgH9jRrpQSRP.4wvFjoBux6JV7ZTqeNw27/6eZzxXnbq', '080100000002', 'Lingkungan RT 02, RW 01', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(8, 'Ulfatun', 'ulfatun@gmail.com', '$2y$12$6uX6875168/e96XlfP3DROcU.oJ12f6hxt1mZEGe8CJG/qBOFmXCO', '080100000003', 'Lingkungan RT 03, RW 01', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(9, 'Sabri', 'sabri@gmail.com', '$2y$12$XnMqT.Gpr4P7kW0bTEzSLOvTNzATrLRTNeiYiHYx0H8TXBva3ZQo6', '080100000004', 'Lingkungan RT 04, RW 01', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(10, 'Aulia', 'aulia@gmail.com', '$2y$12$RzNiHRbQtQK9nwD1hsoTMuvedVxW0zTy0f.IIFOBriY.3VfehJXWO', '080100000005', 'Lingkungan RT 05, RW 02', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(11, 'Fajar', 'fajar@gmail.com', '$2y$12$/VROQuAnOAu88cvzTjGjLOY62dQlatvdzaJMu3GW1KtS/zFWcC8Ca', '080100000006', 'Lingkungan RT 06, RW 02', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(12, 'Putri', 'putri@gmail.com', '$2y$12$zCFzoW6JcfrzP9yiMIDFzO1qA20TsG3gkeZQo42hUSGOzb1rjkp9i', '080100000007', 'Lingkungan RT 07, RW 02', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(13, 'Rani', 'rani@gmail.com', '$2y$12$9a07OR2bBPFIYzGAEuUbWu52N95DqYckDzOyV2lHeLkqRRsEHkd5G', '080100000008', 'Lingkungan RT 08, RW 02', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(14, 'Dinda', 'dinda@gmail.com', '$2y$12$f2x2Emm9flFrBUy6W/sgCOO1e9.DLiCR10Xvt9TrAnelgHtnoWZvi', '080100000009', 'Lingkungan RT 09, RW 03', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(15, 'Rian', 'rian@gmail.com', '$2y$12$UQItA57qOb4mb2XHDuErgORZlP9SzHi9TCi.T6xS9sRcsthw0xMJS', '080100000010', 'Lingkungan RT 10, RW 03', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(16, 'Adit', 'adit@gmail.com', '$2y$12$EU1j9/kvBbxO0MIY7K/HBOy4Oim03FMO8b7MEjvJDHFEEZHTf7ZTi', '080100000011', 'Lingkungan RT 01, RW 03', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(17, 'Salsa', 'salsa@gmail.com', '$2y$12$TYdNeFwbA6RZRstuhlylQuktWVLGDAwyPNFdb1VydRSRJ721hYRna', '080100000012', 'Lingkungan RT 02, RW 03', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(18, 'Nanda', 'nanda@gmail.com', '$2y$12$QV5CneIIv0qslHVbiLzOFOyUtQIsznEhdwcdI4.FiMlV2W66a.hBK', '080100000013', 'Lingkungan RT 03, RW 04', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(19, 'Bayu', 'bayu@gmail.com', '$2y$12$vRmPCpOgzENs75lpFxIXJuDt7EP288BZIDFuP00kn1LkCfjCqrxZ.', '080100000014', 'Lingkungan RT 04, RW 04', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(20, 'Vina', 'vina@gmail.com', '$2y$12$Zqi/F3T.8lu2ii3na62jlOvsQZN8YbLu./p05W0qHhCEDe6D.z2Ga', '080100000015', 'Lingkungan RT 05, RW 04', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(21, 'Raka', 'raka@gmail.com', '$2y$12$jQoJnHJ4iYlJM4Jx4QA1DORtWgZ9ZqY3/WKZBynU505RHlDc80AcK', '080100000016', 'Lingkungan RT 06, RW 04', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(22, 'Maya', 'maya@gmail.com', '$2y$12$2UnQeZuYEJbM4mHfdnX58ukY2GSzbox/RYuVE4/wyRAH7OHqJoM/i', '080100000017', 'Lingkungan RT 07, RW 01', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(23, 'Ilham', 'ilham@gmail.com', '$2y$12$mqRmtK4tK9MUZeDyeIGln.6mpi8jZDopDeNVsN8ng2dyuU4umqIza', '080100000018', 'Lingkungan RT 08, RW 01', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(24, 'Novi', 'novi@gmail.com', '$2y$12$Dge6BiuCCSJfyH/x4Of/W.IQJj1ToaYF22Y8EFOr2l8RvEATVzoj2', '080100000019', 'Lingkungan RT 09, RW 01', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(25, 'Yoga', 'yoga@gmail.com', '$2y$12$lFBs8u13jBeF0C2l6vBAFu/tY6FKQc0lYI3M3pMkXkRfmmbFdELzO', '080100000020', 'Lingkungan RT 10, RW 01', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(26, 'Intan', 'intan@gmail.com', '$2y$12$SOWtUQo2AXp1CqMnxch4PeMoJTPx7TObB3td.TzVafLWcM.Iml4ba', '080100000021', 'Lingkungan RT 01, RW 02', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(27, 'Doni', 'doni@gmail.com', '$2y$12$lgXoHa5WJtsCejnS2D1PD.p07.DGsxjCquX3r0JqqflFmxPcye4am', '080100000022', 'Lingkungan RT 02, RW 02', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(28, 'Lia', 'lia@gmail.com', '$2y$12$KfyGcag4ht.bVnxiX0lsVunDd0RYDcIAaDMFcrXliPchrgzDB.jI2', '080100000023', 'Lingkungan RT 03, RW 02', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(29, 'Arif', 'arif@gmail.com', '$2y$12$dgNJErztDMwd3DW2vUDCgecXclXr4elkZaFEuwAWGkZ1ltPbuT5au', '080100000024', 'Lingkungan RT 04, RW 02', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(30, 'Elsa', 'elsa@gmail.com', '$2y$12$NT7iFuKwPJc.PjYyYFPyQ.u8orufL7Ku2Pmxe8GUP4Ku1hrNcpGEy', '080100000025', 'Lingkungan RT 05, RW 03', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(31, 'Reza', 'reza@gmail.com', '$2y$12$4gaK58CY8Wsr99vn2H7h1eAB3cwNa0emFPn.joCfxJjA5NIK73Z5O', '080100000026', 'Lingkungan RT 06, RW 03', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(32, 'Tia', 'tia@gmail.com', '$2y$12$LpGvZNYr/nlpG/e/ZqWp1e0tCEa8nBOKZWn2A91eVlwRVvHvm4HUW', '080100000027', 'Lingkungan RT 07, RW 03', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(33, 'Dewi', 'dewi@gmail.com', '$2y$12$DQwIiwfBbqM5JfL0qk8LQuH5t/cgZGQeTWfOzHdlmkCx17L2/gWl6', '080100000028', 'Lingkungan RT 08, RW 03', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(34, 'Rizal', 'rizal@gmail.com', '$2y$12$0WO3xAftRL3zVqokgyDK5eWEQKzuh95Gw6s1NIPAp4Ooq5Nc/2rBq', '080100000029', 'Lingkungan RT 09, RW 04', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(35, 'Fitri', 'fitri@gmail.com', '$2y$12$mrWxycqyrOkXhEmdR67oHe7cqUU2yPWrQZBQoob3PjS.M3aGCFg1y', '080100000030', 'Lingkungan RT 10, RW 04', 'masyarakat', 1, '2026-11-16 04:33:31', '2026-11-16 04:33:31'),
(36, 'sasikirana', 'kiranasasi@gmail.com', '$2y$10$agvGQcVDby0OkRNXVryueOu8lk1hHaX/FigD2Z/sn8nM/atX9Lk4O', '0882008439412', '', 'petugas', 1, '2026-11-16 05:53:20', '2026-11-16 05:53:20'),
(37, 'raikana', 'rainkana@gmail.com', '$2y$10$6feqDAAnuX.dIj1sQxHDVucAddaWuTmt3Eq20mzcuE1Zs4UzfZtLa', '0882008439413', '', 'petugas', 1, '2026-11-16 05:55:06', '2026-11-16 05:55:06');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `fk_log_user` (`id_pengguna`),
  ADD KEY `idx_log_time` (`created_at`),
  ADD KEY `idx_log_activity` (`aktivitas`);

--
-- Indeks untuk tabel `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id_pengumuman`),
  ADD KEY `fk_announcement_admin` (`id_admin`),
  ADD KEY `idx_announcement_status` (`STATUS`,`created_at`);

--
-- Indeks untuk tabel `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id_setting`),
  ADD UNIQUE KEY `kunci` (`kunci`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `idx_notifications_user_read` (`id_pengguna`,`is_read`,`created_at`);

--
-- Indeks untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id_laporan`),
  ADD UNIQUE KEY `nomor_laporan` (`nomor_laporan`),
  ADD KEY `idx_reports_status` (`STATUS`),
  ADD KEY `idx_reports_tanggal` (`tanggal_kejadian`),
  ADD KEY `idx_reports_petugas` (`id_petugas`),
  ADD KEY `idx_reports_pelapor` (`id_pelapor`),
  ADD KEY `idx_reports_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `report_history`
--
ALTER TABLE `report_history`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `fk_history_user` (`id_pengguna`),
  ADD KEY `idx_history_report_time` (`id_laporan`,`created_at`);

--
-- Indeks untuk tabel `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`id_tanggapan`),
  ADD KEY `fk_response_petugas` (`id_petugas`),
  ADD KEY `idx_responses_laporan` (`id_laporan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_role_status` (`role`,`status_akun`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id_log` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT untuk tabel `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id_pengumuman` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id_setting` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id_kategori` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notifikasi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT untuk tabel `reports`
--
ALTER TABLE `reports`
  MODIFY `id_laporan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `report_history`
--
ALTER TABLE `report_history`
  MODIFY `id_history` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `responses`
--
ALTER TABLE `responses`
  MODIFY `id_tanggapan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `fk_announcement_admin` FOREIGN KEY (`id_admin`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `fk_reports_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `categories` (`id_kategori`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reports_pelapor` FOREIGN KEY (`id_pelapor`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reports_petugas` FOREIGN KEY (`id_petugas`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `report_history`
--
ALTER TABLE `report_history`
  ADD CONSTRAINT `fk_history_laporan` FOREIGN KEY (`id_laporan`) REFERENCES `reports` (`id_laporan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_history_user` FOREIGN KEY (`id_pengguna`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `fk_response_laporan` FOREIGN KEY (`id_laporan`) REFERENCES `reports` (`id_laporan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_response_petugas` FOREIGN KEY (`id_petugas`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
