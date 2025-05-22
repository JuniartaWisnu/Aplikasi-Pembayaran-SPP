-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 20, 2025 at 03:08 AM
-- Server version: 5.7.39
-- PHP Version: 8.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spp`
--

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nominal` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `tanggal_pembayaran` date NOT NULL,
  `bulan_tagihan` date NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `selisih` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `siswa_id`, `user_id`, `nominal`, `status`, `tanggal_pembayaran`, `bulan_tagihan`, `created_at`, `updated_at`, `deleted_at`, `selisih`) VALUES
(173, 1010, 5, 100000, 0, '2025-05-19', '2025-01-01', '2025-05-19 03:12:11', NULL, NULL, -25000),
(174, 1010, 5, 25000, 1, '2025-05-19', '2025-01-01', '2025-05-19 03:12:30', NULL, NULL, 0),
(175, 1013, 5, 125000, 1, '2025-05-19', '2025-01-01', '2025-05-19 03:15:29', NULL, NULL, 0),
(176, 1013, 5, 125000, 1, '2025-05-19', '2025-02-01', '2025-05-19 03:15:56', NULL, NULL, 0),
(177, 1015, 5, 125000, 1, '2025-05-19', '2025-01-01', '2025-05-19 03:16:17', NULL, NULL, 0),
(178, 1013, 5, 125000, 1, '2025-05-19', '2025-05-01', '2025-05-19 05:12:27', NULL, NULL, 0),
(179, 1013, 5, 125000, 1, '2025-05-19', '2025-06-01', '2025-05-19 05:12:27', NULL, NULL, 0),
(180, 1013, 5, 125000, 1, '2025-05-19', '2025-07-01', '2025-05-19 05:12:27', NULL, NULL, 0),
(181, 1013, 5, 125000, 1, '2025-05-19', '2025-08-01', '2025-05-19 05:12:27', NULL, NULL, 0),
(182, 1017, 5, 125000, 1, '2025-05-19', '2025-01-01', '2025-05-19 08:11:17', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `nis` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `alamat` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`nis`, `user_id`, `nama`, `kelas`, `alamat`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1010, 14, 'I Komang Wisnu Juniarta', 'XI PPLG', 'Br. Teruna Blahbatuh', '2025-05-17 12:05:07', '2025-05-17 16:14:24', NULL),
(1011, 5, 'saya', 'saya', '-', '2025-05-17 12:06:18', '2025-05-17 12:06:18', '2025-05-17 12:07:09'),
(1012, 15, 'Putu Bhisma Parasurama', 'XI PPLG', '-', '2025-05-17 12:26:34', NULL, NULL),
(1013, 16, 'A.A Gde Krisna Satya Wibawa', 'XI PPLG', '-', '2025-05-17 12:38:19', NULL, NULL),
(1014, 17, 'I Wayan Sumber Bawa', 'XI PPLG', '-', '2025-05-17 12:52:53', NULL, NULL),
(1015, 18, 'I Nyoman Tri Saputra', 'XI PPLG', '-', '2025-05-18 06:03:21', NULL, NULL),
(1016, 19, 'I Komang Wisnu Juniarta', 'X PPLG', '-', '2025-05-18 06:22:22', '2025-05-18 07:34:58', NULL),
(1017, 20, 'Ni Putu Bunga Anandita', 'XI PPLG', '-', '2025-05-19 02:32:21', NULL, NULL),
(1018, 21, 'Langgam Ramadhan', 'XI PPLG', 'Jl. Kaliasem', '2025-05-19 02:36:38', NULL, NULL),
(1019, 22, 'I Wayan Candra Wiadnyana', 'XI PPLG', '-', '2025-05-19 03:05:53', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','siswa') NOT NULL DEFAULT 'siswa',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'Admin', '$2y$10$vrgY094vEgiSmvGws5EHb.ekcXLZtTOrEfyFE9atBA./4bNBJNUdy', 'admin', '2025-05-17 12:19:23', NULL, NULL),
(14, '0084939316', '$2y$10$XxstdnazKFC7OhITx5yF7ePPqLb.LYHlsKJMem3TVzxB..R0jJj1O', 'siswa', '2025-05-17 12:05:07', NULL, NULL),
(15, '0000000001', '$2y$10$Gq2PjJIyFkBr5zlxAc1eHOuOPAwt8Lw.5YMGM1mlQC1H57OjF/E4m', 'siswa', '2025-05-17 12:26:34', NULL, NULL),
(16, '0000000002', '$2y$10$6ns744cidsJfo5yF3Z3fUOgmFXEOjL5ShxGRQnTX5MHaqL5HY0m1.', 'siswa', '2025-05-17 12:38:19', NULL, NULL),
(17, '0000000003', '$2y$10$OOP37ho4izg2dx/WTXkLveRH0epwylWy0Nr4jsJyictSVB3Uyce1K', 'siswa', '2025-05-17 12:52:53', NULL, NULL),
(18, '0000000004', '$2y$10$6ER1v20K32gGV873f2QkKuKm5fyTXq4TzVvfMoy.Tia2Xn0I8hdnu', 'siswa', '2025-05-18 06:03:21', NULL, NULL),
(19, '0000000005', '$2y$10$VgwthgYfQq5FvQx6eBYq1OGvtdwJNQp9erVtJ.hwtxsJ21Pm3CKE.', 'siswa', '2025-05-18 06:22:22', NULL, NULL),
(20, '0000000007', '$2y$10$tG0zjFLzqVlybl3EXMR9au6y4BwlTbKhO3XlsjU9UBxkf9VpH7wPm', 'siswa', '2025-05-19 02:32:21', NULL, NULL),
(21, '0000000008', '$2y$10$O.L8RsVTRz1ypFFDJMBAI.t34ygqGo4VTY6i9guQe21BmRSMUS7eK', 'siswa', '2025-05-19 02:36:38', NULL, NULL),
(22, '0078279291', '$2y$10$LwL2whXM2Rl6LYnvSzeO5O.AwXGzcZ.9nBhd.ba52Sntg7i62AtUC', 'siswa', '2025-05-19 03:05:53', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`nis`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `nis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1020;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`nis`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
