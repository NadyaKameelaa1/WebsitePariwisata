<?php
session_start();
require_once '../koneksi/koneksi.php';

$kategori_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : 'sejarah';


$sql_rekomendasi = "SELECT p.*, k.nama_kategori 
                   FROM pariwisata p 
                   JOIN kategori k ON p.id_kategori = k.id_kategori 
                   WHERE p.rating_pariwisata = 5.0 
                   ORDER BY p.rating_pariwisata DESC 
                   LIMIT 3";
$query_rekomendasi = mysqli_query($koneksi, $sql_rekomendasi);

$query_total = "SELECT COUNT(*) as total FROM pariwisata";
$result_total = mysqli_query($koneksi, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_destinasi = $row_total['total'];


$query_ikonik = "SELECT COUNT(*) as ikonik FROM pariwisata WHERE rating_pariwisata = 5.0";
$result_ikonik = mysqli_query($koneksi, $query_ikonik);
$row_ikonik = mysqli_fetch_assoc($result_ikonik);
$destinasi_ikonik = $row_ikonik['ikonik'];

$wisatawan_puas = 1000;

$sql2 = "SELECT p.*, k.nama_kategori 
        FROM pariwisata p 
        JOIN kategori k ON p.id_kategori = k.id_kategori 
        WHERE k.nama_kategori = '$kategori_aktif'
        ORDER BY p.rating_pariwisata DESC 
        LIMIT 12";

$query = mysqli_query($koneksi, $sql2);
$jumlah_data = mysqli_num_rows($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah | Hello Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="kategori_sejarah.css">
</head>

<body>
    <?php include "template/navbar.php"; ?>

    <section class="hero">

        <div class="search-container">
            <form action="hasil_pencarian.php" method="GET" class="search-form">
                <input type="text" name="q" placeholder="Cari Pariwisata..." class="search-input" id="searchInput">
                <button type="submit" class="search-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <div class="search-suggestions" id="searchSuggestions">
                <div class="suggestions-header">
                    <h4>Rekomendasi Untuk Anda</h4>
                </div>
                <div class="suggestions-list">
                    <?php while ($rekomendasi = mysqli_fetch_assoc($query_rekomendasi)): ?>
                        <?php
                        $id_rekomendasi = $rekomendasi['id_pariwisata'];
                        $sql_gambar_rekom = "SELECT nama_gambar FROM pariwisata_gambar WHERE id_pariwisata = '$id_rekomendasi' ORDER BY urutan ASC LIMIT 1";
                        $query_gambar_rekom = mysqli_query($koneksi, $sql_gambar_rekom);
                        $gambar_rekom = mysqli_fetch_assoc($query_gambar_rekom);

                        $gambar_url_rekom = isset($gambar_rekom['nama_gambar'])
                            ? '../admin/gambar-admin/pariwisata/' . $gambar_rekom['nama_gambar']
                            : 'gambar/default.jpg';
                        ?>

                        <div class="suggestion-item" data-name="<?= htmlspecialchars($rekomendasi['nama_pariwisata']) ?>">
                            <div class="suggestion-image">
                                <img src="<?= htmlspecialchars($gambar_url_rekom) ?>" alt="<?= htmlspecialchars($rekomendasi['nama_pariwisata']) ?>">
                            </div>
                            <div class="suggestion-info">
                                <h5 class="pariwisata-name"><?= htmlspecialchars($rekomendasi['nama_pariwisata']) ?></h5>
                                <p class="suggestion-location">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <?= htmlspecialchars($rekomendasi['lokasi_pariwisata']) ?>
                                </p>
                                <div class="suggestion-rating">
                                    <i class="fa-solid fa-star" style="color: #FFCC00;"></i>
                                    <span class="rating-number"><?= number_format($rekomendasi['rating_pariwisata'], 1) ?></span>
                                    <span class="suggestion-category"><?= htmlspecialchars($rekomendasi['nama_kategori']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

    </section>

    <br><br><br><br><br><br><br>


    <div class="populer-grid">
        <?php if ($jumlah_data > 0): ?>
            <?php while ($data = mysqli_fetch_assoc($query)): ?>

                <?php
                $id_pariwisata = $data['id_pariwisata'];
                $sql_gambar = "SELECT nama_gambar 
                     FROM pariwisata_gambar 
                     WHERE id_pariwisata = '$id_pariwisata'
                     ORDER BY urutan ASC
                     LIMIT 1";
                $query_gambar = mysqli_query($koneksi, $sql_gambar);
                $gambar = mysqli_fetch_assoc($query_gambar);

                $gambar_default = 'gambar/default/default.png';

                if ($gambar && !empty($gambar['nama_gambar'])) {
                    $path_gambar = '../admin/gambar-admin/pariwisata/';
                    $gambar_url = $path_gambar . $gambar['nama_gambar'];
                } else {
                    $gambar_url = $gambar_default;
                }
                ?>

                <div class="populer-card">
                    <div class="populer-img">
                        <img src="<?= htmlspecialchars($gambar_url) ?>"
                            alt="<?= htmlspecialchars($data['nama_pariwisata']) ?>">

                        <div class="top-bar">
                            <div class="left-info">
                                <span class="rating">
                                    <i class="fa-solid fa-star" style="color: #FFCC00;"></i>
                                    <?= number_format($data['rating_pariwisata'], 1) ?>
                                </span>
                                <span class="kategorii"><?= htmlspecialchars($data['nama_kategori']) ?></span>
                            </div>
                            <button class="favorite-btn-card" data-id="<?= $data['id_pariwisata'] ?>">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </div>
                    </div>

                    <div class="populer-content">
                        <h3><?= htmlspecialchars($data['nama_pariwisata']) ?></h3>
                        <p class="lokasi">
                            <i class="fa-solid fa-location-dot" style="color: #FFCC00;"></i>
                            <?= htmlspecialchars($data['lokasi_pariwisata']) ?>
                        </p>

                        <?php
                        $deskripsi_asli = utf8_encode(strip_tags($data['deskripsi_pariwisata']));
                        $deskripsi = explode(" ", $deskripsi_asli);
                        $deskripsi_singkat = implode(" ", array_slice($deskripsi, 0, 20));
                        if (count($deskripsi) > 20) $deskripsi_singkat .= "...";
                        ?>

                        <p class="deskripsi"><?= htmlspecialchars($deskripsi_singkat) ?></p>


                        <p class="harga">
                            IDR <?= number_format($data['harga_pariwisata'] / 1000, 0) ?>K
                            <span><?= $data['satuan_harga'] ?></span>
                        </p>


                        <button class="btn-detail" data-modal="modal<?= $data['id_pariwisata'] ?>">
                            Lihat Detail <i class="fa-solid fa-circle-arrow-right"></i>
                        </button>
                    </div>
                </div>


                <div id="modal<?= $data['id_pariwisata'] ?>" class="modal">
                    <div class="modal-content detail-modal">
                        <span class="close">&times;</span>

                        <div class="breadcrumb">
                            <a href="beranda.php">Beranda</a> &gt;
                            <a href="pariwisata.php">Pariwisata</a> &gt;
                            <span>Detail Pariwisata</span>
                        </div>

                        <div class="detail-header">
                            <h1><?= htmlspecialchars($data['nama_pariwisata']) ?></h1>
                            <p class="location">
                                <i class="fa-solid fa-location-dot"></i>
                                <?= htmlspecialchars($data['lokasi_pariwisata']) ?>
                            </p>
                        </div>

                        <div class="detail-main-content">
                            <!-- kolom kiri -->
                            <div class="detail-left">

                                <div class="gallery-section">
                                    <div class="gallery-grid-layout">
                                        <?php
                                        $id_pariwisata = $data['id_pariwisata'];
                                        $sql_gambar = "SELECT nama_gambar, urutan FROM pariwisata_gambar WHERE id_pariwisata = '$id_pariwisata' ORDER BY urutan ASC LIMIT 5";

                                        $query_gambar = mysqli_query($koneksi, $sql_gambar);
                                        $gambar_data = array();

                                        for ($i = 1; $i <= 5; $i++) {
                                            $gambar_data[$i] = '../user/gambar/default/gambar-default.png';
                                        }

                                        while ($gambar = mysqli_fetch_assoc($query_gambar)) {
                                            $urutan = $gambar['urutan'];
                                            $nama_gambar = $gambar['nama_gambar'];

                                            if ($urutan >= 1 && $urutan <= 5 && !empty($nama_gambar)) {
                                                $gambar_path = '../admin/gambar-admin/pariwisata/' . $nama_gambar;

                                                if (file_exists($gambar_path)) {
                                                    $gambar_data[$urutan] = $gambar_path;
                                                }
                                            }
                                        }

                                        $path_default = '../user/gambar/default/default.png';
                                        ?>

                                        <div class="main-large-image">
                                            <img src="<?= htmlspecialchars($gambar_data[1]) ?>"
                                                alt="Main image"
                                                id="main-image"
                                                onerror="this.src='<?= $path_default ?>'">
                                        </div>

                                        <div class="small-bottom-div">
                                            <div class="small-images-bottom-1">
                                                <div class="small-image" data-index="3">
                                                    <img src="<?= htmlspecialchars($gambar_data[4]) ?>"
                                                        alt="Image 4"
                                                        onerror="this.src='<?= $path_default ?>'">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="small-bottom-div">
                                            <div class="small-images-bottom-2">
                                                <div class="small-image" data-index="4">
                                                    <img src="<?= htmlspecialchars($gambar_data[5]) ?>"
                                                        alt="Image 5"
                                                        onerror="this.src='<?= $path_default ?>'">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="small-images-container">
                                            <div class="small-images-right">
                                                <div class="small-image" data-index="1">
                                                    <img src="<?= htmlspecialchars($gambar_data[2]) ?>"
                                                        alt="Image 2"
                                                        onerror="this.src='<?= $path_default ?>'">
                                                </div>

                                                <div class="small-image" data-index="2">
                                                    <img src="<?= htmlspecialchars($gambar_data[3]) ?>"
                                                        alt="Image 3"
                                                        onerror="this.src='<?= $path_default ?>'">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-section">
                                    <h3>Informasi Lebih Lanjut</h3>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="fa-solid fa-map-marker-alt"></i>
                                                <span>Alamat</span>
                                            </div>
                                            <div class="info-value"><?= nl2br(htmlspecialchars($data['alamat_pariwisata'])) ?></div>
                                        </div>

                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="fa-solid fa-clock"></i>
                                                <span>Jam Buka</span>
                                            </div>
                                            <div class="info-value">
                                                <?= htmlspecialchars($data['hari_operasional']) ?>
                                                <?php if ($data['jam_buka'] !== NULL && $data['jam_tutup'] !== NULL): ?>
                                                    <br>
                                                    <?= date('H:i', strtotime($data['jam_buka'])) ?> - <?= date('H:i', strtotime($data['jam_tutup'])) ?>
                                                    <?php if ($data['zona_waktu'] !== NULL): ?>
                                                        <?= $data['zona_waktu'] ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- kolom kanan -->
                            <div class="detail-right">

                                <div class="sidebar-content">
                                    <div class="rating-display">
                                        <?php

                                        $rating = $data['rating_pariwisata'];
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars) >= 0.5;

                                        if ($rating == 5.0): ?>
                                            <div class="rating-approve">
                                                <i class="fa-solid fa-award"></i>
                                                <span>Rekomendasi Pariwisata</span>
                                            </div>

                                        <?php endif; ?>
                                        <div class="stars">



                                            <?php



                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $fullStars) {
                                                    echo '<i class="fa-solid fa-star"></i>';
                                                } elseif ($halfStar && $i == $fullStars + 1) {
                                                    echo '<i class="fa-solid fa-star-half-stroke"></i>';
                                                } else {
                                                    echo '<i class="fa-regular fa-star"></i>';
                                                }
                                            }
                                            ?>
                                        </div>

                                        <span class="rating-value"><?= number_format($rating, 1) ?></span>




                                    </div>

                                    <div class="price-box">



                                        <div class="price-header">
                                            <span class="price-label">Harga</span>
                                            <div class="price-main">
                                                <span class="price-amount">IDR <?= number_format($data['harga_pariwisata'], 0, ',', '.') ?></span>
                                                <span class="price-unit"><?= $data['satuan_harga'] ?></span>
                                            </div>
                                        </div>

                                        <div class="divider"></div>


                                        <div class="description-section">
                                            <h3>Deskripsi Pariwisata</h3>
                                            <p> <?php
                                                $deskripsi = $data['deskripsi_pariwisata'];
                                                if (!empty($deskripsi)) {

                                                    $deskripsi_utf8 = utf8_encode($deskripsi);
                                                    echo nl2br(htmlspecialchars($deskripsi_utf8));
                                                } else {
                                                    echo "Deskripsi tidak tersedia.";
                                                }
                                                ?>
                                            </p>
                                        </div>

                                        <br>



                                        <div class="recommendation-card">
                                            <div class="recommendation-category">

                                                <?php
                                                $kategori = $data['nama_kategori'];
                                                $emoji = '';

                                                switch (strtolower($kategori)) {
                                                    case 'pantai':
                                                        $emoji = '🏝️';
                                                        break;
                                                    case 'gunung':
                                                        $emoji = '⛰️';
                                                        break;
                                                    case 'kuliner':
                                                        $emoji = '🍜';
                                                        break;
                                                    case 'sejarah':
                                                        $emoji = '🏛️';
                                                        break;
                                                    case 'budaya':
                                                        $emoji = '🎭';
                                                        break;
                                                    default:
                                                        $emoji = '📍';
                                                        break;
                                                }
                                                ?>
                                                <span class="category-emoji"><?= $emoji ?></span>
                                                <span class="category-name"><?= htmlspecialchars($kategori) ?></span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="recommendation-section">
                                        <div class="favorite-section">
                                            <p class="favorite-text">Suka dengan pariwisata ini?</p>
                                            <button class="favorite-btn-large" data-id="<?= $data['id_pariwisata'] ?>">
                                                <i class="fa-regular fa-heart"></i>
                                                Tambahkan Favorit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>

        <?php else: ?>
            <div class="empty-category">
                <img src="../error/error2.png" alt="error" class="empty-icon">
                <p>Belum Ada Pariwisata Kategori <span><?= ucfirst($kategori_aktif) ?></span></p>
                <a href="beranda.php" class="btn-explore">Jelajahi Kategori Lain</a>
            </div>
        <?php endif; ?>


    </div>

    </div>

    <br><br>
    <div class="container">
        <section class="why-hello-indonesia">


            <div class="stats-container">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-smile"></i>
                    </div>
                    <div class="stat-number" data-count="<?php echo $wisatawan_puas; ?>">0</div>
                    <p class="stat-label">Wisatawan Puas</p>
                </div>

                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-number" data-count="<?php echo $destinasi_ikonik; ?>">0</div>
                    <p class="stat-label">Destinasi Ikonik</p>
                </div>

                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="stat-number" data-count="<?php echo $total_destinasi; ?>">0</div>
                    <p class="stat-label">Tempat Hits</p>
                </div>
            </div>
    </div>
    </section>

    <?php include "template/footer.php"; ?>


    <script>
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const icon = btn.querySelector('i');
                icon.classList.toggle('fa-regular');
                icon.classList.toggle('fa-solid');
                icon.classList.toggle('active');
            });
        });

        // -------------------------------
        function filterKategori(kategori) {
            if (kategori === 'all') {
                window.location.href = 'pariwisata.php';
            } else {
                window.location.href = 'pariwisata.php?kategori=' + encodeURIComponent(kategori);
            }
        }

        // -----------------------

        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        document.querySelectorAll('.close').forEach(closeBtn => {
            closeBtn.addEventListener('click', function() {
                const modal = this.closest('.modal');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';

                modal.classList.add('blur-strong');
            });
        });

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // favorit heart

        document.addEventListener('DOMContentLoaded', function() {
            // Cek status favorit untuk semua card saat halaman dimuat
            checkAllFavoriteStatus();

            // Event listener untuk favorite button di card
            document.querySelectorAll('.favorite-btn-card').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    handleFavoriteCardClick(this);
                });
            });
        });

        // Fungsi untuk menangani klik favorite button di card
        function handleFavoriteCardClick(button) {
            const isLoggedIn = <?= isset($_SESSION['id_user']) ? 'true' : 'false' ?>;

            if (!isLoggedIn) {
                alert('Silakan login terlebih dahulu untuk menambahkan favorit');
                return;
            }

            const idPariwisata = button.getAttribute('data-id');
            const icon = button.querySelector('i');

            if (button.classList.contains('active')) {
                // Jika sudah favorit, tampilkan konfirmasi hapus
                if (confirm('Yakin hapus dari favorit?')) {
                    removeFromFavoriteCard(idPariwisata, button, icon);
                }
            } else {
                // Jika belum favorit, tambahkan ke favorit
                addToFavoriteCard(idPariwisata, button, icon);
            }
        }

        // Fungsi untuk menambah favorit dari card
        function addToFavoriteCard(idPariwisata, button, icon) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'tambah_favorit_card.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            setFavoriteCardActive(button, icon);
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Terjadi kesalahan saat menambahkan favorit');
                    }
                }
            };

            xhr.onerror = function() {
                alert('Terjadi kesalahan jaringan');
            };

            xhr.send('id_pariwisata=' + idPariwisata);
        }

        // Fungsi untuk menghapus favorit dari card
        function removeFromFavoriteCard(idPariwisata, button, icon) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'hapus_favorit_card.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            setFavoriteCardInactive(button, icon);
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Terjadi kesalahan saat menghapus favorit');
                    }
                }
            };

            xhr.onerror = function() {
                alert('Terjadi kesalahan jaringan');
            };

            xhr.send('id_pariwisata=' + idPariwisata);
        }

        // Fungsi untuk cek status favorit semua card
        function checkAllFavoriteStatus() {
            const isLoggedIn = <?= isset($_SESSION['id_user']) ? 'true' : 'false' ?>;
            if (!isLoggedIn) return;

            document.querySelectorAll('.favorite-btn-card').forEach(btn => {
                const idPariwisata = btn.getAttribute('data-id');
                checkFavoriteCardStatus(idPariwisata, btn);
            });
        }

        // Fungsi untuk cek status favorit per card
        function checkFavoriteCardStatus(idPariwisata, button) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_favorite_card.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        const icon = button.querySelector('i');
                        if (response.is_favorite) {
                            setFavoriteCardActive(button, icon);
                        } else {
                            setFavoriteCardInactive(button, icon);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                }
            };

            xhr.onerror = function() {
                console.error('Request failed');
            };

            xhr.send('id_pariwisata=' + idPariwisata);
        }

        // Fungsi untuk set card favorit aktif
        function setFavoriteCardActive(button, icon) {
            button.classList.add('active');
            icon.className = 'fa-solid fa-heart';
            icon.style.color = 'white';
        }

        // Fungsi untuk set card favorit tidak aktif
        function setFavoriteCardInactive(button, icon) {
            button.classList.remove('active');
            icon.className = 'fa-regular fa-heart';
            icon.style.color = 'white';
        }


        // favorit modal

        // Fungsi untuk favorit button di card list (jika ada)
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-regular');
                icon.classList.toggle('fa-solid');

                // Tambahkan logika AJAX untuk card list jika diperlukan
            });
        });

        // Fungsi untuk modal favorit
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target.closest('.favorite-btn-large')) {
                    const favoriteBtn = e.target.closest('.favorite-btn-large');
                    const favoriteIcon = favoriteBtn.querySelector('i');
                    const favoriteText = favoriteBtn.querySelector('span');
                    const idPariwisata = favoriteBtn.getAttribute('data-id');
                    const isLoggedIn = <?= isset($_SESSION['id_user']) ? 'true' : 'false' ?>;

                    if (!isLoggedIn) {
                        alert('Silakan login terlebih dahulu untuk menambahkan favorit');
                        return;
                    }

                    if (favoriteBtn.classList.contains('active')) {
                        // Hapus dari favorit
                        removeFromFavorite(idPariwisata, favoriteBtn, favoriteIcon, favoriteText);
                    } else {
                        // Tambah ke favorit
                        addToFavorite(idPariwisata, favoriteBtn, favoriteIcon, favoriteText);
                    }
                }
            });
        });

        // Cek status favorit saat modal dibuka
        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal');
                const modal = document.getElementById(modalId);

                // Beri delay sedikit untuk memastikan modal sudah terbuka
                setTimeout(() => {
                    const favoriteBtn = modal.querySelector('.favorite-btn-large');
                    if (favoriteBtn) {
                        checkFavoriteStatus(favoriteBtn);
                    }
                }, 100);
            });
        });

        function checkFavoriteStatus(favoriteBtn) {
            const isLoggedIn = <?= isset($_SESSION['id_user']) ? 'true' : 'false' ?>;
            if (!isLoggedIn) return;

            const idPariwisata = favoriteBtn.getAttribute('data-id');
            const favoriteIcon = favoriteBtn.querySelector('i');
            const favoriteText = favoriteBtn.querySelector('span');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_favorite.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.is_favorite) {
                            setFavoriteActive(favoriteBtn, favoriteIcon, favoriteText);
                        } else {
                            setFavoriteInactive(favoriteBtn, favoriteIcon, favoriteText);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                }
            };

            xhr.onerror = function() {
                console.error('Request failed');
            };

            xhr.send('id_pariwisata=' + idPariwisata);
        }

        function addToFavorite(idPariwisata, favoriteBtn, favoriteIcon, favoriteText) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'tambah_favorit.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            setFavoriteActive(favoriteBtn, favoriteIcon, favoriteText);
                            alert('Berhasil ditambahkan ke favorit!');
                        } else {
                            alert('Gagal: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Terjadi kesalahan saat menambahkan favorit');
                    }
                }
            };

            xhr.onerror = function() {
                alert('Terjadi kesalahan jaringan');
            };

            xhr.send('id_pariwisata=' + idPariwisata);
        }

        function removeFromFavorite(idPariwisata, favoriteBtn, favoriteIcon, favoriteText) {
            if (confirm('Hapus dari favorit?')) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'hapus_favorit.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                setFavoriteInactive(favoriteBtn, favoriteIcon, favoriteText);
                                alert('Berhasil dihapus dari favorit!');
                            } else {
                                alert('Gagal: ' + response.message);
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            alert('Terjadi kesalahan saat menghapus favorit');
                        }
                    }
                };

                xhr.onerror = function() {
                    alert('Terjadi kesalahan jaringan');
                };

                xhr.send('id_pariwisata=' + idPariwisata);
            }
        }

        function setFavoriteActive(favoriteBtn, favoriteIcon, favoriteText) {
            favoriteBtn.classList.add('active');
            favoriteIcon.className = 'fa-solid fa-heart';
            if (favoriteText) {
                favoriteText.textContent = 'Favorit';
            }
            favoriteBtn.style.background = '#007bff';
            favoriteBtn.style.color = 'white';
        }

        function setFavoriteInactive(favoriteBtn, favoriteIcon, favoriteText) {
            favoriteBtn.classList.remove('active');
            favoriteIcon.className = 'fa-regular fa-heart';
            if (favoriteText) {
                favoriteText.textContent = 'Tambahkan Favorit';
            }
            favoriteBtn.style.background = 'white';
            favoriteBtn.style.color = '#007bff';
        }

        document.querySelectorAll('.btn-detail').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                }
            });
        });

        document.querySelectorAll('.close').forEach(closeBtn => {
            closeBtn.addEventListener('click', function() {
                const modal = this.closest('.modal');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        });

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // --------- search

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchSuggestions = document.getElementById('searchSuggestions');
            const searchForm = document.querySelector('.search-form');
            const suggestionItems = document.querySelectorAll('.suggestion-item');

            searchInput.addEventListener('focus', function() {
                searchSuggestions.classList.add('active');
            });

            document.addEventListener('click', function(e) {
                if (!searchContainer.contains(e.target)) {
                    searchSuggestions.classList.remove('active');
                }
            });

            suggestionItems.forEach(item => {
                item.addEventListener('click', function() {
                    const destinationName = this.getAttribute('data-name');
                    searchInput.value = destinationName;
                    searchSuggestions.classList.remove('active');

                    setTimeout(() => {
                        searchForm.submit();
                    }, 300);
                });
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                if (searchTerm.length > 0) {
                    searchSuggestions.classList.add('active');

                    suggestionItems.forEach(item => {
                        const itemName = item.getAttribute('data-name').toLowerCase();
                        if (itemName.includes(searchTerm)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                } else {
                    suggestionItems.forEach(item => {
                        item.style.display = 'flex';
                    });
                }
            });
        });

        const searchContainer = document.querySelector('.search-container');
    </script>

</body>

</html>