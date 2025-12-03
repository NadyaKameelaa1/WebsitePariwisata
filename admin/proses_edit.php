<?php
require_once '../koneksi/koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pariwisata = $_POST['id_pariwisata'];
    $nama_pariwisata = mysqli_real_escape_string($koneksi, $_POST['nama_pariwisata']);
    $id_kategori = $_POST['id_kategori'];
    $lokasi_pariwisata = mysqli_real_escape_string($koneksi, $_POST['lokasi_pariwisata']);
    $alamat_pariwisata = mysqli_real_escape_string($koneksi, $_POST['alamat_pariwisata']);
    $deskripsi_pariwisata = mysqli_real_escape_string($koneksi, $_POST['deskripsi_pariwisata']);
    $harga_pariwisata = $_POST['harga_pariwisata'];
    $satuan_harga = $_POST['satuan_harga'];
    $hari_operasional = $_POST['hari_operasional'];
    $jam_buka = $_POST['jam_buka'] ?: null;
    $jam_tutup = $_POST['jam_tutup'] ?: null;
    $zona_waktu = $_POST['zona_waktu'] ?: null;
    $rating_pariwisata = $_POST['rating_pariwisata'];

    $sql = "UPDATE pariwisata SET 
            nama_pariwisata = '$nama_pariwisata',
            id_kategori = '$id_kategori',
            lokasi_pariwisata = '$lokasi_pariwisata',
            alamat_pariwisata = '$alamat_pariwisata',
            deskripsi_pariwisata = '$deskripsi_pariwisata',
            harga_pariwisata = '$harga_pariwisata',
            satuan_harga = '$satuan_harga',
            hari_operasional = '$hari_operasional',
            jam_buka = " . ($jam_buka ? "'$jam_buka'" : "NULL") . ",
            jam_tutup = " . ($jam_tutup ? "'$jam_tutup'" : "NULL") . ",
            zona_waktu = " . ($zona_waktu ? "'$zona_waktu'" : "NULL") . ",
            rating_pariwisata = '$rating_pariwisata'
            WHERE id_pariwisata = '$id_pariwisata'";

    if (mysqli_query($koneksi, $sql)) {
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil diperbarui'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal memperbarui data: ' . mysqli_error($koneksi)
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Metode request tidak valid'
    ]);
}
