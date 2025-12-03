<?php
session_start();
require_once '../koneksi/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['is_favorite' => false]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $id_pariwisata = mysqli_real_escape_string($koneksi, $_POST['id_pariwisata']);

    $sql = "SELECT * FROM favorit WHERE id_user = '$id_user' AND id_pariwisata = '$id_pariwisata'";
    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        echo json_encode(['is_favorite' => mysqli_num_rows($query) > 0]);
    } else {
        echo json_encode(['is_favorite' => false, 'error' => mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['is_favorite' => false]);
}
