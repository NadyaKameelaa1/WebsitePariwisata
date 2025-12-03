<?php
require_once '../koneksi/koneksi.php';

$id_pariwisata = $_GET['id_pariwisata'];

$sql = "DELETE FROM pariwisata WHERE id_pariwisata = '$id_pariwisata'";
$query = mysqli_query($koneksi, $sql);

if ($query) {
    echo
    "<script>
        alert('Berhasil menghapus data!');
        document.location.href = 'pariwisata.php';
    </script>";
} else {
    echo
    "<script>
        alert('Gagal menghapus data!');
        document.location.href = 'pariwisata.php';
    </script>";
}
