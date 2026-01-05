-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 04, 2026 at 10:01 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sapadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `gambar` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `nama`, `role`, `gambar`) VALUES
(1, 'dosen', '$2y$10$45nGtc2WvMtF2xAoN8Dn1ewQu1nrd6kTAGFG1JPjzPeXkyrrDolRi', 'dosen', 'admin', 'dosen_ifpolibatam.png'),
(2, 'mahasiswa', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nama Mahasiswa', 'mahasiswa', 'default.png');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users_backup`
--

CREATE TABLE `admin_users_backup` (
  `id` int NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_users_backup`
--

INSERT INTO `admin_users_backup` (`id`, `username`, `password`, `nama`, `role`) VALUES
(1, 'dosen', '$2y$10$KHwW2ElGBCH7xXtD6jb/Ye7Iua6guIjfYoeyzptMe8qsMGu/vp7U2', 'Admin Dosen', 'admin'),
(2, 'mahasiswa', '$2y$10$zjJxXttyiZaoH4LssNUPiO2So.TacShsqS2391qVtYzpMAYks5E3a', 'Mahasiswa', 'mahasiswa');

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `nik` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prodi` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`nik`, `nama`, `prodi`, `jabatan`) VALUES
('112093', 'Yeni Rokhayati, S.Si., M.Sc.', 'Teknik Informatika', 'Pengusul Proyek'),
('112094', 'Ir. Dwi Ely Kurniawan, S.Pd., M.Kom', 'Teknik Informatika', 'Manajer Proyek');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jurusan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `angkatan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nim`, `nama`, `jurusan`, `angkatan`, `password`) VALUES
('3312511028', 'Juni Friskenny Sinaga', 'Teknik Informatika', '2025', '$2y$10$Xc6Vk9z8qRwLmNpJsT4G.OuY7iZv1B2C3D4E5F6G7H8I9J0K1L2M'),
('3312511029', 'Muhamad Restu Putra', 'Teknik Informatika', '2025', '$2y$10$Xc6Vk9z8qRwLmNpJsT4G.OuY7iZv1B2C3D4E5F6G7H8I9J0K1L2M'),
('3312511030', 'Dita Indriyani', 'Teknik Informatika', '2025', '$2y$10$Xc6Vk9z8qRwLmNpJsT4G.OuY7iZv1B2C3D4E5F6G7H8I9J0K1L2M');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `penulis` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'published'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `kategori`, `date`, `penulis`, `gambar`, `pdf`, `status`) VALUES
(6, 'Test 123', 'Test 123 Test 123 Test 123 Test 123', 'Akademik', '2026-01-04 05:00:35', 'dosen', 'img_1767503364_6959f6045131e.png', '', 'published'),
(8, 'Pengumuman Pelaksanaan Ujian Semester', 'Diberitahukan kepada seluruh mahasiswa bahwa pelaksanaan ujian semester akan dilaksanakan sesuai dengan kalender akademik yang telah ditetapkan oleh pihak kampus. Mahasiswa diwajibkan mengikuti ujian sesuai dengan jadwal, waktu, dan ketentuan yang berlaku pada masing-masing mata kuliah.\r\n\r\nMahasiswa diharapkan telah memenuhi seluruh persyaratan akademik, seperti kehadiran minimal dan penyelesaian tugas, sebelum mengikuti ujian. Selama pelaksanaan ujian, mahasiswa wajib mematuhi tata tertib ujian yang berlaku. Pelanggaran terhadap aturan ujian akan dikenakan sanksi sesuai dengan ketentuan akademik kampus.\r\n\r\nInformasi lebih lanjut mengenai jadwal dan teknis pelaksanaan ujian dapat dilihat melalui sistem akademik atau pengumuman resmi dari program studi.\r\n\r\nDemikian pengumuman ini disampaikan. Atas perhatian dan kerja sama mahasiswa, kami ucapkan terima kasih.\r\n\r\nBagian Akademik\r\nPoliteknik Negeri Batam', 'Akademik', '2026-01-04 08:58:04', 'dosen', 'img_1767517084_695a2b9c65472.jpeg', '', 'published'),
(9, 'Tentang Pengisian Kartu Rencana Studi (KRS)', 'Diberitahukan kepada seluruh mahasiswa bahwa pengisian Kartu Rencana Studi (KRS) untuk semester berjalan wajib dilakukan sesuai dengan jadwal yang telah ditentukan oleh pihak kampus. Pengisian KRS dilakukan melalui sistem akademik kampus dengan memperhatikan ketentuan jumlah SKS dan mata kuliah yang diambil.\r\n\r\nMahasiswa diharapkan berkonsultasi terlebih dahulu dengan dosen pembimbing akademik sebelum melakukan pengisian KRS agar rencana studi sesuai dengan ketentuan kurikulum. Mahasiswa yang tidak melakukan pengisian KRS hingga batas waktu yang ditentukan tidak diperkenankan mengikuti kegiatan perkuliahan pada semester tersebut.\r\n\r\nApabila terdapat kendala teknis atau administrasi dalam proses pengisian KRS, mahasiswa dapat menghubungi bagian akademik atau program studi terkait.\r\n\r\nDemikian pengumuman ini disampaikan untuk menjadi perhatian bersama.\r\n\r\nBagian Akademik\r\nPoliteknik Negeri Batam', 'Akademik', '2026-01-04 09:57:53', 'dosen', 'img_1767520673_695a39a172338.jpg', '', 'published');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
