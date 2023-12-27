-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 26, 2023 at 07:08 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canvas`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) NOT NULL,
  `activity_type` varchar(50) DEFAULT NULL,
  `activity_detail` varchar(255) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`log_id`, `activity_type`, `activity_detail`, `timestamp`) VALUES
(1, 'klik_link', 'https://youtu.be/RUTV_5m4VeI?list=PLFIM0718LjIWXagluzROrA-iBY9eeUt4w', '2023-12-24 17:18:03'),
(2, 'klik_link', 'https://youtu.be/RUTV_5m4VeI?list=PLFIM0718LjIWXagluzROrA-iBY9eeUt4w', '2023-12-25 17:18:24'),
(3, 'submit_assignment', '', '2023-12-06 17:18:24'),
(4, 'submit_assignment', '', '2023-12-05 17:18:24'),
(5, 'submit_assignment', '', '2023-12-04 17:18:24'),
(6, 'submit_assignment', '', '2023-12-04 17:18:24'),
(7, 'submit_assignment', '', '2023-12-04 17:18:24'),
(8, 'submit_assignment', '', '2023-12-03 17:18:24'),
(9, 'submit_assignment', 'Submission berhasil diunggah: Assignment ID: 1, Mahasiswa ID: 3', '2023-12-24 11:23:50'),
(10, 'submit_assignment', 'Submission berhasil diunggah: Assignment ID: 2, Mahasiswa ID: 3', '2023-12-26 14:25:47'),
(11, 'submit_assignment', 'Submission berhasil diunggah: Assignment ID: 3, Mahasiswa ID: 3', '2023-12-24 18:11:25'),
(12, 'submit_assignment', 'Submission berhasil diunggah: Assignment ID: 4, Mahasiswa ID: 3', '2023-12-26 19:39:02'),
(13, 'submit_assignment', 'Submission berhasil diunggah: Assignment ID: 5, Mahasiswa ID: 3', '2023-12-26 20:30:13');

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `assignment_id` int(11) NOT NULL,
  `assignment_title` varchar(255) NOT NULL,
  `assignment_document` varchar(255) DEFAULT NULL,
  `is_link` tinyint(1) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`assignment_id`, `assignment_title`, `assignment_document`, `is_link`, `class_id`, `created_at`) VALUES
(1, 'Javascript Challange #1', 'https://youtu.be/yG03EvfHSIU?list=PLnHJACx3NwAdQElswAscNtHAZLAQYgpDA', 1, 1, '2023-11-19 09:58:18'),
(2, 'assigment 1', 'BRIEF PERANCANGAN WEBSITE-1.pdf', 0, 2, '2023-12-26 07:04:33'),
(3, 'mention react native', 'BRIEF PERANCANGAN WEBSITE.pdf', 0, 3, '2023-12-26 11:10:45'),
(4, 'assigment 3 react native', 'Ahp-1.pdf', 0, 4, '2023-12-26 12:36:31'),
(5, 'assigment 2', 'BRIEF PERANCANGAN WEBSITE.pdf', 0, 4, '2023-12-26 12:36:46');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_submissions`
--

CREATE TABLE `assignment_submissions` (
  `submission_id` int(11) NOT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `mahasiswa_id` int(11) DEFAULT NULL,
  `submission_file` varchar(255) DEFAULT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('To Do','Doing','Done','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assignment_submissions`
--

INSERT INTO `assignment_submissions` (`submission_id`, `assignment_id`, `mahasiswa_id`, `submission_file`, `submission_date`, `status`) VALUES
(1, 1, 1, 'tes.pdf', '2023-11-22 13:52:48', 'To Do'),
(2, 1, 3, 'invoice-INV-20231219001-1.pdf', '2023-12-26 04:23:50', 'To Do'),
(3, 2, 3, 'BRIEF PERANCANGAN WEBSITE.pdf', '2023-12-26 07:25:47', 'To Do'),
(4, 3, 3, 'BRIEF PERANCANGAN WEBSITE.pdf', '2023-12-26 11:11:25', 'To Do'),
(5, 4, 3, 'auliya.pdf', '2023-12-26 12:39:02', 'Done'),
(6, 5, 3, 'BRIEF PERANCANGAN WEBSITE.pdf', '2023-12-26 13:30:13', 'Done');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `mentor_id` int(11) DEFAULT NULL,
  `class_description` text DEFAULT NULL,
  `class_image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_name`, `mentor_id`, `class_description`, `class_image`, `created_at`) VALUES
(1, 'Pengenalan Javascript Dasar', 1, 'Pengenalan dasar dasar pada bahasa pemrograman javascript.', 'javascript.png', '2023-11-19 09:24:52'),
(2, 'Pengenalan React Native', 2, 'berikut adalah pengenalan React Native', 'react native.png', '2023-12-26 04:15:14'),
(3, 'Pengenalan React Native 2', 2, 'ini adalah session 2', 'react native.png', '2023-12-26 11:08:55'),
(4, 'Pengenalan React Native 3', 2, 'tahap 3', 'preview.png', '2023-12-26 12:35:43');

-- --------------------------------------------------------

--
-- Table structure for table `collaboration`
--

CREATE TABLE `collaboration` (
  `id` int(11) NOT NULL,
  `jenis_colaboration` enum('google docs','google slide','google sheet') NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `collaboration`
--

INSERT INTO `collaboration` (`id`, `jenis_colaboration`, `mahasiswa_id`, `link`, `created_at`, `updated_at`) VALUES
(1, 'google docs', 3, 'https://docs.google.com/document/d/1pc_y6dCM8vIuSmhMDk8GXdFT7s2tubg5wOFqZHJIack/edit#heading=h.m883tdqqe6v5', '2023-12-26 14:02:51', '2023-12-26 14:02:51');

-- --------------------------------------------------------

--
-- Table structure for table `collaboration_detail`
--

CREATE TABLE `collaboration_detail` (
  `id` int(11) NOT NULL,
  `collaboration_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `collaboration_detail`
--

INSERT INTO `collaboration_detail` (`id`, `collaboration_id`, `nama`, `email`) VALUES
(1, 1, 'agus salim', 'agus@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `submission_id` int(11) DEFAULT NULL,
  `mahasiswa_id` int(11) DEFAULT NULL,
  `grade` float DEFAULT NULL,
  `kriteria` varchar(10) NOT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`grade_id`, `submission_id`, `mahasiswa_id`, `grade`, `kriteria`, `feedback`, `created_at`) VALUES
(2, 1, 1, 75, 'B', NULL, '2023-11-22 14:20:59'),
(12, 3, 3, 90, 'A', 'saya ingin memberi anda waktu lebih baik', '2023-12-26 10:50:35'),
(13, 4, 3, 80, 'B', NULL, '2023-12-26 12:03:20'),
(15, 6, 3, 90, 'A', NULL, '2023-12-26 13:37:09');

-- --------------------------------------------------------

--
-- Table structure for table `grade_nilai`
--

CREATE TABLE `grade_nilai` (
  `id` int(11) NOT NULL,
  `nilai` decimal(5,2) NOT NULL,
  `grade` char(1) NOT NULL,
  `grade_range_start` decimal(5,2) NOT NULL,
  `grade_range_end` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grade_nilai`
--

INSERT INTO `grade_nilai` (`id`, `nilai`, `grade`, `grade_range_start`, `grade_range_end`) VALUES
(1, '90.00', 'A', '80.00', '100.00'),
(2, '75.00', 'B', '70.00', '79.99'),
(3, '60.00', 'C', '50.00', '69.99'),
(4, '40.00', 'D', '30.00', '49.99'),
(5, '20.00', 'E', '0.00', '29.99');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `link_group` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `link_group`) VALUES
(1, 'Kelompok 1 (Javascript)', 'https://t.me/codeigniterindonesia/1'),
(2, 'Kelompok 2 (Node.js)', 'https://t.me/reactnativeindo/2'),
(3, 'React Native', NULL),
(4, 'Python', 'https://t.me/pythonID/290029');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `member_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`member_id`, `group_id`, `mahasiswa_id`) VALUES
(2, 1, 2),
(4, 2, 1),
(5, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `helps`
--

CREATE TABLE `helps` (
  `help_id` int(11) NOT NULL,
  `help_title` varchar(255) NOT NULL,
  `help_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `helps`
--

INSERT INTO `helps` (`help_id`, `help_title`, `help_text`) VALUES
(1, 'Contoh Judul', 'Isi dari jawaban help');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `topik` varchar(250) NOT NULL,
  `jam` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `link_zoom` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `class_id`, `topik`, `jam`, `tanggal`, `link_zoom`, `created_at`, `updated_at`) VALUES
(1, 2, 'introdiction', '17:00 - 19:00 WIB', '2024-01-02', 'https://www.liveagent.com/templates/zoom-meeting-invitation/', '2023-12-26 15:52:26', '2023-12-26 15:59:56'),
(2, 2, 'cara install', '19:00 - 22:00 WIB', '2024-01-02', 'https://www.liveagent.com/templates/zoom-meeting-invitation/', '2023-12-26 15:55:09', '2023-12-26 15:55:09');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nohp` varchar(25) NOT NULL,
  `nim` varchar(100) NOT NULL,
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `email`, `password`, `nama`, `nohp`, `nim`, `foto`) VALUES
(1, 'riko@gmail.com', '$2y$10$ktyk5uuzb3/IaIo45xLpbuRwelFNCBxUMeI6M3qF7tAUQ1wkSPyoS', 'Riko Putra', '089999123213', '', ''),
(2, 'rikoa@gmail.com', '$2y$10$ktyk5uuzb3/IaIo45xLpbuRwelFNCBxUMeI6M3qF7tAUQ1wkSPyoS', 'Riko Putraaaa', '089999123213', '', ''),
(3, 'hasanarofid@gmail.com', '$2y$10$k9zakShNMuNWDjcxtNrsI.59EGR1frEuXSssuG4pderq15MRw9GCi', 'hasan arofid', '081213131', '232424', 'profile_3_1703614035.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `mentor`
--

CREATE TABLE `mentor` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nohp` varchar(25) NOT NULL,
  `foto` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `instagram_link` varchar(255) NOT NULL,
  `tiktok_link` varchar(255) NOT NULL,
  `facebook_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mentor`
--

INSERT INTO `mentor` (`id`, `email`, `password`, `nama`, `nohp`, `foto`, `deskripsi`, `instagram_link`, `tiktok_link`, `facebook_link`) VALUES
(1, 'budi@gmail.com', '$2y$10$B1fca7gEnxRh7sHXxbZlHOKC25etoPbyS8.YIWbpQn5NXGLGee8ti', 'Budi', '0899213213', '0', 'Pekerja keras', 'https://www.instagram.com', 'https://www.tiktok.com', 'https://www.facebook.com'),
(2, 'barra@gmail.com', '$2y$10$XlFV1Qpvwro5tYqwmS9CjutBP1liqZcuQOKGTojB3pvH2MgpNDUyi', 'barra', '081213131', 'profile_2_1703613866.jpg', 'ada', 'https://www.instagram.com/syntax_errors404/', 'https://www.instagram.com/syntax_errors404/', 'https://www.instagram.com/syntax_errors404/');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL,
  `module_title` varchar(255) NOT NULL,
  `module_document` varchar(255) DEFAULT NULL,
  `is_link` tinyint(1) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module_id`, `module_title`, `module_document`, `is_link`, `class_id`, `created_at`) VALUES
(1, 'Intro', 'https://youtu.be/RUTV_5m4VeI?list=PLFIM0718LjIWXagluzROrA-iBY9eeUt4w', 1, 1, '2023-11-19 09:58:18'),
(2, 'modul 1', 'BRIEF PERANCANGAN WEBSITE-1.pdf', 0, 2, '2023-12-26 07:04:51'),
(3, 'modul 2', 'auliya.pdf', 0, 2, '2023-12-26 10:57:25'),
(4, 'modul 3', 'invoice-INV-20231220001-1.pdf', 0, 2, '2023-12-26 10:57:40'),
(5, 'modul tahap 2', 'BRIEF PERANCANGAN WEBSITE-1.pdf', 0, 3, '2023-12-26 11:10:18'),
(6, 'modul 4', 'Ahp.pdf', 0, 4, '2023-12-26 12:35:59');

-- --------------------------------------------------------

--
-- Table structure for table `program_manager`
--

CREATE TABLE `program_manager` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nohp` varchar(25) NOT NULL,
  `foto` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `instagram_link` varchar(255) NOT NULL,
  `tiktok_link` varchar(255) NOT NULL,
  `facebook_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `program_manager`
--

INSERT INTO `program_manager` (`id`, `email`, `password`, `nama`, `nohp`, `foto`, `deskripsi`, `instagram_link`, `tiktok_link`, `facebook_link`) VALUES
(1, 'rizal@gmail.com', '$2y$10$p993qJV1zCTkHlYT9gWVt.cfjCoiPLQSBmvOvW6uiMEA8w6a0vLfu', 'Rizaldi', '0812999213213', '', 'sadasdsad', 'asdsad', 'asdasd', 'asdsad'),
(2, 'barra@gmail.com', '$2y$10$zD8NPX.nTfbvKiTf7CuJmeP438w1bVgTyij3xQMAUIwRq0z4tBNwm', 'barra', '081213131', 'profile_2_1703613690.jpg', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `syllabus`
--

CREATE TABLE `syllabus` (
  `syllabus_id` int(11) NOT NULL,
  `syllabus_document` varchar(255) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `syllabus`
--

INSERT INTO `syllabus` (`syllabus_id`, `syllabus_document`, `class_id`, `created_at`) VALUES
(2, 'tes.pdf', 1, '2023-11-25 07:29:39'),
(3, 'Ahp-1.pdf', 2, '2023-12-26 07:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text DEFAULT NULL,
  `task_due_date` date DEFAULT NULL,
  `task_status` enum('To Do','Doing','Done') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `mahasiswa_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `task_description`, `task_due_date`, `task_status`, `created_at`, `mahasiswa_id`, `class_id`) VALUES
(1, 'Mengerjakan Wireframe Web', 'Pengerjaan wireframe web sistem keuangan pada PT. Adaro', '2023-11-21', 'Done', '2023-11-18 08:36:06', 1, NULL),
(2, 'asdsadsasda', 'xzxxxx', '2023-11-18', 'Doing', '2023-11-18 09:10:32', 1, NULL),
(3, 'tugas 1', 'tugas 1 ini', '2023-12-29', 'Done', '2023-12-25 13:37:02', 3, 1),
(5, 'tugas 2', 'ini adalah', '2024-10-01', 'Done', '2023-12-26 07:00:50', 3, 2),
(6, 'Tugas dasar ', 'coba buat function mention dengan react native', '2023-12-29', 'Done', '2023-12-26 11:09:57', 3, 3),
(7, 'react native lanjut', 'coba', '2024-01-01', 'Done', '2023-12-26 12:37:49', 3, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD PRIMARY KEY (`submission_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `mentor_id` (`mentor_id`);

--
-- Indexes for table `collaboration`
--
ALTER TABLE `collaboration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collaboration_detail`
--
ALTER TABLE `collaboration_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`);

--
-- Indexes for table `grade_nilai`
--
ALTER TABLE `grade_nilai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `helps`
--
ALTER TABLE `helps`
  ADD PRIMARY KEY (`help_id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mentor`
--
ALTER TABLE `mentor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`module_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `program_manager`
--
ALTER TABLE `program_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `syllabus`
--
ALTER TABLE `syllabus`
  ADD PRIMARY KEY (`syllabus_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `collaboration`
--
ALTER TABLE `collaboration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `collaboration_detail`
--
ALTER TABLE `collaboration_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `grade_nilai`
--
ALTER TABLE `grade_nilai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `helps`
--
ALTER TABLE `helps`
  MODIFY `help_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mentor`
--
ALTER TABLE `mentor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `program_manager`
--
ALTER TABLE `program_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `syllabus`
--
ALTER TABLE `syllabus`
  MODIFY `syllabus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`mentor_id`) REFERENCES `mentor` (`id`);

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`),
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`);

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
