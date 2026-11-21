<?php
const DB_HOST = 'localhost';
const DB_NAME = 'db_laporwarga1';
const DB_USER = 'root';
const DB_PASS = '';
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    try {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log('LaporWarga database connection failed: ' . $e->getMessage());
        http_response_code(503);
        exit('Database LaporWarga belum dapat diakses. Pastikan MySQL aktif di XAMPP, database db_laporwarga1 sudah diimpor, dan pengaturan config/database.php sesuai.');
    }
}
