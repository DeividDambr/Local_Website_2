-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2023 at 05:27 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `20202359_kursinis`
--

-- --------------------------------------------------------

--
-- Table structure for table `dalykai`
--

CREATE TABLE `dalykai` (
  `id` int(11) NOT NULL,
  `vartotojo_id` int(11) NOT NULL,
  `pavadinimas` varchar(250) NOT NULL,
  `tipas` tinyint(1) NOT NULL COMMENT '0 yra preke, 1 yra paslauga'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dalykai`
--

INSERT INTO `dalykai` (`id`, `vartotojo_id`, `pavadinimas`, `tipas`) VALUES
(1, 1, 'Proteinas', 0),
(2, 1, 'Bananai', 0),
(3, 1, 'Gymas', 0),
(4, 2, 'Bananai', 0),
(5, 3, 'hostingas', 1);

-- --------------------------------------------------------

--
-- Table structure for table `dalykai_info`
--

CREATE TABLE `dalykai_info` (
  `dalykai_id` int(11) NOT NULL,
  `metai` year(4) NOT NULL,
  `menuo` tinyint(4) NOT NULL,
  `kaina` float NOT NULL,
  `kiekis` int(11) NOT NULL,
  `kiekio_tipas` varchar(100) DEFAULT NULL,
  `vnt_kaina` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dalykai_info`
--

INSERT INTO `dalykai_info` (`dalykai_id`, `metai`, `menuo`, `kaina`, `kiekis`, `kiekio_tipas`, `vnt_kaina`) VALUES
(1, 2022, 6, 15, 1, 'kg', 15),
(1, 2022, 7, 15, 1, 'kg', 15),
(2, 2022, 5, 5.49, 6, 'kg', 0.915),
(2, 2022, 6, 5.49, 6, 'kg', 0.915),
(2, 2022, 7, 5.49, 6, 'kg', 0.915),
(3, 2022, 1, 68, 1, 'abonimentas', 68),
(3, 2022, 2, 68, 1, 'abonimentas', 68),
(3, 2022, 3, 65, 1, 'abonimentas', 65),
(3, 2022, 4, 65, 1, 'abonimentas', 65),
(3, 2022, 5, 65, 1, 'abonimentas', 65),
(3, 2022, 6, 65, 1, 'abonimentas', 65),
(3, 2022, 7, 70, 1, 'abonimentas', 70),
(3, 2022, 8, 70, 1, 'abonimentas', 70),
(3, 2022, 9, 75, 1, 'abonimentas', 75),
(3, 2022, 10, 75, 1, 'abonimentas', 75),
(3, 2022, 11, 75, 1, 'abonimentas', 75),
(3, 2022, 12, 75, 1, 'abonimentas', 75),
(4, 2022, 1, 8.49, 10, 'kg', 0.849),
(4, 2022, 2, 8.49, 10, 'kg', 0.849),
(4, 2022, 3, 8.49, 10, 'kg', 0.849),
(4, 2022, 4, 7.99, 9, 'kg', 0.887778),
(4, 2022, 5, 7.99, 9, 'kg', 0.887778),
(4, 2022, 6, 7.99, 9, 'kg', 0.887778),
(4, 2022, 7, 5.49, 4, 'kg', 1.3725),
(4, 2022, 8, 5.49, 4, 'kg', 1.3725),
(4, 2022, 9, 5.49, 4, 'kg', 1.3725),
(4, 2022, 10, 5.49, 4, 'kg', 1.3725),
(4, 2022, 11, 5.49, 4, 'kg', 1.3725),
(4, 2022, 12, 5.49, 4, 'kg', 1.3725),
(5, 2022, 8, 30, 1, NULL, 30),
(5, 2022, 9, 32, 1, NULL, 32),
(5, 2022, 10, 32, 1, NULL, 32),
(5, 2022, 11, 35, 1, NULL, 35),
(5, 2022, 12, 35, 1, NULL, 35);

-- --------------------------------------------------------

--
-- Table structure for table `vartotojai`
--

CREATE TABLE `vartotojai` (
  `id` int(11) NOT NULL,
  `vardas` varchar(40) NOT NULL,
  `pastas` varchar(320) NOT NULL,
  `slaptazodis` varchar(100) NOT NULL,
  `adminas` tinyint(1) NOT NULL DEFAULT 0,
  `prisijungimo_laikas` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Laikas, kada vartotojas prisijunge paskutini karta',
  `sukurimo_data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vartotojai`
--

INSERT INTO `vartotojai` (`id`, `vardas`, `pastas`, `slaptazodis`, `adminas`, `prisijungimo_laikas`, `sukurimo_data`) VALUES
(0, 'HeadAdmin', '', '$2y$10$ee9VjlJpSZRVdBh3vDo0feIqf6h68ONxk2MjpzMGsMwQgiKZ4gabm', 1, '2023-01-12 14:32:40', '2023-01-12 15:17:44'),
(1, 'buldozeris', 'destrojeris3000@gmail.com', '$2y$10$FOBGPrrT8e0mYbFp9/14UuUItCTH8K4ZBorem19M8SZFMSkIRloAW', 0, '2023-01-12 16:20:47', '2023-01-12 16:24:22'),
(2, 'testeris', 'testeris@gmail.com', '$2y$10$2KM4jY3hMMHkovJ2zA/ioOYQXe.VGsV3cFjaLcgn5AUjls3sc.Lxe', 0, '2023-01-12 14:32:21', '2023-01-12 16:29:07'),
(3, 'admin', 'admin@protonmail.com', '$2y$10$XUtw7QT2LBmykjX.PfaEo.JyNydvWVFvoaDFU74Vvkkpw47345PU6', 1, '2023-01-12 16:23:47', '2023-01-12 16:32:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dalykai`
--
ALTER TABLE `dalykai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vartotojo_id` (`vartotojo_id`);

--
-- Indexes for table `dalykai_info`
--
ALTER TABLE `dalykai_info`
  ADD PRIMARY KEY (`dalykai_id`,`metai`,`menuo`),
  ADD KEY `dalyko_id` (`dalykai_id`);

--
-- Indexes for table `vartotojai`
--
ALTER TABLE `vartotojai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`vardas`),
  ADD UNIQUE KEY `email` (`pastas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dalykai`
--
ALTER TABLE `dalykai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vartotojai`
--
ALTER TABLE `vartotojai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dalykai`
--
ALTER TABLE `dalykai`
  ADD CONSTRAINT `dalykai_ibfk_1` FOREIGN KEY (`vartotojo_id`) REFERENCES `vartotojai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dalykai_info`
--
ALTER TABLE `dalykai_info`
  ADD CONSTRAINT `dalykai_info_ibfk_1` FOREIGN KEY (`dalykai_id`) REFERENCES `dalykai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
