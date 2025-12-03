<?php
require_once '../koneksi/koneksi.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

function uploadGambar($file, $target_dir, $id_pariwisata, $urutan)
{
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Tidak ada file yang diupload'];
    }

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'message' => 'Hanya file JPG, JPEG, PNG yang diperbolehkan'];
    }

    if ($file['size'] > 5000000) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }

    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $nama_file_unik = "pariwisata_{$id_pariwisata}_gambar{$urutan}_" . time() . "_" . rand(1000, 9999) . "." . $file_extension;
    $target_file = $target_dir . $nama_file_unik;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'nama_file' => $nama_file_unik];
    } else {
        return ['success' => false, 'message' => 'Gagal mengupload file'];
    }
}

function hapusGambarLama($nama_file, $target_dir)
{
    if (empty($nama_file)) {
        return false;
    }

    $file_path = $target_dir . $nama_file;

    if (file_exists($file_path) && is_file($file_path)) {
        return unlink($file_path);
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_pariwisata = isset($_POST['id_pariwisata']) ? mysqli_real_escape_string($koneksi, $_POST['id_pariwisata']) : null;

    if (!$id_pariwisata) {
        echo json_encode([
            'success' => false,
            'message' => 'ID Pariwisata tidak valid'
        ]);
        exit;
    }

    $target_dir = "../admin/gambar-admin/pariwisata/";

    mysqli_begin_transaction($koneksi);

    try {
        $gambar_lama_query = mysqli_query($koneksi, "SELECT id_gambar, nama_gambar, urutan FROM pariwisata_gambar WHERE id_pariwisata = '$id_pariwisata' ORDER BY urutan");

        $gambar_lama = [];
        while ($row = mysqli_fetch_assoc($gambar_lama_query)) {
            $gambar_lama[$row['urutan']] = [
                'id_gambar' => $row['id_gambar'],
                'nama_gambar' => $row['nama_gambar']
            ];
        }

        $gambar_berhasil = 0;
        $gambar_diupdate = 0;
        $gambar_gagal = [];

        for ($i = 1; $i <= 5; $i++) {
            $input_name = "gambar_" . $i;

            if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === UPLOAD_ERR_OK && $_FILES[$input_name]['size'] > 0) {

                $upload_result = uploadGambar($_FILES[$input_name], $target_dir, $id_pariwisata, $i);

                if ($upload_result['success']) {
                    $nama_gambar_baru = $upload_result['nama_file'];

                    if (isset($gambar_lama[$i])) {
                        $id_gambar = $gambar_lama[$i]['id_gambar'];
                        $nama_gambar_lama = $gambar_lama[$i]['nama_gambar'];

                        hapusGambarLama($nama_gambar_lama, $target_dir);

                        $sql = "UPDATE pariwisata_gambar SET nama_gambar = '$nama_gambar_baru' WHERE id_gambar = '$id_gambar'";

                        if (mysqli_query($koneksi, $sql)) {
                            $gambar_berhasil++;
                            $gambar_diupdate++;
                        } else {
                            $gambar_gagal[] = "Gambar $i: " . mysqli_error($koneksi);
                        }
                    } else {
                        $result = mysqli_query($koneksi, "SELECT MAX(id_gambar) as max_id FROM pariwisata_gambar");
                        $row = mysqli_fetch_assoc($result);
                        $next_id = $row['max_id'] ? $row['max_id'] + 1 : 3001;

                        $sql = "INSERT INTO pariwisata_gambar (id_gambar, id_pariwisata, nama_gambar, urutan) 
                                VALUES ('$next_id', '$id_pariwisata', '$nama_gambar_baru', '$i')";

                        if (mysqli_query($koneksi, $sql)) {
                            $gambar_berhasil++;
                            $gambar_diupdate++;
                        } else {
                            $gambar_gagal[] = "Gambar $i: " . mysqli_error($koneksi);
                        }
                    }
                } else {
                    $gambar_gagal[] = "Gambar $i: " . $upload_result['message'];
                }
            } else {
                if (isset($gambar_lama[$i])) {
                    $gambar_berhasil++;
                }
            }
        }

        if ($gambar_berhasil < 5) {
            mysqli_rollback($koneksi);
            echo json_encode([
                'success' => false,
                'message' => "Gagal: Harus ada 5 gambar. Saat ini hanya ada $gambar_berhasil gambar."
            ]);
            exit;
        }

        mysqli_commit($koneksi);

        if ($gambar_diupdate > 0) {
            echo json_encode([
                'success' => true,
                'message' => "Berhasil! $gambar_diupdate gambar telah diperbarui."
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => "Tidak ada perubahan gambar. Semua gambar tetap menggunakan gambar lama."
            ]);
        }
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Metode request tidak valid'
    ]);
}
