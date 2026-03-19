-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2026 at 05:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wanderly_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `passengers` int(11) DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `mode` enum('flight','bus') DEFAULT NULL,
  `class` enum('economy','first','vip') DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_name`, `user_email`, `destination_id`, `passengers`, `travel_date`, `mode`, `class`, `total_price`, `created_at`) VALUES
(1, 'Guest User', 'guest@example.com', 2, 1, '2026-01-07', NULL, 'economy', 4444.00, '2026-01-10 00:35:58'),
(2, 'Guest User', 'guest@example.com', 2, 1, '2026-01-19', '', 'economy', 4444.00, '2026-01-10 01:06:22'),
(3, 'Guest User', 'guest@example.com', 2, 1, '2026-01-12', '', 'economy', 4444.00, '2026-01-10 01:10:46'),
(4, 'shyam', 'example@gmail.com', 2, 1, '2026-01-19', '', 'economy', 4444.00, '2026-01-10 01:34:25'),
(5, 'Anil Shrestha', 'ronitbista2@gmail.com', 2, 1, '2026-01-06', '', 'economy', 4444.00, '2026-01-10 01:43:18'),
(6, NULL, NULL, 5, 1, '2026-01-13', '', 'economy', 700000.00, '2026-01-10 03:01:27'),
(7, NULL, NULL, 4, 1, '2026-01-13', '', 'economy', 80000.00, '2026-01-10 03:02:14'),
(8, NULL, NULL, 4, 1, '2026-01-13', '', 'economy', 80000.00, '2026-01-10 03:03:02'),
(9, NULL, NULL, 4, 1, '2026-01-14', '', 'economy', 80000.00, '2026-01-10 03:09:19'),
(10, NULL, NULL, 4, 1, '2026-01-06', '', 'economy', 80000.00, '2026-01-10 03:10:29'),
(11, NULL, NULL, 4, 1, '2026-01-06', '', 'economy', 80000.00, '2026-01-10 03:14:55'),
(12, NULL, NULL, 6, 1, '2026-01-06', '', 'economy', 10000.00, '2026-01-10 03:24:19'),
(13, NULL, NULL, 7, 1, '2026-01-13', '', 'economy', 700000.00, '2026-01-10 03:29:04'),
(15, 'shyam', 'example@gmail.com', 7, 1, '2026-01-14', '', 'economy', 700000.00, '2026-01-10 03:32:22'),
(17, 'shyam', 'example@gmail.com', 7, 1, '2026-01-07', '', 'economy', 700000.00, '2026-01-10 03:32:44'),
(18, 'shyam', 'example@gmail.com', 7, 2, '2026-01-06', 'flight', 'economy', 1400000.00, '2026-01-10 03:35:57'),
(19, 'shyam', 'example@gmail.com', 10, 1, '2026-01-05', 'flight', 'economy', 50000.00, '2026-01-10 03:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `description`, `image`, `price`, `created_at`) VALUES
(2, 'b', 'akkasd', '1768001408_pexels-karola-g-5632402.jpg', 4444.00, '2026-01-09 23:30:08'),
(4, 'Ilam', 'sjlislislviasl', '1768011710_Illam.jpg', 80000.00, '2026-01-10 02:21:50'),
(5, 'Upper-mustang', 'ls;oslijaslijabsd', '1768011732_upper-mustang.jpg', 700000.00, '2026-01-10 02:22:12'),
(6, 'ram', 'nhhh', '1768015031.jpg', 10000.00, '2026-01-10 03:17:11'),
(7, 'kathmandu', 'hhhh', '1768015668.jpg', 700000.00, '2026-01-10 03:27:48'),
(9, 'pokhara', '99ealag', '1768016438.jpg', 60000.00, '2026-01-10 03:40:38'),
(10, 'Upper mustang', 'skjhksjvbkshad', '1768016471.jpg', 50000.00, '2026-01-10 03:41:11'),
(11, 'Ilam', 'skljasklhdfkha', '1768016497.jpg', 40000.00, '2026-01-10 03:41:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Ronit Bista', 'ronitbista2@gmail.com', '123456789', '2026-01-09 23:20:18');

-- --------------------------------------------------------

--
-- Table structure for table `website_users`
--

CREATE TABLE `website_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_users`
--

INSERT INTO `website_users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Anil Shrestha', 'ronitbista2@gmail.com', '$2y$10$L75IQk5dl3jJpzdS3a8Il.WSZwqNgL37QJj4cGfgkFgkXZ8SpMCua', '2026-01-10 01:25:43'),
(2, 'ronit', 'bista2@gmail.com', '$2y$10$tenghQB7kyAzGfGVINEPY.1tqxIEx.imjCb45.mofVdSS3H0LBjbq', '2026-01-10 01:27:01'),
(3, 'shyam', 'example@gmail.com', '$2y$10$z/E/Yl/.sDOp5.wP6tNDwOsOvtcq.tJQgCN0RwczYqika.0o5vBBy', '2026-01-10 01:33:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_id` (`destination_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `website_users`
--
ALTER TABLE `website_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `website_users`
--
ALTER TABLE `website_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
