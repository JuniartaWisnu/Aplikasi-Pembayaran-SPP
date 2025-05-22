<?php
include 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validasi login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$dashboard_url = ($_SESSION['role'] === 'admin') ? 'dashboard_admin.php' : 'dashboard_siswa.php';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembayaran SPP</title>
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
    <h1>Laporan Pembayaran SPP Siswa</h1>
</div>

<div class="mainnn">
<?php
if ($role === 'siswa') {
    // Ambil info siswa berdasarkan user_id
    $sql = "SELECT * FROM siswa WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $siswa = $result->fetch_assoc();

    if ($siswa) {
        $nis = $siswa['nis'];
        echo "<h3>" . htmlspecialchars($siswa['nama']) . " (NIS: {$nis})</h3>";

        // Ambil data pembayaran siswa
        $sql = "SELECT p.*, u.username AS petugas 
                FROM pembayaran p
                JOIN users u ON p.user_id = u.id
                WHERE p.siswa_id = ? AND (p.deleted_at IS NULL OR p.deleted_at = '')
                ORDER BY p.tanggal_pembayaran DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $nis);
        $stmt->execute();
        $result = $stmt->get_result();

        include 'tabel_pembayaran.php';
    } else {
        echo "<p style='color:red;'>Data siswa tidak ditemukan untuk akun ini.</p>";
    }

} elseif ($role === 'admin') {

    // Form pencarian
    // echo '<h1>Laporan Pembayaran SPP</h1>';
    echo '<form action="laporan.php" method="GET" class="search-form" style="margin: 10px 0;">
            <input type="text" name="cari" placeholder="Cari nama siswa..." value="' . htmlspecialchars($_GET['cari'] ?? '') . '">
            <button type="submit">Cari</button>
          </form>';

    // Ambil parameter pencarian jika ada
    $cari = $_GET['cari'] ?? '';

    if (!empty($cari)) {
        $stmt = $conn->prepare("SELECT nis, nama FROM siswa WHERE deleted_at IS NULL AND nama LIKE ? ORDER BY nama ASC");
        $like = "%$cari%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $siswa_result = $stmt->get_result();
    } else {
        $siswa_result = $conn->query("SELECT nis, nama FROM siswa WHERE deleted_at IS NULL ORDER BY nama ASC");
    }

    if ($siswa_result->num_rows > 0) {
        while ($siswa = $siswa_result->fetch_assoc()) {
            $nis = $siswa['nis'];
            echo "<h3>" . htmlspecialchars($siswa['nama']) . " (NIS: {$nis})</h3>";

            $sql = "SELECT p.*, u.username AS petugas 
                    FROM pembayaran p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.siswa_id = ? AND (p.deleted_at IS NULL OR p.deleted_at = '')
                    ORDER BY p.tanggal_pembayaran DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $nis);
            $stmt->execute();
            $result = $stmt->get_result();

            include 'tabel_pembayaran.php';
        }
    } else {
        echo "<p>Tidak ada siswa ditemukan.</p>";
    }
}
?>
 <button class="button" type="button" onclick="window.location.href='dashboard_siswa.php'">Kembali</button>
</div>



</body>
</html>
