<?php
$host = '127.0.0.1';
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

// ==========================
// AMBIL DATA FAKULTAS
// ==========================
$fakultas = ['Fakultas_TEKNIK', 'fakultas_mipa','fkm'];

if (empty($fakultas)) {
    die("Data fakultas kosong.");
}
function seed_accounts() {
    global $pdo, $fakultas;
    // ==========================
    // PREPARED STATEMENT
    // ==========================
    $insertUser = $pdo->prepare("
        INSERT INTO user (username,email, password_hash, auth_key,status, created_at, updated_at)
        VALUES (:username, :email, :password_hash, :auth_key,:status,:created_at,:updated_at)
    ");

    try {
        foreach ($fakultas as $row) {

            // Normalisasi nama fakultas (spasi -> underscore, lowercase)
            $fakultas = strtolower(trim($row));

            $username = "user_$fakultas";
            // PASSWORD UNIK PER FAKULTAS
            $plainPassword = $fakultas.bin2hex(random_bytes(3));
            $passwordHash  = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 13]);


            // Insert ke table user
            $insertUser->execute([
                ':username'       => $username,
                ':email'          => $username . '@uho.ac.id',
                ':password_hash'  => $passwordHash,
                ':status' => 10,
                ':auth_key'       => bin2hex(random_bytes(16)),
                ':created_at'     => time(),
                ':updated_at'     => time()
            ]);


            echo "\n========= \n";
            echo "user: $username \n";
            echo "password: $plainPassword \n";

        }

        
        echo "Seeding user  berhasil.\n";

    } catch (Exception $e) {
        die("Seeding gagal: " . $e->getMessage());
    }
}

function main(){
    seed_accounts();
}

main();



?>