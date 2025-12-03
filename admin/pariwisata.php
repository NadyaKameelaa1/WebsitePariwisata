<?php
require_once '../koneksi/koneksi.php';

$sql = "SELECT * FROM pariwisata";
$query = mysqli_query($koneksi, $sql);

$result = mysqli_query($koneksi, "SELECT MAX(id_pariwisata) as last_id FROM pariwisata");
$row = mysqli_fetch_assoc($result);
$last_id = $row['last_id'];
echo "<script>let lastID = $last_id;</script>";

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pariwisata.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Pariwisata | Hello Indonesia</title>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <h2><img src="../user/gambar/logo/logo.png" alt="Logo Hello Indonesia" height="80" style="margin-bottom: -20px; margin-top: -15px; margin-left: 20px;"></h2>
        </div>
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-globe"></i> Dashboard</a></li>
                <li><a href="user.php"><i class="fas fa-user"></i> User</a></li>
                <li class="active"><a href="pariwisata.php"><i class="fas fa-cubes"></i> Pariwisata</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="breadcrumb-container">
            <div class="breadcrumb-nav">
                <span>Dashboard</span>
                <span>/</span>
                <span>Pariwisata</span>
            </div>
            <a href="../login/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <div class="content-header">
            <div class="header-left">
                <h1><i class="fas fa-mountain"></i> Data Pariwisata</h1>
            </div>
            <div class="header-right">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari pariwisata...">
                </div>
                <button class="btn-tambah" id="openModal">
                    <i class="fas fa-plus"></i> Tambah Pariwisata
                </button>
            </div>
        </div>

        <div class="table-container">
            <table id="pariwisataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Alamat</th>
                        <th>Harga</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Zona</th>
                        <th>Rating</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pariwisata = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?= $pariwisata['id_pariwisata']; ?></td>
                            <td><?= $pariwisata['nama_pariwisata']; ?></td>
                            <td>
                                <?php
                                $kategori_id = $pariwisata['id_kategori'];
                                $kat_query = mysqli_query($koneksi, "SELECT nama_kategori FROM kategori WHERE id_kategori = '$kategori_id'");
                                $kategori = mysqli_fetch_assoc($kat_query);
                                echo $kategori['nama_kategori'] ?? $pariwisata['id_kategori'];
                                ?>
                            </td>
                            <td><?= $pariwisata['lokasi_pariwisata']; ?></td>
                            <td class="truncate"><?= substr($pariwisata['alamat_pariwisata'], 0, 30) . '...'; ?></td>
                            <td><?= number_format($pariwisata['harga_pariwisata'], 0, ',', '.'); ?></td>
                            <td><?= $pariwisata['hari_operasional']; ?></td>
                            <td>
                                <?php
                                $jam_buka = $pariwisata['jam_buka'] ?? null;
                                $jam_tutup = $pariwisata['jam_tutup'] ?? null;

                                $jam_buka_kosong = empty($jam_buka) || $jam_buka == '00:00:00';
                                $jam_tutup_kosong = empty($jam_tutup) || $jam_tutup == '00:00:00';

                                if ($jam_buka_kosong && $jam_tutup_kosong) {
                                    // Jika keduanya kosong, tampilkan strip
                                    echo '-';
                                } elseif (!$jam_buka_kosong && !$jam_tutup_kosong) {
                                    // Jika keduanya ada, tampilkan keduanya
                                    echo substr($jam_buka, 0, 5) . ' - ' . substr($jam_tutup, 0, 5);
                                } elseif (!$jam_buka_kosong) {
                                    // Jika hanya jam buka yang ada
                                    echo substr($jam_buka, 0, 5) . ' - -';
                                } else {
                                    // Jika hanya jam tutup yang ada
                                    echo '- - ' . substr($jam_tutup, 0, 5);
                                }
                                ?>
                            </td>
                            <td><?= (!empty($pariwisata['zona_waktu']) && $pariwisata['zona_waktu'] != 'NULL') ? $pariwisata['zona_waktu'] : '-'; ?></td>
                            <td>
                                <span class="rating">
                                    <i class="fas fa-star"></i> <?= $pariwisata['rating_pariwisata']; ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($pariwisata['tanggal_input'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-edit" onclick="openEditModal(<?= $pariwisata['id_pariwisata']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="pariwisata_hapus.php?id_pariwisata=<?= $pariwisata['id_pariwisata']; ?>" class="btn-hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <button class="btn-gambar" onclick="openEditGambarModal(<?= $pariwisata['id_pariwisata']; ?>)">
                                        <i class="fas fa-image"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah Data -->
        <div class="modal" id="tambahModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-plus-circle"></i> Tambah Pariwisata</h3>
                    <span class="close" id="closeModal">&times;</span>
                </div>

                <div class="modal-body">
                    <div class="step-indicator">
                        <span id="step1-indicator" class="step active">
                            <i class="fas fa-database"></i> 1. Data Pariwisata
                        </span>
                        <span class="step-separator">—</span>
                        <span id="step2-indicator" class="step">
                            <i class="fas fa-image"></i> 2. Upload 5 Gambar
                        </span>
                    </div>

                    <?php
                    $result = mysqli_query($koneksi, "SELECT MAX(id_pariwisata) AS last_id FROM pariwisata");
                    $row = mysqli_fetch_assoc($result);
                    $last_id = $row['last_id'] ? $row['last_id'] + 1 : 1001;

                    $kategori_query = mysqli_query($koneksi, "SELECT id_kategori, nama_kategori FROM kategori");
                    ?>

                    <form action="proses_tambah.php" method="POST" enctype="multipart/form-data" id="formTambah">
                        <div id="step1" class="step-content">
                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-hashtag"></i> ID Pariwisata</label>
                                    <input type="text" name="id_pariwisata" value="<?= $last_id ?>" readonly class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-signature"></i> Nama Pariwisata</label>
                                    <input type="text" name="nama_pariwisata" placeholder="Masukkan nama pariwisata..." required class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-tags"></i> Kategori Pariwisata</label>
                                    <select name="id_kategori" required class="form-control">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php
                                        mysqli_data_seek($kategori_query, 0); // Reset pointer
                                        while ($kategori = mysqli_fetch_assoc($kategori_query)): ?>
                                            <option value="<?= $kategori['id_kategori'] ?>"><?= $kategori['nama_kategori'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt"></i> Lokasi Pariwisata</label>
                                    <input type="text" name="lokasi_pariwisata" placeholder="Masukkan lokasi pariwisata..." required class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-map-pin"></i> Alamat Pariwisata</label>
                                <textarea name="alamat_pariwisata" placeholder="Masukkan alamat pariwisata..." required class="form-control" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-align-left"></i> Deskripsi Pariwisata</label>
                                <textarea name="deskripsi_pariwisata" placeholder="Masukkan deskripsi pariwisata..." required class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-tag"></i> Harga Pariwisata</label>
                                    <input type="number" name="harga_pariwisata" min="0" placeholder="Masukkan harga..." required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-balance-scale"></i> Satuan Harga</label>
                                    <select name="satuan_harga" required class="form-control">
                                        <option value="">-- Pilih Satuan --</option>
                                        <option value="/ orang">/ orang</option>
                                        <option value="/ porsi">/ porsi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar-day"></i> Hari Operasional</label>
                                    <select name="hari_operasional" required class="form-control">
                                        <option value="">-- Pilih Hari --</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Minggu">Minggu</option>
                                        <option value="Setiap Hari">Setiap Hari</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-clock"></i> Jam Buka</label>
                                    <input type="time" name="jam_buka" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-clock"></i> Jam Tutup</label>
                                    <input type="time" name="jam_tutup" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-globe-asia"></i> Zona Waktu</label>
                                    <select name="zona_waktu" class="form-control">
                                        <option value="">-- Pilih Zona --</option>
                                        <option value="WIB">WIB</option>
                                        <option value="WITA">WITA</option>
                                        <option value="WIT">WIT</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-star"></i> Rating Pariwisata</label>
                                    <input type="number" name="rating_pariwisata" step="0.1" min="0" max="5" placeholder="0.0 - 5.0" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div id="step2" class="step-content" style="display: none;">
                            <div class="upload-section">
                                <h4><i class="fas fa-images"></i> Upload 5 Gambar Pariwisata</h4>
                                <p class="upload-info">Setiap pariwisata membutuhkan 5 gambar dengan urutan tertentu</p>

                                <div class="gambar-container">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <div class="gambar-item">
                                            <div class="gambar-header">
                                                <span class="gambar-number">Gambar <?= $i ?></span>
                                                <?php if ($i == 1): ?>
                                                    <span class="gambar-label">(Gambar Utama)</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="upload-box-small" id="uploadBox<?= $i ?>">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <p>Klik atau drag file</p>
                                                <input type="file" name="gambar_<?= $i ?>" id="gambar_<?= $i ?>" accept="image/*" class="gambar-input" data-index="<?= $i ?>">
                                                <small>Max 5MB | JPG, PNG, JPEG</small>
                                            </div>

                                            <div id="preview-gambar-<?= $i ?>" class="image-preview-small"></div>

                                            <div class="gambar-actions">
                                                <button type="button" class="btn-clear" data-index="<?= $i ?>" style="display: none;">
                                                    <i class="fas fa-times"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>

                                <div class="upload-requirements">
                                    <p><i class="fas fa-info-circle"></i> <strong>Persyaratan:</strong></p>
                                    <ul style="text-align: left;">
                                        <li>Semua 5 gambar harus diisi</li>
                                        <li>Gambar 1 akan menjadi gambar utama/thumbnail</li>
                                        <li>Ukuran maksimal per file: 5MB</li>
                                        <li>Format yang didukung: JPG, PNG, JPEG</li>
                                        <li>Rasio gambar disarankan: 16:9 atau 4:3</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="nextBtn" class="btn-next">
                                <i class="fas fa-arrow-right"></i> Lanjut ke Upload Gambar
                            </button>
                            <button type="button" id="prevBtn" class="btn-prev" style="display: none;">
                                <i class="fas fa-arrow-left"></i> Kembali ke Data
                            </button>
                            <button type="submit" id="saveBtn" class="btn-submit" style="display: none;">
                                <i class="fas fa-save"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Data -->
        <div class="modal" id="editModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-edit"></i> Edit Pariwisata</h3>
                    <span class="close" id="closeEditModal">&times;</span>
                </div>

                <div class="modal-body">
                    <div class="step-indicator">
                        <span class="step active">
                            <i class="fas fa-database"></i> Edit Data Pariwisata
                        </span>
                    </div>

                    <form action="proses_edit.php" method="POST" id="formEdit">
                        <div class="step-content">
                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-hashtag"></i> ID Pariwisata</label>
                                    <input type="text" name="id_pariwisata" id="edit_id_pariwisata" readonly class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-signature"></i> Nama Pariwisata</label>
                                    <input type="text" name="nama_pariwisata" id="edit_nama_pariwisata" placeholder="Masukkan nama pariwisata..." required class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-tags"></i> Kategori Pariwisata</label>
                                    <select name="id_kategori" id="edit_id_kategori" required class="form-control">
                                        <option value="">-- Pilih Kategori --</option>
                                        <?php
                                        mysqli_data_seek($kategori_query, 0); // Reset pointer
                                        while ($kategori = mysqli_fetch_assoc($kategori_query)): ?>
                                            <option value="<?= $kategori['id_kategori'] ?>"><?= $kategori['nama_kategori'] ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt"></i> Lokasi Pariwisata</label>
                                    <input type="text" name="lokasi_pariwisata" id="edit_lokasi_pariwisata" placeholder="Masukkan lokasi pariwisata..." required class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-map-pin"></i> Alamat Pariwisata</label>
                                <textarea name="alamat_pariwisata" id="edit_alamat_pariwisata" placeholder="Masukkan alamat pariwisata..." required class="form-control" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-align-left"></i> Deskripsi Pariwisata</label>
                                <textarea name="deskripsi_pariwisata" id="edit_deskripsi_pariwisata" placeholder="Masukkan deskripsi pariwisata..." required class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-tag"></i> Harga Pariwisata</label>
                                    <input type="number" name="harga_pariwisata" id="edit_harga_pariwisata" min="0" placeholder="Masukkan harga..." required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-balance-scale"></i> Satuan Harga</label>
                                    <select name="satuan_harga" id="edit_satuan_harga" required class="form-control">
                                        <option value="">-- Pilih Satuan --</option>
                                        <option value="/ orang">/ orang</option>
                                        <option value="/ porsi">/ porsi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar-day"></i> Hari Operasional</label>
                                    <select name="hari_operasional" id="edit_hari_operasional" required class="form-control">
                                        <option value="">-- Pilih Hari --</option>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Sabtu">Sabtu</option>
                                        <option value="Minggu">Minggu</option>
                                        <option value="Setiap Hari">Setiap Hari</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-clock"></i> Jam Buka</label>
                                    <input type="time" name="jam_buka" id="edit_jam_buka" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-clock"></i> Jam Tutup</label>
                                    <input type="time" name="jam_tutup" id="edit_jam_tutup" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-globe-asia"></i> Zona Waktu</label>
                                    <select name="zona_waktu" id="edit_zona_waktu" class="form-control">
                                        <option value="">-- Pilih Zona --</option>
                                        <option value="WIB">WIB</option>
                                        <option value="WITA">WITA</option>
                                        <option value="WIT">WIT</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-star"></i> Rating Pariwisata</label>
                                    <input type="number" name="rating_pariwisata" id="edit_rating_pariwisata" step="0.1" min="0" max="5" placeholder="0.0 - 5.0" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <button type="button" id="cancelEdit" class="btn-prev">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Gambar -->
        <div class="modal" id="editGambarModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-images"></i> Edit Gambar Pariwisata</h3>
                    <span class="close" id="closeGambarModal">&times;</span>
                </div>

                <div class="modal-body">
                    <div class="step-indicator">
                        <span class="step active">
                            <i class="fas fa-image"></i> Edit 5 Gambar Pariwisata
                        </span>
                    </div>

                    <form action="proses_edit_gambar.php" method="POST" enctype="multipart/form-data" id="formEditGambar">
                        <input type="hidden" name="id_pariwisata" id="edit_gambar_id_pariwisata">

                        <div class="upload-section">
                            <h4><i class="fas fa-images"></i> Edit 5 Gambar Pariwisata</h4>
                            <p class="upload-info">Setiap pariwisata membutuhkan 5 gambar dengan urutan tertentu</p>

                            <div class="gambar-container" id="gambarContainer">
                                <!-- diisi js -->
                            </div>

                            <div class="upload-requirements">
                                <p><i class="fas fa-info-circle"></i> <strong>Persyaratan:</strong></p>
                                <ul style="text-align: left;">
                                    <li>Gambar yang diupload akan menggantikan gambar lama</li>
                                    <li>Gambar 1 akan menjadi gambar utama/thumbnail</li>
                                    <li>Ukuran maksimal per file: 5MB</li>
                                    <li>Format yang didukung: JPG, PNG, JPEG</li>
                                    <li>Jika tidak upload gambar baru, gambar lama akan tetap digunakan</li>
                                </ul>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <button type="button" id="cancelEditGambar" class="btn-prev">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById("tambahModal");
        const openBtn = document.getElementById("openModal");
        const closeBtn = document.getElementById("closeModal");

        openBtn.onclick = () => modal.style.display = "flex";
        closeBtn.onclick = () => modal.style.display = "none";
        window.onclick = (e) => {
            if (e.target == modal) modal.style.display = "none";
        };

        // Step navigation
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const saveBtn = document.getElementById('saveBtn');
        const step1Indicator = document.getElementById('step1-indicator');
        const step2Indicator = document.getElementById('step2-indicator');
        const formTambah = document.getElementById('formTambah');

        // Validasi sebelum ke step 2
        nextBtn.addEventListener('click', function() {
            // Validasi form step 1
            let isValid = true;
            const requiredFields = formTambah.querySelectorAll('#step1 [required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('input-error');
                    isValid = false;
                } else {
                    field.classList.remove('input-error');
                }
            });

            if (!isValid) {
                alert('Harap isi semua field yang diperlukan di Data Pariwisata');
                return;
            }

            // Lanjut ke step 2
            step1.style.display = 'none';
            step2.style.display = 'block';
            nextBtn.style.display = 'none';
            prevBtn.style.display = 'inline-block';
            saveBtn.style.display = 'inline-block';

            step1Indicator.classList.remove('active');
            step2Indicator.classList.add('active');
        });

        prevBtn.addEventListener('click', function() {
            step1.style.display = 'block';
            step2.style.display = 'none';
            nextBtn.style.display = 'inline-block';
            prevBtn.style.display = 'none';
            saveBtn.style.display = 'none';

            step1Indicator.classList.add('active');
            step2Indicator.classList.remove('active');
        });

        // Handle upload untuk 5 gambar
        for (let i = 1; i <= 5; i++) {
            const fileInput = document.getElementById('gambar_' + i);
            const uploadBox = document.getElementById('uploadBox' + i);
            const previewDiv = document.getElementById('preview-gambar-' + i);
            const clearBtn = document.querySelector('.btn-clear[data-index="' + i + '"]');

            // Click pada upload box
            uploadBox.addEventListener('click', () => fileInput.click());

            // Drag and drop
            uploadBox.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadBox.style.borderColor = '#007bff';
                uploadBox.style.backgroundColor = '#f8f9fa';
            });

            uploadBox.addEventListener('dragleave', () => {
                uploadBox.style.borderColor = '#b8daff';
                uploadBox.style.backgroundColor = '#e7f3ff';
            });

            uploadBox.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadBox.style.borderColor = '#b8daff';
                uploadBox.style.backgroundColor = '#e7f3ff';

                if (e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });

            // File change event
            fileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = e => {
                        previewDiv.innerHTML = '';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'preview-image-small';

                        previewDiv.appendChild(img);

                        if (clearBtn) {
                            clearBtn.style.display = 'inline-flex';
                        }

                        uploadBox.style.padding = '10px';
                        uploadBox.querySelector('i').style.display = 'none';
                        uploadBox.querySelector('p').style.display = 'none';
                        uploadBox.querySelector('small').style.display = 'none';
                    };

                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Clear button
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    fileInput.value = '';
                    previewDiv.innerHTML = '';
                    this.style.display = 'none';

                    // Reset upload box
                    uploadBox.style.padding = '20px 15px';
                    uploadBox.querySelector('i').style.display = 'block';
                    uploadBox.querySelector('p').style.display = 'block';
                    uploadBox.querySelector('small').style.display = 'block';
                });
            }
        }

        // Validasi sebelum submit
        formTambah.addEventListener('submit', function(e) {
            let gambarValid = true;

            // Cek apakah semua 5 gambar sudah diisi
            for (let i = 1; i <= 5; i++) {
                const fileInput = document.getElementById('gambar_' + i);
                if (!fileInput.files || fileInput.files.length === 0) {
                    gambarValid = false;
                    // tandai upload box yang belum diisi
                    const uploadBox = document.getElementById('uploadBox' + i);
                    uploadBox.style.borderColor = '#dc3545';
                    uploadBox.style.backgroundColor = '#f8d7da';
                }
            }

            if (!gambarValid) {
                e.preventDefault();
                alert('Harap upload semua 5 gambar yang diperlukan!');
                return false;
            }

            let sizeValid = true;
            let sizeMessage = '';

            for (let i = 1; i <= 5; i++) {
                const fileInput = document.getElementById('gambar_' + i);
                if (fileInput.files && fileInput.files[0]) {
                    const fileSize = fileInput.files[0].size / 1024 / 1024;
                    if (fileSize > 5) {
                        sizeValid = false;
                        sizeMessage += `Gambar ${i} melebihi 5MB\n`;

                        const uploadBox = document.getElementById('uploadBox' + i);
                        uploadBox.style.borderColor = '#dc3545';
                        uploadBox.style.backgroundColor = '#f8d7da';
                    }
                }
            }

            if (!sizeValid) {
                e.preventDefault();
                alert('Ukuran file terlalu besar:\n' + sizeMessage + '\nMaksimal 5MB per file');
                return false;
            }

            // Tampilkan loading
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            saveBtn.disabled = true;

            return true;
        });

        // Modal Edit
        const editModal = document.getElementById("editModal");
        const closeEditBtn = document.getElementById("closeEditModal");
        const cancelEditBtn = document.getElementById("cancelEdit");

        function openEditModal(id) {
            console.log("Opening edit modal for ID:", id);

            editModal.style.display = "flex";

            const formEdit = document.getElementById('formEdit');
            const stepContent = document.querySelector('#editModal .step-content');

            if (formEdit && stepContent) {

                const originalContent = stepContent.innerHTML;

                stepContent.innerHTML = '<div style="text-align:center; padding:40px;"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Memuat data...</p></div>';

                const url = 'get_pariwisata.php?id=' + id;
                console.log("Fetch URL:", url);

                fetch(url)
                    .then(response => {
                        console.log("Response status:", response.status);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Received data:", data);

                        if (data.success) {
                            stepContent.innerHTML = originalContent;

                            document.getElementById('edit_id_pariwisata').value = data.id_pariwisata || '';
                            document.getElementById('edit_nama_pariwisata').value = data.nama_pariwisata || '';
                            document.getElementById('edit_id_kategori').value = data.id_kategori || '';
                            document.getElementById('edit_lokasi_pariwisata').value = data.lokasi_pariwisata || '';
                            document.getElementById('edit_alamat_pariwisata').value = data.alamat_pariwisata || '';
                            document.getElementById('edit_deskripsi_pariwisata').value = data.deskripsi_pariwisata || '';
                            document.getElementById('edit_harga_pariwisata').value = data.harga_pariwisata || '';
                            document.getElementById('edit_satuan_harga').value = data.satuan_harga || '';
                            document.getElementById('edit_hari_operasional').value = data.hari_operasional || '';
                            document.getElementById('edit_jam_buka').value = data.jam_buka || '';
                            document.getElementById('edit_jam_tutup').value = data.jam_tutup || '';
                            document.getElementById('edit_zona_waktu').value = data.zona_waktu || '';
                            document.getElementById('edit_rating_pariwisata').value = data.rating_pariwisata || '';
                        } else {
                            alert('Gagal mengambil data: ' + data.message);
                            editModal.style.display = "none";
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengambil data: ' + error.message);
                        editModal.style.display = "none";
                    });
            }
        }

        closeEditBtn.onclick = () => editModal.style.display = "none";
        cancelEditBtn.onclick = () => editModal.style.display = "none";
        window.onclick = (e) => {
            if (e.target == editModal) editModal.style.display = "none";
        };

        // Handle submit form edit
        document.getElementById('formEdit').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('proses_edit.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        editModal.style.display = "none";
                        location.reload();
                    } else {
                        alert('Gagal menyimpan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data');
                });
        });

        // Modal Edit Gambar
        const editGambarModal = document.getElementById("editGambarModal");
        const closeGambarBtn = document.getElementById("closeGambarModal");
        const cancelGambarBtn = document.getElementById("cancelEditGambar");

        // Fungsi untuk membuka modal edit gambar
        function openEditGambarModal(id) {
            console.log("Opening edit gambar modal for ID:", id);

            document.getElementById('edit_gambar_id_pariwisata').value = id;

            editGambarModal.style.display = "flex";

            const gambarContainer = document.getElementById('gambarContainer');
            gambarContainer.innerHTML = '<div style="text-align:center; padding:40px;"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Memuat gambar...</p></div>';

            fetch('get_gambar.php?id=' + id)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Gambar data received:", data);

                    if (data.success) {
                        renderGambarContainer(data.gambar, id);
                    } else {
                        alert('Gagal mengambil data gambar: ' + data.message);
                        editGambarModal.style.display = "none";
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data gambar');
                    editGambarModal.style.display = "none";
                });
        }

        function renderGambarContainer(gambarData, idPariwisata) {
            const container = document.getElementById('gambarContainer');
            let html = '';

            for (let i = 1; i <= 5; i++) {
                const gambar = gambarData.find(g => g.urutan == i);
                const gambarPath = gambar ? '../admin/gambar-admin/pariwisata/' + gambar.nama_gambar : '';

                html += `
        <div class="gambar-item">
            <div class="gambar-header">
                <span class="gambar-number">Gambar ${i}</span>
                ${i == 1 ? '<span class="gambar-label">(Gambar Utama)</span>' : ''}
            </div>

            <div class="upload-box-small" id="uploadBoxGambar${i}" data-index="${i}">
                ${gambar ? 
                    `<img src="${gambarPath}" class="preview-image-small" style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px; margin-bottom: 10px;">
                     <p>${gambar.nama_gambar}</p>` : 
                    `<i class="fas fa-cloud-upload-alt"></i>
                     <p>Klik atau drag file</p>`
                }
                <small>Max 5MB | JPG, PNG, JPEG</small>
            </div>

            <input type="file" name="gambar_${i}" id="gambar_edit_${i}" accept="image/*" class="gambar-input" data-index="${i}" style="display: none;">

            <div id="preview-gambar-edit-${i}" class="image-preview-small">
                ${gambar ? `<img src="${gambarPath}" class="preview-image-small">` : ''}
            </div>

            <div class="gambar-actions">
                <button type="button" class="btn-clear-gambar" data-index="${i}" ${!gambar ? 'style="display: none;"' : ''}>
                    <i class="fas fa-times"></i> Hapus
                </button>
            </div>
        </div>
    `;
            }

            container.innerHTML = html;

            // Setup event listeners setelah render
            setupAllGambarEventListeners();
        }

        function setupAllGambarEventListeners() {
            for (let i = 1; i <= 5; i++) {
                const fileInput = document.getElementById('gambar_edit_' + i);
                const uploadBox = document.getElementById('uploadBoxGambar' + i);
                const previewDiv = document.getElementById('preview-gambar-edit-' + i);
                const clearBtn = document.querySelector('.btn-clear-gambar[data-index="' + i + '"]');

                if (!fileInput || !uploadBox) {
                    console.error('Element not found for index:', i);
                    continue;
                }

                // Remove existing listeners (jika ada)
                const newUploadBox = uploadBox.cloneNode(true);
                uploadBox.parentNode.replaceChild(newUploadBox, uploadBox);
                const finalUploadBox = document.getElementById('uploadBoxGambar' + i);

                // Click pada upload box
                finalUploadBox.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Upload box clicked for gambar', i);
                    fileInput.click();
                });

                // Drag and drop
                finalUploadBox.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    finalUploadBox.style.borderColor = '#007bff';
                    finalUploadBox.style.backgroundColor = '#f8f9fa';
                });

                finalUploadBox.addEventListener('dragleave', () => {
                    finalUploadBox.style.borderColor = '#b8daff';
                    finalUploadBox.style.backgroundColor = '#e7f3ff';
                });

                finalUploadBox.addEventListener('drop', (e) => {
                    e.preventDefault();
                    finalUploadBox.style.borderColor = '#b8daff';
                    finalUploadBox.style.backgroundColor = '#e7f3ff';

                    if (e.dataTransfer.files.length > 0) {
                        fileInput.files = e.dataTransfer.files;
                        handleFileChange(fileInput, i);
                    }
                });

                // File change event - PENTING: Gunakan fungsi terpisah
                fileInput.addEventListener('change', function() {
                    console.log('File changed for gambar', i, this.files);
                    handleFileChange(this, i);
                });

                // Clear button
                if (clearBtn) {
                    clearBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const index = this.getAttribute('data-index');
                        console.log('Clear button clicked for index:', index);

                        const fileInput = document.getElementById('gambar_edit_' + index);
                        const uploadBox = document.getElementById('uploadBoxGambar' + index);
                        const previewDiv = document.getElementById('preview-gambar-edit-' + index);

                        // Reset file input
                        fileInput.value = '';

                        // Reset preview
                        previewDiv.innerHTML = '';

                        // Reset upload box
                        uploadBox.innerHTML = `
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Klik atau drag file</p>
                    <small>Max 5MB | JPG, PNG, JPEG</small>
                `;


                        this.style.display = 'none';
                        setupAllGambarEventListeners();
                    });
                }
            }
        }

        // Fungsi terpisah untuk handle file change
        function handleFileChange(fileInput, index) {
            if (fileInput.files && fileInput.files[0]) {
                console.log('Processing file for index:', index);
                const file = fileInput.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const uploadBox = document.getElementById('uploadBoxGambar' + index);
                    const previewDiv = document.getElementById('preview-gambar-edit-' + index);
                    const clearBtn = document.querySelector('.btn-clear-gambar[data-index="' + index + '"]');

                    previewDiv.innerHTML = '<img src="' + e.target.result + '" class="preview-image-small">';

                    // Update upload box tampilan
                    uploadBox.innerHTML = `
                <img src="${e.target.result}" class="preview-image-small" style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px; margin-bottom: 10px;">
                <p>${file.name}</p>
                <small>Max 5MB | JPG, PNG, JPEG</small>
            `;

                    if (clearBtn) {
                        clearBtn.style.display = 'inline-flex';
                    }

                    const newUploadBox = document.getElementById('uploadBoxGambar' + index);
                    newUploadBox.addEventListener('click', function(e) {
                        e.preventDefault();
                        fileInput.click();
                    });

                    console.log('File preview updated for index:', index);
                };

                reader.readAsDataURL(file);
            }
        }

        // Tutup modal edit gambar
        closeGambarBtn.onclick = () => editGambarModal.style.display = "none";
        cancelGambarBtn.onclick = () => editGambarModal.style.display = "none";

        // Tutup modal edit gambar saat klik di luar
        window.addEventListener('click', (e) => {
            if (e.target == editGambarModal) {
                editGambarModal.style.display = "none";
            }
        });

        // Handle submit form edit gambar
        document.getElementById('formEditGambar').addEventListener('submit', function(e) {
            e.preventDefault();

            console.log("Form edit gambar submitted");

            // Tampilkan loading
            const submitBtn = this.querySelector('.btn-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;

            const formData = new FormData(this);

            // Debug: Log FormData
            console.log("FormData entries:");
            let hasNewFiles = false;
            for (let [key, value] of formData.entries()) {
                if (key.startsWith('gambar_') && value.size > 0) {
                    hasNewFiles = true;
                    console.log(key, ':', value.name, '- Size:', value.size, 'bytes');
                } else {
                    console.log(key, ':', value);
                }
            }

            console.log('Has new files:', hasNewFiles);

            // Kirim data
            fetch('proses_edit_gambar.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log("Response status:", response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Response data:", data);

                    if (data.success) {
                        alert(data.message);
                        editGambarModal.style.display = "none";
                        location.reload();
                    } else {
                        alert('Gagal menyimpan: ' + data.message);
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data: ' + error.message);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });

        // Ubah tombol gambar di tabel untuk membuka modal
        document.addEventListener('DOMContentLoaded', function() {
            // Ganti link dengan button yang membuka modal
            const gambarButtons = document.querySelectorAll('.btn-gambar');

            gambarButtons.forEach(button => {
                // Ubah dari <a> ke <button> atau tetap <a> dengan event listener
                if (button.tagName === 'A') {
                    const originalHref = button.getAttribute('href');
                    const idMatch = originalHref.match(/id_pariwisata=(\d+)/);

                    if (idMatch && idMatch[1]) {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            openEditGambarModal(idMatch[1]);
                        });

                        button.removeAttribute('href');
                    }
                }
            });
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#pariwisataTable tbody tr');

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