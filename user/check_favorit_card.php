<?php
session_start();
require_once '../koneksi/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['is_favorite' => false]);
    exit;
}

$id_user = $_SESSION['id_user'];
$id_pariwisata = $_POST['id_pariwisata'];

$sql = "SELECT COUNT(*) as total FROM favorit WHERE id_user = '$id_user' AND id_pariwisata = '$id_pariwisata'";
$result = mysqli_query($koneksi, $sql);
$row = mysqli_fetch_assoc($result);

echo json_encode(['is_favorite' => $row['total'] > 0]);
