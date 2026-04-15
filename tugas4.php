<?php
// Konfigurasi database
$host = "localhost";
$db   = "pbp2026";
$user = "root";
$pass = "";

// Pastikan dijalankan di CLI
if (php_sapi_name() !== 'cli') {
    die("Program ini hanya bisa dijalankan melalui CLI.\n");
}

try {
    // Koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== UPDATE DATA USER ===\n";

    // Input ID
    echo "Masukkan ID user: ";
    $id = trim(fgets(STDIN));

    // Cek apakah user ada
    $cek = $pdo->prepare("SELECT * FROM user WHERE id = :id");
    $cek->execute([':id' => $id]);
    $data = $cek->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo "❌ User dengan ID $id tidak ditemukan.\n";
        exit;
    }

    echo "Data lama:\n";
    echo "Username: {$data['username']}\n";
    echo "Email   : {$data['email']}\n";

    // Input data baru
    echo "\nMasukkan username baru (kosongkan jika tidak diubah): ";
    $username = trim(fgets(STDIN));

    echo "Masukkan email baru (kosongkan jika tidak diubah): ";
    $email = trim(fgets(STDIN));

    echo "Masukkan password baru (kosongkan jika tidak diubah): ";
    $password = trim(fgets(STDIN));

    // Gunakan data lama jika kosong
    if ($username == "") $username = $data['username'];
    if ($email == "") $email = $data['email'];

    // Jika password diisi, hash password
    if ($password != "") {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $password_hash = $data['password_hash'];
    }

    // Update waktu
    $updated_at = time();

    // Query update
    $sql = "UPDATE user 
            SET username = :username, 
                email = :email, 
                password_hash = :password_hash,
                updated_at = :updated_at
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password_hash' => $password_hash,
        ':updated_at' => $updated_at,
        ':id' => $id
    ]);

    echo "✅ Data user berhasil diupdate!\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}