<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// PROSES FORM PEMBAYARAN YANG DIPERBAIKI
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_id = (int) $_POST['siswa_id'];
    $user_id = $_SESSION['user_id'];
    $tanggal_pembayaran = $_POST['tanggal_pembayaran'];
    $nominal = (int) $_POST['nominal'];
    $bulan_mulai_input = $_POST['bulan_mulai'];

    // Validasi input
    if ($siswa_id <= 0 || $nominal <= 0 || empty($tanggal_pembayaran) || empty($bulan_mulai_input)) {
        $_SESSION['error'] = "Data yang dimasukkan tidak valid";
        header("Location: payment.php");
        exit;
    }

    // Format tanggal
    $bulan_mulai = date('Y-m-01', strtotime($bulan_mulai_input));
    $tarif_spp = 125000;
    $sisa_pembayaran = $nominal;

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Ambil histori pembayaran siswa
        $query = "SELECT bulan_tagihan, SUM(nominal) AS total_bayar 
                  FROM pembayaran 
                  WHERE siswa_id = ? AND deleted_at IS NULL 
                  GROUP BY bulan_tagihan";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $siswa_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $tagihan = [];
        while ($row = $result->fetch_assoc()) {
            $tagihan[$row['bulan_tagihan']] = (int) $row['total_bayar'];
        }

        // Proses pembayaran
        $current_bulan = $bulan_mulai;
        while ($sisa_pembayaran > 0) {
            $total_bayar = $tagihan[$current_bulan] ?? 0;
            $kurang = $tarif_spp - $total_bayar;

            if ($kurang <= 0) {
                $current_bulan = date('Y-m-01', strtotime($current_bulan . ' +1 month'));
                continue;
            }

            $dibayar = min($sisa_pembayaran, $kurang);
            $status = ($total_bayar + $dibayar) >= $tarif_spp ? 1 : 0;
            $selisih = ($total_bayar + $dibayar) - $tarif_spp;

            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO pembayaran 
                                   (siswa_id, user_id, nominal, status, selisih, tanggal_pembayaran, bulan_tagihan, created_at) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("iiiisss", $siswa_id, $user_id, $dibayar, $status, $selisih, $tanggal_pembayaran, $current_bulan);
            $stmt->execute();

            $sisa_pembayaran -= $dibayar;
            if ($dibayar >= $kurang) {
                $current_bulan = date('Y-m-01', strtotime($current_bulan . ' +1 month'));
            }
        }

        $conn->commit();
        $_SESSION['success'] = "Pembayaran berhasil disimpan!";
        header("Location: laporan.php"); // REDIRECT KE LAPORAN
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Gagal menyimpan pembayaran: " . $e->getMessage();
        header("Location: payment.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pembayaran</title>
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
    <h1>Pembayaran SPP</h1>
    
    <!-- Form pencarian yang diperbaiki -->
    <form action="payment.php" method="GET" class="search-form" style="margin: 10px 0;">
        <input type="text" name="cari" placeholder="Cari NIS atau Nama Siswa..." value="<?php echo htmlspecialchars($_GET['cari'] ?? ''); ?>">
        <button type="submit">Cari</button>
    </form>
    
    <?php
    // Ambil parameter pencarian jika ada
    $cari = $_GET['cari'] ?? '';
    
    // Jika ada pencarian, tampilkan hasil
    if (!empty($cari)) {
        $query = "SELECT s.nis, s.nama, 
                 (SELECT COUNT(*) FROM pembayaran p WHERE p.siswa_id = s.nis AND (p.deleted_at IS NULL OR p.deleted_at = '')) as jumlah_pembayaran
                 FROM siswa s
                 WHERE (s.nis LIKE ? OR s.nama LIKE ?) 
                 AND (s.deleted_at IS NULL OR s.deleted_at = '')
                 ORDER BY s.nama ASC";
        
        $stmt = $conn->prepare($query);
        $like = "%$cari%";
        $stmt->bind_param("ss", $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
        
    }
    ?>
</div>

<div class="mainn margin-top">
    <form method="post">
        <h2>Form Pembayaran SPP</h2>

        <label>Pilih Siswa:</label><br>
        <select name="siswa_id" required>
            <option value="">-- Pilih Siswa --</option>
            <?php
            // Query untuk dropdown siswa dengan filter pencarian jika ada
            $query = "SELECT nis, nama FROM siswa WHERE deleted_at IS NULL";
            if (!empty($cari)) {
                $query .= " AND (nis LIKE '%$cari%' OR nama LIKE '%$cari%')";
            }
            $query .= " ORDER BY nama ASC";
            
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['nis']}'>{$row['nis']} - {$row['nama']}</option>";
            }
            ?>
        </select><br><br>

        <label>Tanggal Bayar:</label><br>
        <input type="date" name="tanggal_pembayaran" required><br><br>

        <label>Jumlah Bayar (Rp):</label><br>
        <input type="number" name="nominal" min="1" required><br><br>

        <label>Mulai Bayar Bulan:</label><br>
        <input type="month" name="bulan_mulai" required><br><br>

        <button class="button" type="submit">Simpan Pembayaran</button>
    </form>
</div>
</body>
</html>