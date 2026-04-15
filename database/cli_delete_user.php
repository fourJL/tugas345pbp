<?php
$host = 'localhost';
$db   = 'pbp2026';
$user = 'root';
$pass = 'password_baru';

// ==========================
// KONFIGURASI DATABASE
// ==========================
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Koneksi DB gagal: " . $e->getMessage());
}

// ==========================
// AMBIL DATA FAKULTAS
// ==========================
$fakultas = ['Fakultas Teknik', 'Fakultas MIPA'];

if (empty($fakultas)) {
    die("Data fakultas kosong.");
}
$sqlUser = "DELETE FROM user";
$del = $pdo->prepare($sqlUser);
$del->execute();

echo "semua user sudah dihapus";


?>

