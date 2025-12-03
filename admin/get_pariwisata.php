<?php
require_once '../koneksi/koneksi.php';

header('Content-Type: application/json');

error_log("GET Request: " . print_r($_GET, true));

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    error_log("ID yang diterima: " . $id);

    $query = mysqli_query($koneksi, "SELECT * FROM pariwisata WHERE id_pariwisata = '$id'");

    error_log("Query: SELECT * FROM pariwisata WHERE id_pariwisata = '$id'");
    error_log("Jumlah baris: " . mysqli_num_rows($query));

    if ($query && mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        error_log("Data ditemukan: " . print_r($data, true));

        $data['jam_buka'] = !empty($data['jam_buka']) ? substr($data['jam_buka'], 0, 5) : '';
        $data['jam_tutup'] = !empty($data['jam_tutup']) ? substr($data['jam_tutup'], 0, 5) : '';

        $data['zona_waktu'] = $data['zona_waktu'] ?? '';
        $data['satuan_harga'] = $data['satuan_harga'] ?? '';

        $response = [
            'success' => true,
            'id_pariwisata' => $data['id_pariwisata'],
            'nama_pariwisata' => $data['nama_pariwisata'],
            'id_kategori' => $data['id_kategori'],
            'lokasi_pariwisata' => $data['lokasi_pariwisata'],
            'alamat_pariwisata' => $data['alamat_pariwisata'],
            'deskripsi_pariwisata' => $data['deskripsi_pariwisata'],
            'harga_pariwisata' => $data['harga_pariwisata'],
            'satuan_harga' => $data['satuan_harga'],
            'hari_operasional' => $data['hari_operasional'],
            'jam_buka' => $data['jam_buka'],
            'jam_tutup' => $data['jam_tutup'],
            'zona_waktu' => $data['zona_waktu'],
            'rating_pariwisata' => $data['rating_pariwisata']
        ];

        error_log("Response: " . print_r($response, true));

        echo json_encode($response);
    } else {
        $error_message = $query ? "Data tidak ditemukan" : mysqli_error($koneksi);
        error_log("Error: " . $error_message);

        echo json_encode([
            'success' => false,
            'message' => $error_message
        ]);
    }
} else {
    error_log("Error: ID tidak diterima");

    echo json_encode([
        'success' => false,
        'message' => 'ID tidak diterima'
    ]);
}
