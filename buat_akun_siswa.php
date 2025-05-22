<?php
include 'config.php';
session_start();

// Tambahkan ini untuk membantu debugging (WAJIB SAAT ERROR)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Hanya admin yang bisa membuat akun siswa.'); window.location='dashboard.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama     = trim($_POST['nama']);
    $kelas    = trim($_POST['kelas']);
    $alamat   = trim($_POST['alamat']);
    $role     = 'siswa';

    if (empty($username) || empty($password) || empty($nama) || empty($kelas) || empty($alamat)) {
        echo "<script>alert('Semua kolom wajib diisi.');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Mulai transaksi
            $conn->begin_transaction();

            // Cek apakah username sudah ada
            $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $check->bind_param("s", $username);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                throw new Exception("Username sudah digunakan.");
            }

            // Insert ke tabel users
            $stmt = $conn->prepare("INSERT INTO users (username, password, role, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $username, $hashed_password, $role);
            $stmt->execute();

            $user_id = $conn->insert_id;

            // Insert ke tabel siswa
            $stmt2 = $conn->prepare("INSERT INTO siswa (user_id, nama, kelas, alamat, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt2->bind_param("isss", $user_id, $nama, $kelas, $alamat);
            $stmt2->execute();

            // Commit transaksi
            $conn->commit();

            echo "<script>alert('Akun siswa berhasil dibuat.'); window.location='siswa.php';</script>";
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Terjadi kesalahan: " . addslashes($e->getMessage()) . "');</script>";
        } finally {
            if (isset($stmt)) $stmt->close();
            if (isset($stmt2)) $stmt2->close();
            if (isset($check)) $check->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Siswa</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</head>
<body>
<div class="sidebar">
    <nav style="display: flex; align-items: center; padding: 10px 20px; background-color: #2c3e50;">
    <img src="aset/logo.png" alt="Logo" style="height: 60px; margin-right: 15px;">
    <h2 style="color: white; margin: 0;">MySPP</h2>
</nav>
<br>
    <a href="dashboard_admin.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="siswa.php"><i class="fas fa-users"></i> Siswa</a>
    <a href="payment.php"><i class="fas fa-money-bill-wave"></i> Pembayaran</a>
    <a href="laporan.php"><i class="fas fa-folder-open"></i> Laporan</a>
    <a href="buat_akun_siswa.php"><i class="fas fa-user-plus"></i> Akun Siswa</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="navbar-dashboard">
    <h1>Buat Akun Siswa</h1>
</div>

<div class="mainnnn">
    <form method="post">
        <div class="form-group">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat:</label>
            <textarea id="alamat" name="alamat" required></textarea>
        </div>
        <div class="form-group">
            <label for="username">NISN:</label>
            <input type="number" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="siswa">Siswa</option>
            </select>
        </div>
        <button class="button" type="submit">Buat Akun Siswa</button>
    </form>
</div>
      
</body>
</html>
