<?php
ob_start();
session_start();
include 'config.php';
$username = $_POST['username'];
$password = $_POST['password'];
$query = $conn->prepare("SELECT * FROM users WHERE username = ? AND deleted_at IS NULL");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // 2. Verifikasi password
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = strtolower($user['role']); // ← Pastikan lowercase

      // Setelah login berhasil dan $_SESSION['user_id'], $_SESSION['role'] sudah diset
if ($_SESSION['role'] === 'siswa') {
    $stmt = $conn->prepare("SELECT nama FROM siswa WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $_SESSION['nama'] = $row['nama'];
    }
    $stmt->close();
}



        // 3. Debug: Tampilkan role yang dideteksi
        error_log("Role detected: " . $_SESSION['role']);

        // 4. Redirect berdasarkan role
        if ($_SESSION['role'] === 'admin') {
            if (file_exists('dashboard_admin.php')) {
                header("Location: dashboard_admin.php");
            } else {
                die("Error: File admin dashboard tidak ditemukan!");
            }
        } else {
            header("Location: dashboard_siswa.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Password salah.";
    }
} else {
    $_SESSION['error'] = "Username tidak ditemukan.";
}

header("Location: login.php");
exit();