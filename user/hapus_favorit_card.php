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

$sql = "DELETE FROM favorit WHERE id_user = '$id_user' AND id_pariwisata = '$id_pariwisata'";
$result = mysqli_query($koneksi, $sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Berhasil dihapus dari favorit']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus dari favorit']);
}
