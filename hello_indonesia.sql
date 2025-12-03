-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 11:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hello_indonesia`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorit`
--

CREATE TABLE `favorit` (
  `id_favorit` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_pariwisata` int(11) NOT NULL,
  `tanggal_favorit` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `favorit`
--

INSERT INTO `favorit` (`id_favorit`, `id_user`, `id_pariwisata`, `tanggal_favorit`) VALUES
(20, 1010, 2001, '2025-11-17 15:03:55'),
(21, 1010, 2002, '2025-11-17 15:04:03'),
(24, 1005, 2001, '2025-11-19 11:27:45'),
(28, 1011, 2001, '2025-11-19 12:02:02'),
(29, 1005, 2002, '2025-11-29 17:54:51'),
(32, 1012, 2002, '2025-12-02 14:43:04'),
(33, 1012, 2001, '2025-12-02 20:43:18'),
(36, 1012, 2003, '2025-12-02 21:22:18');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(5001, 'Pantai'),
(5002, 'Gunung'),
(5003, 'Kuliner'),
(5004, 'Sejarah'),
(5005, 'Budaya');

-- --------------------------------------------------------

--
-- Table structure for table `pariwisata`
--

CREATE TABLE `pariwisata` (
  `id_pariwisata` int(11) NOT NULL,
  `nama_pariwisata` varchar(100) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `lokasi_pariwisata` varchar(150) NOT NULL,
  `alamat_pariwisata` text NOT NULL,
  `deskripsi_pariwisata` text NOT NULL,
  `harga_pariwisata` int(11) NOT NULL,
  `satuan_harga` enum('/ orang','/ porsi') NOT NULL,
  `hari_operasional` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu','Setiap Hari') NOT NULL,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  `zona_waktu` enum('WIB','WITA','WIT') DEFAULT NULL,
  `rating_pariwisata` decimal(2,1) DEFAULT 0.0,
  `tanggal_input` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pariwisata`
--

INSERT INTO `pariwisata` (`id_pariwisata`, `nama_pariwisata`, `id_kategori`, `lokasi_pariwisata`, `alamat_pariwisata`, `deskripsi_pariwisata`, `harga_pariwisata`, `satuan_harga`, `hari_operasional`, `jam_buka`, `jam_tutup`, `zona_waktu`, `rating_pariwisata`, `tanggal_input`) VALUES
(2001, 'Pantai Kelingking', 5001, 'Nusa Penida, Bali', 'Dusun Karang Dawa, Desa Bunga Mekar, Kecamatan Nusa Penida, Kabupaten Klungkung, Bali', 'Pantai Kelingking, yang sering juga disebut T-rex Bay, merupakan salah satu destinasi pantai paling terkenal di Bali. Lokasinya berada di Desa Bunga Mekar, Nusa Penida. Bentuk daratannya menjorok panjang ke arah laut terbuka dan menyerupai jari kelingking, dikelilingi tebing menjulang sekitar 150 meter. Hamparan pasirnya berwarna putih hingga kemerahan, dengan panorama matahari terbenam yang memukau.', 15000, '/ orang', 'Setiap Hari', NULL, NULL, NULL, 5.0, '2025-10-21 19:39:36'),
(2002, 'Gunung Agung', 5002, 'Karangasem, Bali', 'Gunung Agung, Desa Besakih, Kecamatan Rendang, Kabupaten Karangasem, Bali, Indonesia', 'Gunung Agung adalah gunung berapi tertinggi di Bali dengan ketinggian sekitar 3.031 mdpl. Gunung ini dianggap suci oleh masyarakat Bali karena di puncaknya terdapat Pura Besakih, pura terbesar dan terpenting di Bali. Pendakian ke Gunung Agung menawarkan panorama spektakuler, mulai dari pemandangannGunung Rinjani di Lombok, hingga laut biru yang membentang luas.', 15000, '/ orang', 'Setiap Hari', NULL, NULL, NULL, 4.5, '2025-10-21 20:02:13'),
(2003, 'Pura Tirta Empul', 5005, 'Tampaksiring, Gianyar, Bali', 'Manukaya, Kecamatan Tampaksiring, Kabupaten Gianyar, Bali', 'Pura Tirta Empul adalah salah satu pura suci terpenting di Bali yang terkenal dengan mata air alami yang digunakan untuk ritual penyucian diri (melukat). Pura ini diibangun pada tahun 962 Masehi pada masa Dinasti Warmadewa, terbagi menjadi beberapa bagian: halaman luar, tengah, dan dalam, dengan kolam pemandian berisi pancuran air suci. Dan juga terdapat Istana Tampaksiring, yang dulu dibangun untuk Presiden Soekarno.', 50000, '/ orang', 'Setiap Hari', '08:00:00', '18:00:00', 'WITA', 5.0, '2025-12-02 09:24:33'),
(2004, 'Warung Mak Beng', 5003, 'Sanur, Denpasar, Bali', ' Jl. Hang Tuah No. 45, Sanur Kaja, Denpasar Selatan, Kota Denpasar, Bali', 'Menu utama hanya satu paket yang terdiri dari nasi putih, sup kepala ikan, ikan goreng, dan sambal khas. Jenis ikan yang dipakai tergantung stok harian : bisa tongkol, kakap, tenggiri, atau ikan laut lokal lainnya. Sup ikan-nya dimasak dengan rempah Bali tradisional, rasa agak asam dan pedas, serta tidak terlalu amis. Ada penggunaan belimbing wuluh dan mentimun agar rasa segar.', 45000, '/ porsi', 'Setiap Hari', '08:00:00', '22:00:00', 'WITA', 3.5, '2025-12-02 15:37:11'),
(2005, 'Klungkung Palace', 5004, 'Semarapura, Kabupaten Klungkung, Bali', 'Jl. Untung Surapati No.1, Semarapura Kelod, Kecamatan Klungkung, Kabupaten Klungkung, Bali', 'Klungkung Palace atau Puri Agung Semarapura adalah bekas istana Kerajaan Klungkung yang dibangun pada akhir abad ke-17 (sekitar 1686) oleh Dewa Agung Jambe. Yang paling khas adalah lukisan wayang pada langit-langit Bale Kertha Gosa yang menggambarkan cerita epos Hindu, ajaran moral, hingga hukum karma. Tempat ini kini menjadi destinasi wisata sejarah dan budaya yang populer di Bali Timur.', 25000, '/ orang', 'Setiap Hari', '08:00:00', '17:00:00', 'WITA', 5.0, '2025-12-03 00:34:20'),
(2006, 'Gunung Batur', 5002, 'Kintamani, Kabupaten Bangli, Bali', 'Jl. Pendakian Gunung Batur, Kecamatan Kintamani, Kabupaten Bangli, Bali', 'Gunung Batur (1.717 mdpl) adalah gunung berapi aktif yang sangat populer untuk wisata pendakian di Bali. Terletak di kawasan Kintamani, gunung ini menawarkan pengalaman sunrise trekking yang menjadi favorit wisatawan karena jalurnya relatif singkat (sekitar 1,5â€“2 jam). Dari puncak, pengunjung bisa menyaksikan pemandangan matahari terbit yang menakjubkan dengan latar Danau Batur dan kaldera luas sisa letusan purba.', 30000, '/ orang', 'Setiap Hari', '00:00:00', '00:00:00', '', 4.5, '2025-12-03 01:19:21'),
(2007, 'Pantai Kuta', 5001, 'Kuta, Kabupaten Bandung, Bali', 'Jl. Pantai Kuta, Kuta, Kabupaten Badung, Bali, Indonesia 80361.', 'Pantai Kuta terkenal dengan pasir putih yang luas, ombak yang cocok untuk peselancar pemula, serta pemandangan matahari terbenam yang memukau, menjadikannya favorit wisatawan yang ingin bersantai, surfing, atau menikmati suasana pantai khas Bali.', 5000, '/ orang', 'Setiap Hari', '00:00:00', '00:00:00', '', 5.0, '2025-12-03 03:03:53'),
(2008, 'Ubud Monkey Forest', 5005, 'Kawasan Ubud, Kabupaten Gianyar, Bali.', 'Jl. Monkey Forest, Ubud, Kabupaten Gianyar.', 'Ubud Monkey Forest atau Mandala Suci Wenara Wana adalah kawasan hutan lindung dan suaka margasatwa dengan populasi monyet macaque, disertai jalan setapak yang nyaman dan suasana alam tropis. Selain memberi kesempatan melihat monyet dari dekat, pengunjung juga dapat menikmati suasana hutan, taman, dan suasana khas Ubud yang asri dan tenang. Namun disarankan untuk menjaga barang bawaan karena monyet kadang â€œisengâ€.', 80000, '/ orang', 'Setiap Hari', '08:30:00', '17:30:00', 'WITA', 4.0, '2025-12-03 03:23:36'),
(2009, 'Warung Nasi Ayam Kedewatan Ibu Mangku', 5003, 'Ubud, Kabupaten Gianyar, Bali', 'Jl. Raya Kedewatan No.18, Kedewatan, Kecamatan Ubud, Gianyar, Bali', 'Warung Nasi Ayam Kedewatan Ibu Mangku terkenal karena menyajikan â€œnasi ayam Baliâ€ dengan bumbu khas yaitu nasi putih dengan lauk seperti ayam suwir atau betutu, sayur urap, sambal matah, dan pelengkap tradisional lainnya. Suasana warung cenderung sederhana dan khas lokal, cocok bagi wisatawan yang ingin merasakan cita rasa autentik masakan Bali tanpa harus ke restoran mewah.', 30000, '/ porsi', 'Setiap Hari', '07:00:00', '21:00:00', 'WITA', 4.5, '2025-12-03 04:07:32');

-- --------------------------------------------------------

--
-- Table structure for table `pariwisata_gambar`
--

CREATE TABLE `pariwisata_gambar` (
  `id_gambar` int(11) NOT NULL,
  `id_pariwisata` int(11) NOT NULL,
  `nama_gambar` varchar(255) NOT NULL,
  `urutan` int(2) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pariwisata_gambar`
--

INSERT INTO `pariwisata_gambar` (`id_gambar`, `id_pariwisata`, `nama_gambar`, `urutan`) VALUES
(3001, 2001, 'pantai-kelingking.jpeg', 1),
(3002, 2002, 'gunung-agung.jpg', 1),
(3003, 2001, 'pantai-pantai-kelingking-2.jpg', 2),
(3004, 2001, 'pantai-pantai-kelingking-3.jpg', 3),
(3005, 2001, 'pantai-pantai-kelingking-4.jpg', 4),
(3006, 2001, 'pantai-pantai-kelingking-5.jpg', 5),
(3007, 2003, 'budaya-puratirtaempul-1.jpg', 1),
(3008, 2003, 'pariwisata_2003_gambar2_1764679874_6193.jpg', 2),
(3009, 2003, 'budaya-puratirtaempul-3.jpg', 3),
(3010, 2003, 'budaya-puratirtaempul-4.jpg', 4),
(3011, 2003, 'pariwisata_2003_gambar5_1764680569_1435.jpg', 5),
(3012, 2004, 'kuliner-warungmakbeng-1.jpg', 1),
(3013, 2004, 'kuliner-warungmakbeng-2.jpg', 2),
(3014, 2004, 'kuliner-warungmakbeng-3.jpg', 3),
(3015, 2004, 'kuliner-warungmakbeng-4.jpg', 4),
(3016, 2004, 'kuliner-warungmakbeng-5.jpg', 5),
(3017, 2005, 'sejarah-klungkungpalace-1.jpeg', 1),
(3018, 2005, 'sejarah-klungkungpalace-2.jpg', 2),
(3019, 2005, 'sejarah-klungkungpalace-3.jpg', 3),
(3020, 2005, 'sejarah-klungkungpalace-4.jpg', 4),
(3021, 2005, 'sejarah-klungkungpalace-5.jpg', 5),
(3022, 2006, 'gunung-gunungbatur-1.jpg', 1),
(3023, 2006, 'gunung-gunungbatur-2.jpg', 2),
(3024, 2006, 'gunung-gunungbatur-3.jpg', 3),
(3025, 2006, 'gunung-gunungbatur-4.jpg', 4),
(3026, 2006, 'gunung-gunungbatur-5.jpg', 5),
(3027, 2007, 'pantai-pantaikuta-1.jpg', 1),
(3028, 2007, 'pantai-pantaikuta-2.jpg', 2),
(3029, 2007, 'pantai-pantaikuta-3.jpg', 3),
(3030, 2007, 'pantai-pantaikuta-4.jpg', 4),
(3031, 2007, 'pariwisata_2007_gambar5_1764727839_5889.jpg', 5),
(3032, 2008, 'budaya-ubudmonkeyforest-1.png', 1),
(3033, 2008, 'pariwisata_2008_gambar2_1764728774_9252.jpg', 2),
(3034, 2008, 'budaya-ubudmonkeyforest-3.jpg', 3),
(3035, 2008, 'budaya-ubudmonkeyforest-4.jpg', 4),
(3036, 2008, 'budaya-ubudmonkeyforest-5.jpg', 5),
(3037, 2009, 'kuliner-warungnasiibumangku-1.jpg', 1),
(3038, 2009, 'kuliner-warungnasiibumangku-2.jpg', 2),
(3039, 2009, 'kuliner-warungnasiibumangku-3.jpg', 3),
(3040, 2009, 'kuliner-warungnasiibumangku-4.jpg', 4),
(3041, 2009, 'kuliner-warungnasiibumangku-5.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email_user` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `tanggal_daftar` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email_user`, `password`, `role`, `tanggal_daftar`) VALUES
(1001, 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'admin', '2025-10-22 14:45:34'),
(1003, 'NadyaKameela1', 'nadyakameela@gmail.com', '1e6eb2590ee576e8f788729ad596403a', 'user', '2025-11-11 17:00:28'),
(1005, 'Leehan21', 'leehan21@gmail.com', '5d306b204ff0a1372f5fe6ddb56596e3', 'user', '2025-11-11 19:15:49'),
(1006, 'TaesanMantap', 'taesan@gmail.com', '539ccdcec1569b714c1a159649829949', 'user', '2025-11-11 19:21:52'),
(1007, 'asep', 'asep@gmail.com', 'dc855efb0dc7476760afaa1b281665f1', 'user', '2025-11-11 19:23:20'),
(1008, 'syifa', 'syifa@gmail.com', '1db7faed0921b4ab88da5e284ba45767', 'user', '2025-11-12 10:42:46'),
(1009, 'ihanihan', 'ihan@gmail.com', '67a6e73f7a38d6aaaae0532f4d791238', 'user', '2025-11-13 16:43:39'),
(1010, 'vaesaimyut', 'vaesatiara@gmail.com', '2251df3b7a7c55657526155222d2743a', 'user', '2025-11-17 10:21:30'),
(1011, 'Riwu', 'riwu@gmail.com', '4d950f3b2b3afe671375dd6d9bc1ba7b', 'user', '2025-11-19 11:42:32'),
(1012, 'cacaa', 'caca@gmail.com', 'd2104a400c7f629a197f33bb33fe80c0', 'user', '2025-12-02 20:41:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorit`
--
ALTER TABLE `favorit`
  ADD PRIMARY KEY (`id_favorit`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_pariwisata` (`id_pariwisata`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `pariwisata`
--
ALTER TABLE `pariwisata`
  ADD PRIMARY KEY (`id_pariwisata`),
  ADD KEY `fk_kategori` (`id_kategori`);

--
-- Indexes for table `pariwisata_gambar`
--
ALTER TABLE `pariwisata_gambar`
  ADD PRIMARY KEY (`id_gambar`),
  ADD KEY `fkpariwisatagambar` (`id_pariwisata`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorit`
--
ALTER TABLE `favorit`
  MODIFY `id_favorit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5006;

--
-- AUTO_INCREMENT for table `pariwisata`
--
ALTER TABLE `pariwisata`
  MODIFY `id_pariwisata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2010;

--
-- AUTO_INCREMENT for table `pariwisata_gambar`
--
ALTER TABLE `pariwisata_gambar`
  MODIFY `id_gambar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3042;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorit`
--
ALTER TABLE `favorit`
  ADD CONSTRAINT `favorit_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorit_ibfk_2` FOREIGN KEY (`id_pariwisata`) REFERENCES `pariwisata` (`id_pariwisata`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pariwisata`
--
ALTER TABLE `pariwisata`
  ADD CONSTRAINT `fk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pariwisata_gambar`
--
ALTER TABLE `pariwisata_gambar`
  ADD CONSTRAINT `fkpariwisatagambar` FOREIGN KEY (`id_pariwisata`) REFERENCES `pariwisata` (`id_pariwisata`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
