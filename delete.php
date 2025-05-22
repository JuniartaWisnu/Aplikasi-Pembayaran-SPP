<?php
include 'config.php';
session_start();

// Validasi admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['nis'])) {
    echo "NIS tidak ditemukan.";
    exit;
}

$nis = (int) $_GET['nis'];

// Soft delete (set deleted_at ke waktu sekarang)
$sql = "UPDATE siswa SET deleted_at = NOW() WHERE nis = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $nis);

if ($stmt->execute()) {
    echo "<script>alert('Data siswa berhasil dihapus.'); window.location='siswa.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data siswa.'); window.location='siswa.php';</script>";
}
?>
