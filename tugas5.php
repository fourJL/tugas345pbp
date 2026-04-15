<?php
// ================== KONEKSI DATABASE ==================
$host = "localhost";
$db   = "pbp2026";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// ================== PROSES HAPUS ==================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $stmt = $pdo->prepare("DELETE FROM user WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}

// ================== PROSES TAMBAH & UPDATE ==================
if (isset($_POST['simpan'])) {
    $id       = $_POST['id'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $time = time();

    if ($id == "") {
        // TAMBAH DATA
        $sql = "INSERT INTO user 
        (username, email, password_hash, auth_key, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, 10, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            bin2hex(random_bytes(16)),
            $time,
            $time
        ]);
    } else {
        // UPDATE DATA
        if ($password != "") {
            $sql = "UPDATE user SET username=?, email=?, password_hash=?, updated_at=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $username,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $time,
                $id
            ]);
        } else {
            $sql = "UPDATE user SET username=?, email=?, updated_at=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $username,
                $email,
                $time,
                $id
            ]);
        }
    }

    header("Location: index.php");
    exit;
}

// ================== AMBIL DATA UNTUK EDIT ==================
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE id=?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ================== AMBIL SEMUA DATA ==================
$data = $pdo->query("SELECT * FROM user ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD User</title>
</head>
<body>

<h2>CRUD DATA USER</h2>

<!-- ================== FORM ================== -->
<form method="POST">
    <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">

    <label>Username:</label><br>
    <input type="text" name="username" required value="<?= $edit['username'] ?? '' ?>"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required value="<?= $edit['email'] ?? '' ?>"><br><br>

    <label>Password (kosongkan jika tidak diubah):</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit" name="simpan">Simpan</button>
</form>

<hr>

<!-- ================== TABEL DATA ================== -->
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($data as $row): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['username'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['status'] ?></td>
        <td>
            <a href="?edit=<?= $row['id'] ?>">Edit</a> |
            <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>