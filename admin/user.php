<?php
require_once '../koneksi/koneksi.php';

$sql = "SELECT * FROM users";
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pariwisata.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>User | Hello Indonesia</title>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <h2><img src="../user/gambar/logo/logo.png" alt="Logo Hello Indonesia" height="80" style="margin-bottom: -20px; margin-top: -15px; margin-left: 20px;"></h2>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-globe"></i> Dashboard</a></li>
                <li class="active"><a href="user.php"><i class="fas fa-user"></i> User</a></li>
                <li><a href="pariwisata.php"><i class="fas fa-cubes"></i> Pariwisata</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="breadcrumb-container">
            <div class="breadcrumb-nav">
                <span>Dashboard</span>
                <span>/</span>
                <span>User</span>
            </div>
            <a href="../login/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <div class="content-header">
            <div class="header-left">
                <h1><i class="fas fa-users"></i> Data User</h1>
            </div>
            <div class="header-right">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari user...">
                </div>
            </div>
        </div>

        <div class="table-container">
            <table id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?= $user['id_user'] ?? '-' ?></td>
                            <td><?= $user['username'] ?? '-' ?></td>
                            <td><?= $user['email_user'] ?? '-' ?></td>
                            <td><?= $user['role'] ?? '-' ?></td>
                            <td><?= isset($user['tanggal_daftar']) ? date('d/m/Y', strtotime($user['tanggal_daftar'])) : '-' ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#userTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>