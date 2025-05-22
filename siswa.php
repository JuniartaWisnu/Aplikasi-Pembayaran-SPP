<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login dahulu.'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses tambah data siswa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];

    // Pastikan data tidak kosong dan sanitasi
    $nama = trim($nama);
    $kelas = trim($kelas);
    $alamat = trim($alamat);

    if ($nama && $kelas && $alamat) {
        $sql = "INSERT INTO siswa (user_id, nama, kelas, alamat, created_at, updated_at, deleted_at)
                VALUES (?, ?, ?, ?, NOW(), NOW(), NULL)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $nama, $kelas, $alamat);

        if ($stmt->execute()) {
            echo "<script>alert('Data siswa berhasil ditambahkan.'); window.location='siswa.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal menambahkan data siswa: " . htmlspecialchars($stmt->error) . "');</script>";
        }
    } else {
        echo "<script>alert('Semua field harus diisi dengan benar.');</script>";
    }
}

// Ambil data siswa yang belum dihapus, dan bisa pencarian
$search = '';
if (isset($_GET['cari'])) {
    $search = $conn->real_escape_string($_GET['cari']);
    $query = "SELECT * FROM siswa WHERE deleted_at IS NULL AND nama LIKE '%$search%' ORDER BY nis DESC";
} else {
    $query = "SELECT * FROM siswa WHERE deleted_at IS NULL ORDER BY nis DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
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
    <h1>Data Siswa</h1>
<!-- Form pencarian -->
   <form action="siswa.php" method="GET" class="search-form">
        <input type="text" name="cari" placeholder="Cari nama siswa..." required>
        <button type="submit">Cari</button>
    </form>
   
</div>

<div class="main">
<table>
    <tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Alamat</th>
        <th>Aksi</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($siswa = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($siswa['nis']) ?></td>
                <td><?= htmlspecialchars($siswa['nama']) ?></td>
                <td><?= htmlspecialchars($siswa['kelas']) ?></td>
                <td><?= htmlspecialchars($siswa['alamat']) ?></td>
                <td>
                    <a  class="button" href="edit.php?nis=<?= urlencode($siswa['nis']) ?>">Edit</a> 
                    <a class="button" href="delete.php?nis=<?= urlencode($siswa['nis']) ?>" onclick="return confirm('Yakin ingin hapus siswa ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">Belum ada data siswa.</td></tr>
    <?php endif; ?>
</table>

    <button class="button" type="button" onclick="window.location.href='dashboard_admin.php'">Kembali</button>
</form>
</div>

</body>
</html>
