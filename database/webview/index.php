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
$fakultas = ['Fakultas_Teknik', 'Fakultas_MIPA'];

if (empty($fakultas)) {
    die("Data fakultas kosong.");
}
$sqlUser = "SELECT * FROM user";
$users = $pdo->query($sqlUser)->fetchAll();


// ==========================
// HANDLE POST REQUEST
// ==========================
$seeding_result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            seed_accounts();
        } catch (Exception $e) {
            $seeding_result = 'error: ' . $e->getMessage();
        }
    }
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
            $kode_fakultas = strtolower(trim($row));

            $username = "user_$kode_fakultas";
            // PASSWORD UNIK PER FAKULTAS
            $plainPassword = $kode_fakultas.bin2hex(random_bytes(3));
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


            echo "<br>========= <br>";
            echo "user: $username <br>";
            echo "password: $plainPassword <br>";

        }

        
        echo "Seeding user  berhasil.\n";

    } catch (Exception $e) {
        die("Seeding gagal: " . $e->getMessage());
    }
}
?>

<html>
<head>
    <title>Data User </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .button-group {
            margin: 20px 0;
        }
        button {
            padding: 10px 20px;
            margin-right: 10px;
            font-size: 14px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>    
<div class="container">
    <h1>Seeding User  & </h1>
    
    <div class="button-group">
        <form method="POST" style="display: inline;">
            <button type="submit" name="action" value="">Seed User </button>
        </form>
        <form method="POST" style="display: inline;">
            <button type="submit" name="action" value="" style="background-color: #2196F3;">Seed User </button>
        </form>
        <a href="/" ><button type="button" style="background-color: #f44336;">refresh</button></a>
    </div>
     <?php if ($seeding_result): ?>
        <?php if (strpos($seeding_result, 'success') === 0): ?>
            <div class="success">
                <strong>✓ Seeding Berhasil!</strong>
                <p>
                    <?php 
                    if ($seeding_result === 'success_') {
                        echo 'User  telah berhasil dibuat untuk semua fakultas.';
                    } elseif ($seeding_result === 'success_') {
                        echo 'User  telah berhasil dibuat (5 user).';
                    }
                    ?>
                </p>
            </div>
        <?php else: ?>
            <div class="error">
                <strong>✗ Seeding Gagal!</strong>
                <p><?php echo htmlspecialchars($seeding_result); ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div>

    </div>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
        </tr>
        <?php foreach ($users as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
   

</body>
</html>    
