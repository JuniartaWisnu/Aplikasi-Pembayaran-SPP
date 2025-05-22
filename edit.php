<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['nis'])) {
    echo "ID siswa tidak ditemukan.";
    exit;
}

$nis = (int) $_GET['nis'];

// Ambil data siswa
$sql = "SELECT * FROM siswa WHERE nis = ? AND deleted_at IS NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $nis);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Data siswa tidak ditemukan.";
    exit;
}

$siswa = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];

    $sql_update = "UPDATE siswa SET nama = ?, kelas = ?, alamat = ?, updated_at = NOW() WHERE nis = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssi", $nama, $kelas, $alamat, $nis);

    if ($stmt->execute()) {
        echo "<script>alert('Data siswa berhasil diperbarui.'); window.location='siswa.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data: {$stmt->error}');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Siswa</title>
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
    <h1>Edit Siswa</h1>
    <form action="siswa.php" method="GET" class="search-form">
        <input type="text" name="cari" placeholder="Cari nama siswa..." required>
        <button type="submit">Cari</button>
    </form>
</div>

<div class="main">
    <form method="post">
         <h2>Edit Siswa</h2>
        <label>Nama:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($siswa['nama']) ?>" required><br>

        <label>Kelas:</label><br>
        <input type="text" name="kelas" value="<?= htmlspecialchars($siswa['kelas']) ?>" required><br>

        <label>Alamat:</label><br>
        <textarea name="alamat" rows="3" required><?= htmlspecialchars($siswa['alamat']) ?></textarea><br><br>
        <button class="button" type="submit">Simpan</button>
        <button class="button" onclick="window.location.href='siswa.php'"> Kembali</button>

    </form>
</body>
</html>
