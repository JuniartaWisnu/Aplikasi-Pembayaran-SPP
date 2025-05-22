<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Siswa</title>
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
    <a href="dashboard_siswa.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="laporan_siswa.php"><i class="fas fa-folder-open"></i> Laporan</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="navbar-dashboard">
    <h1>Dashboard Siswa</h1>

</div>

<div class="main">
    <div class="welcome-box">
    <div class="welcome-text">
    <h1>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama'] ?? 'Siswa'); ?>!</h1>
    <p>Semoga harimu menyenangkan dan penuh semangat! 🌞</p>
    </div>
    <img src="aset/welcome.png" alt="Welcome Image">
</div>
    </div>
</body>
</html>
