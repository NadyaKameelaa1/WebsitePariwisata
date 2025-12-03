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

    $cek_sql = "SELECT * FROM favorit WHERE id_user = '$id_user' AND id_pariwisata = '$id_pariwisata'";
    $cek_query = mysqli_query($koneksi, $cek_sql);

    if (mysqli_num_rows($cek_query) > 0) {
        echo json_encode(['success' => false, 'message' => 'Sudah ada di favorit']);
        exit;
    }

    $tanggal_favorit = date('Y-m-d H:i:s');
    $insert_sql = "INSERT INTO favorit (id_user, id_pariwisata, tanggal_favorit) 
                   VALUES ('$id_user', '$id_pariwisata', '$tanggal_favorit')";

    if (mysqli_query($koneksi, $insert_sql)) {
        echo json_encode(['success' => true, 'message' => 'Berhasil menambahkan ke favorit']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan ke favorit: ' . mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
}
