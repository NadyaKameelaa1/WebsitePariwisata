<?php
require_once '../koneksi/koneksi.php';

$sql_pariwisata = "SELECT COUNT(*) as total FROM pariwisata";
$query_pariwisata = mysqli_query($koneksi, $sql_pariwisata);
$total_pariwisata = mysqli_fetch_assoc($query_pariwisata)['total'];

$sql_bintang5 = "SELECT COUNT(*) as total FROM pariwisata WHERE rating_pariwisata = 5.0";
$query_bintang5 = mysqli_query($koneksi, $sql_bintang5);
$total_bintang5 = mysqli_fetch_assoc($query_bintang5)['total'];

$sql_favorit = "SELECT COUNT(*) as total FROM favorit";
$query_favorit = mysqli_query($koneksi, $sql_favorit);
$total_favorit = mysqli_fetch_assoc($query_favorit)['total'];

$today = date('Y-m-d');
$sql_user_harian = "SELECT COUNT(*) as total FROM users WHERE DATE(tanggal_daftar) = '$today'";
$query_user_harian = mysqli_query($koneksi, $sql_user_harian);
$total_user_harian = mysqli_fetch_assoc($query_user_harian)['total'];

$chart_data = [];
for ($month = 1; $month <= 12; $month++) {
    $sql_chart = "SELECT COUNT(*) as total FROM favorit 
                  WHERE YEAR(tanggal_favorit) = 2025 
                  AND MONTH(tanggal_favorit) = $month";
    $query_chart = mysqli_query($koneksi, $sql_chart);
    $result = mysqli_fetch_assoc($query_chart);
    $chart_data[] = $result['total'];
}

$bulan_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

$sql_kategori_favorit = "SELECT k.nama_kategori, COUNT(f.id_favorit) as total 
                         FROM favorit f 
                         JOIN pariwisata p ON f.id_pariwisata = p.id_pariwisata 
                         JOIN kategori k ON p.id_kategori = k.id_kategori 
                         GROUP BY k.nama_kategori 
                         ORDER BY total DESC";
$query_kategori_favorit = mysqli_query($koneksi, $sql_kategori_favorit);
$kategori_favorit_data = [];
$kategori_favorit_labels = [];
$kategori_favorit_values = [];

while ($row = mysqli_fetch_assoc($query_kategori_favorit)) {
    $kategori_favorit_labels[] = $row['nama_kategori'];
    $kategori_favorit_values[] = $row['total'];
    $kategori_favorit_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pariwisata.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard | Hello Indonesia</title>
    <style>
        .welcome-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.2);
        }

        .welcome-section h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .welcome-section p {
            font-size: 14px;
            opacity: 0.9;
            max-width: 600px;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid #2a5298;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .card:nth-child(1) .card-icon {
            background: linear-gradient(135deg, #28abe4 0%, #2a5298 100%);
            color: white;
        }

        .card:nth-child(2) .card-icon {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: white;
        }

        .card:nth-child(3) .card-icon {
            background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%);
            color: white;
        }

        .card:nth-child(4) .card-icon {
            background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
            color: white;
        }

        .card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .card .number {
            font-size: 28px;
            font-weight: 700;
            color: #2a5298;
            margin-bottom: 10px;
        }

        .card .subtext {
            font-size: 12px;
            color: #888;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .card .subtext i {
            color: #28a745;
            font-size: 10px;
        }

        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 1024px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }

        .chart-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .chart-header h3 {
            font-family: 'Montserrat', sans-serif;
            color: #2a5298;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-header h3 i {
            color: #4dabf7;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .recent-activity {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .activity-list {
            margin-top: 15px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e7f3ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #2a5298;
        }

        .activity-details h4 {
            font-size: 14px;
            color: #333;
            margin-bottom: 3px;
        }

        .activity-details p {
            font-size: 12px;
            color: #666;
        }

        .activity-time {
            font-size: 11px;
            color: #999;
            margin-left: auto;
        }

        .stat-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }

        .stat-item .number {
            font-size: 32px;
            font-weight: 700;
            color: #2a5298;
            margin-bottom: 5px;
        }

        .stat-item p {
            font-size: 13px;
            color: #666;
        }

        .breadcrumb-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffffff;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            color: #666;
            font-weight: 500;
        }

        .logout-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 8px rgba(220, 53, 69, 0.2);
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(220, 53, 69, 0.3);
            color: white;
            text-decoration: none;
        }

        .logout-btn i {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <h2><img src="../user/gambar/logo/logo.png" alt="Logo Hello Indonesia" height="80" style="margin-bottom: -20px; margin-top: -15px; margin-left: 20px;"></h2>
        </div>
        <nav>
            <ul>
                <li class="active"><a href="dashboard.php"><i class="fas fa-globe"></i> Dashboard</a></li>
                <li><a href="user.php"><i class="fas fa-user"></i> User</a></li>
                <li><a href="pariwisata.php"><i class="fas fa-cubes"></i> Pariwisata</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="breadcrumb-container">
            <div class="breadcrumb-nav">
                <span>Dashboard</span>
            </div>
            <a href="../login/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <div class="welcome-section">
            <h2>Selamat Datang di Dashboard!</h2>
            <p>Di sini Anda dapat melihat statistik dan aktivitas terkini dari website Hello Indonesia. Pantau perkembangan pariwisata dan interaksi pengguna dengan mudah.</p>
        </div>

        <!-- 4 Cards -->
        <div class="cards-container">
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-mountain"></i>
                </div>
                <h3>Total Destinasi</h3>
                <div class="number"><?= number_format($total_pariwisata) ?></div>
                <div class="subtext">
                    <i class="fas fa-chart-line"></i>
                    Semua destinasi pariwisata
                </div>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Destinasi Bintang 5</h3>
                <div class="number"><?= number_format($total_bintang5) ?></div>
                <div class="subtext">
                    <i class="fas fa-award"></i>
                    Rating sempurna
                </div>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Total Favorit</h3>
                <div class="number"><?= number_format($total_favorit) ?></div>
                <div class="subtext">
                    <i class="fas fa-users"></i>
                    Interaksi pengguna
                </div>
            </div>

            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Pengguna Baru Hari Ini</h3>
                <div class="number"><?= number_format($total_user_harian) ?></div>
                <div class="subtext">
                    <i class="fas fa-calendar-day"></i>
                    Tanggal <?= date('d/m/Y') ?>
                </div>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-box">
                <div class="chart-header">
                    <h3><i class="fas fa-chart-bar"></i> Statistik Favorit Bulanan (2025)</h3>
                </div>
                <div class="chart-container">
                    <canvas id="favoritChart"></canvas>
                </div>
            </div>

            <div class="chart-box">
                <div class="chart-header">
                    <h3><i class="fas fa-chart-pie"></i> Favorit Berdasarkan Kategori</h3>
                </div>
                <div class="chart-container">
                    <canvas id="kategoriChart"></canvas>
                </div>
            </div>
        </div>

        <div class="recent-activity">
            <div class="chart-header">
                <h3><i class="fas fa-history"></i> Aktivitas Terbaru</h3>
            </div>
            <div class="activity-list">
                <?php
                $sql_activity = "SELECT f.*, u.username, p.nama_pariwisata 
                                 FROM favorit f 
                                 JOIN users u ON f.id_user = u.id_user 
                                 JOIN pariwisata p ON f.id_pariwisata = p.id_pariwisata 
                                 ORDER BY f.tanggal_favorit DESC 
                                 LIMIT 5";
                $query_activity = mysqli_query($koneksi, $sql_activity);

                if (mysqli_num_rows($query_activity) > 0):
                    while ($activity = mysqli_fetch_assoc($query_activity)):
                ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="activity-details">
                                <h4><?= htmlspecialchars($activity['username']) ?> menambahkan favorit</h4>
                                <p><?= htmlspecialchars($activity['nama_pariwisata']) ?></p>
                            </div>
                            <div class="activity-time">
                                <?= date('H:i', strtotime($activity['tanggal_favorit'])) ?>
                            </div>
                        </div>
                    <?php
                    endwhile;
                else:
                    ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="activity-details">
                            <h4>Tidak ada aktivitas terbaru</h4>
                            <p>Belum ada pengguna yang menambahkan favorit</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        const bulanLabels = <?= json_encode($bulan_labels) ?>;
        const favoritData = <?= json_encode($chart_data) ?>;
        const kategoriLabels = <?= json_encode($kategori_favorit_labels) ?>;
        const kategoriValues = <?= json_encode($kategori_favorit_values) ?>;

        const chartColors = {
            primary: 'rgba(42, 82, 152, 0.8)',
            primaryLight: 'rgba(42, 82, 152, 0.2)',
            secondary: 'rgba(40, 171, 228, 0.8)',
            secondaryLight: 'rgba(40, 171, 228, 0.2)',
            accent: 'rgba(255, 107, 107, 0.8)',
            accentLight: 'rgba(255, 107, 107, 0.2)',
            success: 'rgba(40, 167, 69, 0.8)',
            successLight: 'rgba(40, 167, 69, 0.2)'
        };

        const favoritCtx = document.getElementById('favoritChart').getContext('2d');
        const favoritChart = new Chart(favoritCtx, {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets: [{
                    label: 'Jumlah Favorit',
                    data: favoritData,
                    backgroundColor: chartColors.primary,
                    borderColor: chartColors.primary,
                    borderWidth: 1,
                    borderRadius: 5,
                    hoverBackgroundColor: chartColors.secondary
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
        const kategoriChart = new Chart(kategoriCtx, {
            type: 'doughnut',
            data: {
                labels: kategoriLabels,
                datasets: [{
                    data: kategoriValues,
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.secondary,
                        chartColors.accent,
                        chartColors.success,
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ],
                    borderColor: [
                        chartColors.primary,
                        chartColors.secondary,
                        chartColors.accent,
                        chartColors.success,
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = Math.round((value / total) * 100);
                                label += value + ' (' + percentage + '%)';
                                return label;
                            }
                        }
                    }
                }
            }
        });

        function updateTime() {
            const now = new Date();
            const timeElement = document.querySelector('.welcome-section p');
            if (timeElement) {
                const hours = now.getHours();
                const greeting = hours < 12 ? 'Pagi' :
                    hours < 15 ? 'Siang' :
                    hours < 18 ? 'Sore' : 'Malam';
                const timeString = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const baseText = "Selamat datang kembali! Di sini Anda dapat melihat statistik dan aktivitas terkini dari website Hello Indonesia. Pantau perkembangan pariwisata dan interaksi pengguna dengan mudah.";
                timeElement.textContent = `${baseText} Selamat ${greeting}! (${timeString})`;
            }
        }

        updateTime();
        setInterval(updateTime, 60000);
    </script>
</body>

</html>