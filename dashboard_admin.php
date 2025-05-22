<?php
session_start();
include 'config.php';

// Pengecekan role harus di awal sebelum output apapun
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}



// Total siswa aktif
$result_total = $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE deleted_at IS NULL");
$total_siswa = $result_total->fetch_assoc()['total'] ?? 0;

// Total siswa aktif
$result_total = $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE deleted_at IS NULL");
$total_siswa = $result_total->fetch_assoc()['total'] ?? 0;

// Hitung bulan berjalan sejak Januari 2024
$start = new DateTime('2024-01-01');
$now = new DateTime();
$bulan_berjalan = ($now->format('Y') - $start->format('Y')) * 12 + ($now->format('n') - $start->format('n')) + 1;
$total_tagihan = $bulan_berjalan * 125000;

// Siswa yang belum pernah bayar
$result_belum_bayar = $conn->query("
    SELECT COUNT(*) AS total_belum_bayar
    FROM siswa s
    WHERE s.deleted_at IS NULL
      AND NOT EXISTS (
          SELECT 1 FROM pembayaran p
          WHERE p.siswa_id = s.nis AND (p.deleted_at IS NULL OR p.deleted_at = '')
      )
");
$total_belum_bayar = $result_belum_bayar->fetch_assoc()['total_belum_bayar'] ?? 0;

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
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
    <h1>Dashboard</h1>
    <form action="siswa.php" method="GET" class="search-form">
        <input type="text" name="cari" placeholder="Cari nama siswa..." required>
        <button type="submit">Cari</button>
    </form>
</div>


<div class="main">
  <div class="welcome-box">
    <div class="welcome-text">
        <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
        <p>Semoga harimu menyenangkan dan penuh semangat! 🌞</p>
    </div>
    <img src="aset/welcome.png" alt="Welcome Image">
</div>

   <div class="stats">
    <div class="card">
        <i class="fas fa-user-graduate fa-2x" style="color:#3498db;" ></i>
        <h3><?= $total_siswa ?></h3>
        <a href="siswa.php"><p>Total Siswa</p></a>
    </div>

    <div class="card">
        <i class="fas fa-money-check fa-2x" style="color:#f39c12;"></i>
        <h3><?= $total_belum_bayar ?></h3>
        <p>Siswa Belum Bayar</p>
    </div>
</div>

   
</div>

</body>
</html>
