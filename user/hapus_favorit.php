<?php
session_start();
require_once '../koneksi/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $id_pariwisata = mysqli_real_escape_string($koneksi, $_POST['id_pariwisata']);

    $delete_sql = "DELETE FROM favorit WHERE id_user = '$id_user' AND id_pariwisata = '$id_pariwisata'";

    if (mysqli_query($koneksi, $delete_sql)) {
        echo json_encode(['success' => true, 'message' => 'Berhasil menghapus dari favorit']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus dari favorit: ' . mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
}
