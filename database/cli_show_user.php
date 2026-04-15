<?php
$host = 'localhost';
$db   = 'pbp2026';
$user = 'root';
$pass = '';

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

$sqlUser = "SELECT * FROM user";
$users = $pdo->query($sqlUser)->fetchAll();
$counter =1;

foreach ($users as $row){
    echo $counter.' '.$row['id'].' - '.$row['username'].' - '.$row['email'].'- '.$row['password_hash']."\n";
    $counter++;
}

?>

