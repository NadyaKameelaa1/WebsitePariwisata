<?php
session_start();
require_once '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username   = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email_user = mysqli_real_escape_string($koneksi, $_POST['email_user']);
    $password   = mysqli_real_escape_string($koneksi, $_POST['password']);

    $check_query = "SELECT * FROM users WHERE username = '$username' OR email_user = '$email_user' LIMIT 1";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('Username atau email sudah terdaftar!');
                window.location='register.php';
              </script>";
        exit();
    }

    $encrypted_password = md5($password);

    $insert_query = "INSERT INTO users (username, email_user, password, role, tanggal_daftar)
                     VALUES ('$username', '$email_user', '$encrypted_password', 'user', NOW())";

    if (mysqli_query($koneksi, $insert_query)) {
        echo "<script>
                alert('Registrasi berhasil! Silakan login.');
                window.location='login.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat registrasi!');
                window.location='register.php';
              </script>";
    }
}
