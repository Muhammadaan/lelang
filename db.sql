-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 01, 2019 at 03:19 PM
-- Server version: 8.0.15
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_taufik`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_roles`
--

CREATE TABLE `m_roles` (
  `id` int(11) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `akses` text NOT NULL,
  `is_deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `m_roles`
--

INSERT INTO `m_roles` (`id`, `nama`, `akses`, `is_deleted`) VALUES
(1, 'Super Admin', '{\"master_roles\":true,\"master_user\":true,\"master_akses\":true,\"master_cabang\":true,\"master_satuan\":true,\"master_kategori\":true,\"master_barang\":true,\"transaksi_pembelian\":true,\"transaksi_penjualan\":true,\"transaksi_penyesuain\":true,\"master_customer\":true,\"transaksi_pemesanan\":true,\"transaksi_kasir\":true,\"transaksi_peminjamanruang\":true,\"master_ruang\":true}', 0),
(5, 'Petugas', '[]', 0),
(6, 'Pemohon', '{\"master_akses\":true,\"master_user\":true,\"master_cabang\":true,\"master_satuan\":true,\"master_kategori\":true,\"master_barang\":true}', 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_ruang`
--

CREATE TABLE `m_ruang` (
  `id` int(11) NOT NULL,
  `kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_ruang`
--

INSERT INTO `m_ruang` (`id`, `kode`, `nama`, `is_deleted`) VALUES
(2, NULL, 'Ruang Mawar', 0),
(3, NULL, 'Ruang Melati', 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE `m_user` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `m_roles_id` int(5) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_user`
--

INSERT INTO `m_user` (`id`, `nama`, `username`, `password`, `m_roles_id`, `is_deleted`) VALUES
(1, 'Admin', 'admin', '63c8f0854166152eaacc876088a8e6b1729ca2d7', 1, 0),
(2, 'Ainul', 'aan', 'd1157e00ac5fbe3e94261598a634b18a3eb4a78b', 5, 0),
(3, 'ita', 'tia', '277f1f143c2cc4e44c17325baf8aeddc5752bab5', 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_pinjamruang`
--

CREATE TABLE `t_pinjamruang` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ruang_id` int(2) NOT NULL,
  `surat_pemohon` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `t_pinjamruang`
--

INSERT INTO `t_pinjamruang` (`id`, `user_id`, `ruang_id`, `surat_pemohon`, `tanggal`, `status`, `is_deleted`) VALUES
(1, 2, 0, '22341231133', 0, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_roles`
--
ALTER TABLE `m_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_ruang`
--
ALTER TABLE `m_ruang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_pinjamruang`
--
ALTER TABLE `t_pinjamruang`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_roles`
--
ALTER TABLE `m_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `m_ruang`
--
ALTER TABLE `m_ruang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_user`
--
ALTER TABLE `m_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_pinjamruang`
--
ALTER TABLE `t_pinjamruang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
