<?php
include 'config.php';

$keyword = $_GET['keyword'] ?? '';

if (strlen($keyword) < 2) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT siswa_id, nama, nis FROM siswa 
        WHERE (nama LIKE ? OR nis LIKE ?) AND deleted_at IS NULL 
        ORDER BY nama ASC";
$stmt = $conn->prepare($sql);
$search = '%' . $keyword . '%';
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
