<?php
require_once '../koneksi/koneksi.php';

function uploadGambarAsli($file, $target_dir)
{
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $nama_file_asli = basename($file["name"]);
    $target_file = $target_dir . $nama_file_asli;

    $counter = 1;
    $file_name_without_ext = pathinfo($nama_file_asli, PATHINFO_FILENAME);
    $file_extension = pathinfo($nama_file_asli, PATHINFO_EXTENSION);

    while (file_exists($target_file)) {
        $nama_file_asli = $file_name_without_ext . '_' . $counter . '.' . $file_extension;
        $target_file = $target_dir . $nama_file_asli;
        $counter++;
    }

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $file_type = strtolower($file_extension);

    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'message' => 'Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan'];
    }

    if ($file["size"] > 5000000) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'nama_file' => $nama_file_asli];
    } else {
        return ['success' => false, 'message' => 'Gagal mengupload file'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pariwisata = $_POST['id_pariwisata'];
    $nama_pariwisata = $_POST['nama_pariwisata'];
    $id_kategori = $_POST['id_kategori'];
    $lokasi_pariwisata = $_POST['lokasi_pariwisata'];
    $alamat_pariwisata = $_POST['alamat_pariwisata'];
    $deskripsi_pariwisata = $_POST['deskripsi_pariwisata'];
    $harga_pariwisata = $_POST['harga_pariwisata'];
    $satuan_harga = $_POST['satuan_harga'];
    $hari_operasional = $_POST['hari_operasional'];
    $jam_buka = $_POST['jam_buka'];
    $jam_tutup = $_POST['jam_tutup'];
    $zona_waktu = $_POST['zona_waktu'];
    $rating_pariwisata = $_POST['rating_pariwisata'];
    $tanggal_input = date('Y-m-d H:i:s');

    $target_dir = "../admin/gambar-admin/pariwisata/";

    mysqli_begin_transaction($koneksi);

    try {
        $sql_pariwisata = "INSERT INTO pariwisata (id_pariwisata, nama_pariwisata, id_kategori, lokasi_pariwisata, alamat_pariwisata, deskripsi_pariwisata, harga_pariwisata, satuan_harga, hari_operasional, jam_buka, jam_tutup, zona_waktu, rating_pariwisata, tanggal_input) 
                           VALUES ('$id_pariwisata', '$nama_pariwisata', '$id_kategori', '$lokasi_pariwisata', '$alamat_pariwisata', '$deskripsi_pariwisata', '$harga_pariwisata', '$satuan_harga', '$hari_operasional', '$jam_buka', '$jam_tutup', '$zona_waktu', '$rating_pariwisata', '$tanggal_input')";

        if (!mysqli_query($koneksi, $sql_pariwisata)) {
            throw new Exception("Gagal menyimpan data pariwisata: " . mysqli_error($koneksi));
        }
        $gambar_berhasil = 0;
        for ($i = 1; $i <= 5; $i++) {
            $input_name = "gambar_" . $i;

            if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
                $upload_result = uploadGambarAsli($_FILES[$input_name], $target_dir);

                if ($upload_result['success']) {
                    $nama_gambar = $upload_result['nama_file'];
                    $urutan = $i;

                    $result = mysqli_query($koneksi, "SELECT MAX(id_gambar) as max_id FROM pariwisata_gambar");
                    $row = mysqli_fetch_assoc($result);
                    $next_id = $row['max_id'] ? $row['max_id'] + 1 : 3001;

                    $sql_gambar = "INSERT INTO pariwisata_gambar (id_gambar, id_pariwisata, nama_gambar, urutan) 
                                   VALUES ('$next_id', '$id_pariwisata', '$nama_gambar', '$urutan')";

                    if (!mysqli_query($koneksi, $sql_gambar)) {
                        throw new Exception("Gagal menyimpan data gambar ke-$i: " . mysqli_error($koneksi));
                    }

                    $gambar_berhasil++;
                } else {
                    throw new Exception("Gagal upload gambar ke-$i: " . $upload_result['message']);
                }
            } else {
                throw new Exception("Gambar ke-$i tidak diupload atau terjadi error");
            }
        }

        mysqli_commit($koneksi);

        session_start();
        $_SESSION['success_message'] = "Data pariwisata berhasil ditambahkan dengan $gambar_berhasil gambar";
        header("Location: pariwisata.php");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        session_start();
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: pariwisata.php");
        exit();
    }
} else {
    header("Location: pariwisata.php");
    exit();
}
