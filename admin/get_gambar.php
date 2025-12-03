<?php
require_once '../koneksi/koneksi.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $query = mysqli_query($koneksi, "SELECT * FROM pariwisata_gambar WHERE id_pariwisata = '$id' ORDER BY urutan");

    if ($query) {
        $gambar = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $gambar[] = $row;
        }

        echo json_encode([
            'success' => true,
            'gambar' => $gambar
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengambil data gambar: ' . mysqli_error($koneksi)
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'ID tidak diterima'
    ]);
}
