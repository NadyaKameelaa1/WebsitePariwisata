<?php
session_start();
require_once '../koneksi/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$id_user = $_SESSION['id_user'];
$id_pariwisata = $_POST['id_pariwisata'];

$cek_sql = "SELECT COUNT(*) as total FROM favorit WHERE id_user = '$id_user' AND id_pariwisata = '$id_pariwisata'";
$cek_result = mysqli_query($koneksi, $cek_sql);
$cek_row = mysqli_fetch_assoc($cek_result);

if ($cek_row['total'] > 0) {
    echo json_encode(['success' => false, 'message' => 'Sudah ada di favorit']);
    exit;
}

$sql = "INSERT INTO favorit (id_user, id_pariwisata, tanggal_favorit) VALUES ('$id_user', '$id_pariwisata', NOW())";
$result = mysqli_query($koneksi, $sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Berhasil ditambahkan ke favorit']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan ke favorit']);
}
