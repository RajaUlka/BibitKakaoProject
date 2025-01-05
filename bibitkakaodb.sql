-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Des 2024 pada 09.43
-- Versi server: 8.0.36
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bibitkakaodb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `device`
--

CREATE TABLE `device` (
  `device_id` int NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `device_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `device_status` varchar(50) NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `device`
--

INSERT INTO `device` (`device_id`, `device_name`, `device_type`, `device_status`, `user_id`, `created_at`) VALUES
(1, 'buset', 'dummy', 'pemupukan', 1, '2024-12-22 08:15:25'),
(2, 'pertanian joko', 'set 1', 'lengkap', 1, '2024-11-24 00:14:00'),
(6, 'testing', 'lengkap', 'aktif', 3, '2024-12-22 01:37:27'),
(7, 'test', 'lengkap', 'aktif', 4, '2024-12-22 02:17:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `histori_pemupukan`
--

CREATE TABLE `histori_pemupukan` (
  `history_pemupukan_id` int NOT NULL,
  `device_id` int NOT NULL,
  `action_type` enum('manual','scheduled') NOT NULL,
  `schedule_date` date DEFAULT NULL,
  `action_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `histori_pemupukan`
--

INSERT INTO `histori_pemupukan` (`history_pemupukan_id`, `device_id`, `action_type`, `schedule_date`, `action_timestamp`, `updated_by`) VALUES
(4, 1, 'scheduled', '1111-11-11', '2024-11-24 09:41:37', 2),
(5, 7, 'scheduled', '2007-07-07', '2024-12-22 12:17:59', 4),
(6, 7, 'scheduled', '2222-12-11', '2024-12-22 12:18:37', 4),
(7, 7, 'scheduled', '3333-03-31', '2024-12-22 12:18:37', 4),
(8, 7, 'scheduled', '2025-12-12', '2024-12-25 10:38:25', 4),
(9, 7, 'scheduled', '2222-04-12', '2024-12-25 11:51:05', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `histori_pencahayaan`
--

CREATE TABLE `histori_pencahayaan` (
  `history_pencahayaan_id` int NOT NULL,
  `device_id` int NOT NULL,
  `manual_control` tinyint(1) NOT NULL,
  `schedule_start` time DEFAULT NULL,
  `schedule_end` time DEFAULT NULL,
  `change_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `action_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `histori_pencahayaan`
--

INSERT INTO `histori_pencahayaan` (`history_pencahayaan_id`, `device_id`, `manual_control`, `schedule_start`, `schedule_end`, `change_type`, `action_timestamp`, `updated_by`) VALUES
(1, 1, 0, '05:00:00', '17:00:00', 'scheduled', '2024-11-24 12:40:46', 2),
(2, 7, 1, NULL, NULL, 'scheduled', '2024-12-22 11:44:10', 4),
(3, 7, 0, '05:00:00', '17:00:00', 'scheduled', '2024-12-22 11:49:52', 4),
(4, 7, 0, '07:07:00', '12:11:00', 'scheduled', '2024-12-22 11:52:37', 4),
(5, 7, 0, '07:07:00', '12:11:00', 'scheduled', '2024-12-22 11:54:40', 4),
(6, 7, 1, NULL, NULL, 'scheduled', '2024-12-22 11:54:43', 4),
(7, 7, 1, NULL, NULL, 'scheduled', '2024-12-22 11:54:45', 4),
(8, 7, 1, NULL, NULL, 'scheduled', '2024-12-22 11:55:23', 4),
(9, 7, 1, NULL, NULL, 'scheduled', '2024-12-22 12:04:36', 4),
(10, 7, 2, NULL, NULL, 'scheduled', '2024-12-22 12:04:38', 4),
(11, 7, 1, NULL, NULL, 'scheduled', '2024-12-22 12:04:40', 4),
(12, 7, 2, NULL, NULL, 'scheduled', '2024-12-22 12:04:41', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `histori_penyiraman`
--

CREATE TABLE `histori_penyiraman` (
  `history_penyiraman_id` int NOT NULL,
  `device_id` int NOT NULL,
  `min_humidity` float(5,2) NOT NULL,
  `max_humidity` float(5,2) NOT NULL,
  `schedule_start` time DEFAULT NULL,
  `schedule_end` time DEFAULT NULL,
  `change_type` enum('manual','automatic') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `action_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `histori_penyiraman`
--

INSERT INTO `histori_penyiraman` (`history_penyiraman_id`, `device_id`, `min_humidity`, `max_humidity`, `schedule_start`, `schedule_end`, `change_type`, `action_timestamp`, `updated_by`) VALUES
(1, 1, 10.00, 30.00, '09:15:00', '09:15:00', 'automatic', '2024-11-24 13:45:15', 2),
(2, 1, 10.00, 19.00, '10:00:00', '10:15:00', 'automatic', '2024-12-02 14:05:22', 2),
(3, 7, 20.00, 80.00, '09:00:00', '09:15:00', 'automatic', '2024-12-22 11:34:02', 4),
(4, 7, 20.00, 80.00, '17:00:00', '17:17:00', 'automatic', '2024-12-22 11:39:26', 4),
(5, 7, 40.00, 70.00, '18:20:00', '18:25:00', 'automatic', '2024-12-22 13:02:45', 4),
(6, 7, 20.00, 80.00, '09:10:00', '09:15:00', 'automatic', '2024-12-22 13:14:37', 4),
(7, 7, 20.00, 80.00, '09:10:00', '09:15:00', 'automatic', '2024-12-25 10:31:33', 4),
(8, 7, 12.00, 23.00, '19:20:00', '20:20:00', 'automatic', '2024-12-29 04:23:23', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemupukan`
--

CREATE TABLE `pemupukan` (
  `schedule_id` int NOT NULL,
  `device_id` int NOT NULL,
  `schedule_date` date NOT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pemupukan`
--

INSERT INTO `pemupukan` (`schedule_id`, `device_id`, `schedule_date`, `last_updated`) VALUES
(5, 1, '1111-11-11', '2024-11-24 09:41:37'),
(6, 7, '2007-07-07', '2024-12-22 12:17:59'),
(7, 7, '2222-12-11', '2024-12-22 12:18:37'),
(9, 7, '2025-12-12', '2024-12-25 10:38:25'),
(10, 7, '2222-04-12', '2024-12-25 11:51:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pencahayaan`
--

CREATE TABLE `pencahayaan` (
  `lighting_id` int NOT NULL,
  `device_id` int NOT NULL,
  `manual_control` tinyint(1) NOT NULL DEFAULT '0',
  `schedule_start` time DEFAULT NULL,
  `schedule_end` time DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pencahayaan`
--

INSERT INTO `pencahayaan` (`lighting_id`, `device_id`, `manual_control`, `schedule_start`, `schedule_end`, `last_updated`) VALUES
(4, 1, 0, '05:00:00', '17:00:00', '2024-11-24 12:40:46'),
(5, 7, 1, NULL, NULL, '2024-12-22 11:44:10'),
(6, 7, 0, '05:00:00', '17:00:00', '2024-12-22 11:49:52'),
(7, 7, 0, '07:07:00', '12:11:00', '2024-12-22 11:52:37'),
(8, 7, 0, '07:07:00', '12:11:00', '2024-12-22 11:54:40'),
(9, 7, 1, NULL, NULL, '2024-12-22 11:54:43'),
(10, 7, 1, NULL, NULL, '2024-12-22 11:54:45'),
(11, 7, 1, NULL, NULL, '2024-12-22 11:55:23'),
(12, 7, 1, NULL, NULL, '2024-12-22 12:04:36'),
(13, 7, 2, NULL, NULL, '2024-12-22 12:04:38'),
(14, 7, 1, NULL, NULL, '2024-12-22 12:04:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyiraman`
--

CREATE TABLE `penyiraman` (
  `watering_id` int NOT NULL,
  `device_id` int NOT NULL,
  `min_humidity` float(5,2) DEFAULT NULL,
  `max_humidity` float(5,2) DEFAULT NULL,
  `schedule_start` time DEFAULT NULL,
  `schedule_end` time DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `penyiraman`
--

INSERT INTO `penyiraman` (`watering_id`, `device_id`, `min_humidity`, `max_humidity`, `schedule_start`, `schedule_end`, `last_updated`) VALUES
(4, 1, 10.00, 30.00, '09:15:00', '09:15:00', '2024-11-24 13:45:15'),
(5, 1, 10.00, 19.00, '10:00:00', '10:15:00', '2024-12-02 14:05:22'),
(6, 7, 20.00, 80.00, '09:00:00', '09:15:00', '2024-12-22 11:34:02'),
(7, 7, 20.00, 80.00, '17:00:00', '17:17:00', '2024-12-22 11:39:26'),
(8, 7, 40.00, 70.00, '18:20:00', '18:25:00', '2024-12-22 13:02:45'),
(9, 7, 20.00, 80.00, '09:10:00', '09:15:00', '2024-12-22 13:14:37'),
(10, 7, 20.00, 80.00, '09:10:00', '09:15:00', '2024-12-25 10:31:33'),
(11, 7, 12.00, 23.00, '19:20:00', '20:20:00', '2024-12-29 04:23:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `realtime_monitoring`
--

CREATE TABLE `realtime_monitoring` (
  `history_realtime_id` int NOT NULL,
  `device_id` int NOT NULL,
  `temperature` float(5,2) DEFAULT NULL,
  `soil_humidity` float(5,2) DEFAULT NULL,
  `light_intensity` float(5,2) DEFAULT NULL,
  `action_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `realtime_monitoring`
--

INSERT INTO `realtime_monitoring` (`history_realtime_id`, `device_id`, `temperature`, `soil_humidity`, `light_intensity`, `action_timestamp`) VALUES
(1, 1, 28.00, 70.00, 75.00, '2024-12-20 15:26:56'),
(2, 6, 29.00, 90.00, 99.00, '2024-12-22 08:04:22'),
(3, 7, 77.00, 77.00, 77.00, '2024-12-22 08:18:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int UNSIGNED NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `username`, `pass`, `email`, `created_at`) VALUES
(1, 'vallorant', '$2y$10$wUlRC0GLTiFnP9plbUMpD.xdUrQPHdn3kj1JxYPcDmGynwrMCYUly', 'p@gmail.com', '2024-11-24 00:14:00'),
(2, 'valo', '$2y$10$EEAeBPoOXxjlQ8d4Kwrrr.Hp6EH0wJ4oLTWzkHvCZO32MaXqqf2Am', 'asd@gmail.com', '2024-12-14 14:26:11'),
(3, 'asd123', '$2y$10$tvE1jZ5.gzCsuGiJurCzUOM5hYzSuAJ/eohkHu7IPyb6s9z1iscpW', 'asd123@gmail.com', '2024-12-29 04:01:13'),
(4, 'vall', '$2y$10$AREqGvhT/9my.4j59.1SnOTzaJ9BGYpQqWaXlTuPHLfwWzInh74gW', 'robotjarvis1@gmail.com', '2024-12-29 08:05:50'),
(26, '123', '$2y$10$6BHocAO.0I6jmrwKEzES..E7bDzrfpLRZGDUJWdVmhZ255CJM3S6u', 'oasiud1@gmail.com', '2024-12-29 04:03:04');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`device_id`),
  ADD KEY `device_ibfk_1` (`user_id`);

--
-- Indeks untuk tabel `histori_pemupukan`
--
ALTER TABLE `histori_pemupukan`
  ADD PRIMARY KEY (`history_pemupukan_id`),
  ADD KEY `fk_fertilizer_updated_by` (`updated_by`),
  ADD KEY `fk_fertilizer_device_id_3` (`device_id`);

--
-- Indeks untuk tabel `histori_pencahayaan`
--
ALTER TABLE `histori_pencahayaan`
  ADD PRIMARY KEY (`history_pencahayaan_id`),
  ADD KEY `fk_lighting_updated_by` (`updated_by`),
  ADD KEY `fk_lighting_device_id_1` (`device_id`);

--
-- Indeks untuk tabel `histori_penyiraman`
--
ALTER TABLE `histori_penyiraman`
  ADD PRIMARY KEY (`history_penyiraman_id`),
  ADD KEY `fk_watering_updated_by` (`updated_by`),
  ADD KEY `fk_watering_device_id` (`device_id`);

--
-- Indeks untuk tabel `pemupukan`
--
ALTER TABLE `pemupukan`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `fk_fertilizer_device_id` (`device_id`);

--
-- Indeks untuk tabel `pencahayaan`
--
ALTER TABLE `pencahayaan`
  ADD PRIMARY KEY (`lighting_id`),
  ADD KEY `fk_lighting_device_id` (`device_id`);

--
-- Indeks untuk tabel `penyiraman`
--
ALTER TABLE `penyiraman`
  ADD PRIMARY KEY (`watering_id`),
  ADD KEY `fk_device_id` (`device_id`);

--
-- Indeks untuk tabel `realtime_monitoring`
--
ALTER TABLE `realtime_monitoring`
  ADD PRIMARY KEY (`history_realtime_id`),
  ADD KEY `fk_monitoring_device_id_4` (`device_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `device`
--
ALTER TABLE `device`
  MODIFY `device_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `histori_pemupukan`
--
ALTER TABLE `histori_pemupukan`
  MODIFY `history_pemupukan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `histori_pencahayaan`
--
ALTER TABLE `histori_pencahayaan`
  MODIFY `history_pencahayaan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `histori_penyiraman`
--
ALTER TABLE `histori_penyiraman`
  MODIFY `history_penyiraman_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pemupukan`
--
ALTER TABLE `pemupukan`
  MODIFY `schedule_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pencahayaan`
--
ALTER TABLE `pencahayaan`
  MODIFY `lighting_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `penyiraman`
--
ALTER TABLE `penyiraman`
  MODIFY `watering_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `realtime_monitoring`
--
ALTER TABLE `realtime_monitoring`
  MODIFY `history_realtime_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `device`
--
ALTER TABLE `device`
  ADD CONSTRAINT `device_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `histori_pemupukan`
--
ALTER TABLE `histori_pemupukan`
  ADD CONSTRAINT `fk_fertilizer_device_id_3` FOREIGN KEY (`device_id`) REFERENCES `device` (`device_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fertilizer_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `histori_pencahayaan`
--
ALTER TABLE `histori_pencahayaan`
  ADD CONSTRAINT `fk_lighting_device_id_1` FOREIGN KEY (`device_id`) REFERENCES `device` (`device_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lighting_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `histori_penyiraman`
--
ALTER TABLE `histori_penyiraman`
  ADD CONSTRAINT `fk_watering_device_id` FOREIGN KEY (`device_id`) REFERENCES `device` (`device_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_watering_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pemupukan`
--
ALTER TABLE `pemupukan`
  ADD CONSTRAINT `fk_fertilizer_device_id` FOREIGN KEY (`device_id`) REFERENCES `device` (`device_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pencahayaan`
--
ALTER TABLE `pencahayaan`
  ADD CONSTRAINT `fk_lighting_device_id` FOREIGN KEY (`device_id`) REFERENCES `device` (`device_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penyiraman`
--
ALTER TABLE `penyiraman`
  ADD CONSTRAINT `fk_device_id` FOREIGN KEY (`device_id`) REFERENCES `device` (`device_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `realtime_monitoring`
--
ALTER TABLE `realtime_monitoring`
  ADD CONSTRAINT `fk_monitoring_device_id_4` FOREIGN KEY (`device_id`) REFERENCES `device` (`device_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
