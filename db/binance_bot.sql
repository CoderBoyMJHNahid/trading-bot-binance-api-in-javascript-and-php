-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2023 at 07:46 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `binance_bot`
--

-- --------------------------------------------------------

--
-- Table structure for table `crypto`
--

CREATE TABLE `crypto` (
  `crypto_id` int(11) NOT NULL,
  `crypto_name` varchar(256) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crypto`
--

INSERT INTO `crypto` (`crypto_id`, `crypto_name`, `user`) VALUES
(6, 'FETUSDT', 4),
(7, 'CRVUSDT', 4),
(8, 'BTCUSDT', 4),
(12, 'GASBTC', 4),
(13, 'GXSETH', 4);

-- --------------------------------------------------------

--
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `history_id` int(11) NOT NULL,
  `history_date` varchar(256) NOT NULL,
  `operation` varchar(256) NOT NULL,
  `usdt_value` decimal(19,10) NOT NULL,
  `crypto_var` decimal(19,10) NOT NULL,
  `possessed_crypto` decimal(19,10) NOT NULL,
  `buy_sell_price` decimal(19,10) NOT NULL,
  `actual_value` decimal(19,10) NOT NULL,
  `crypto` varchar(256) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_data`
--

CREATE TABLE `personal_data` (
  `pid` int(11) NOT NULL,
  `username` varchar(256) NOT NULL,
  `pwd` varchar(256) NOT NULL,
  `type_user` int(11) NOT NULL,
  `budget` int(11) NOT NULL,
  `multiplicator` varchar(11) NOT NULL,
  `digit_num` int(11) NOT NULL,
  `slippage_num` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personal_data`
--

INSERT INTO `personal_data` (`pid`, `username`, `pwd`, `type_user`, `budget`, `multiplicator`, `digit_num`, `slippage_num`) VALUES
(4, 'admin', '0c7540eb7e65b553ec1ba6b20de79608', 1, 6000, '1.02', 4, '0.01'),
(5, 'user', '68f32b5f0943904f5eac13096f25d756', 0, 0, '0', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `trade`
--

CREATE TABLE `trade` (
  `trade_id` int(11) NOT NULL,
  `pricelevel` varchar(256) NOT NULL,
  `state_status` varchar(256) NOT NULL,
  `multiplicator` int(11) NOT NULL,
  `suggested_bid` decimal(19,2) NOT NULL,
  `bid` decimal(19,2) NOT NULL,
  `crypto_var` decimal(19,10) NOT NULL,
  `target_price` decimal(19,10) NOT NULL,
  `on_actual_price` decimal(19,10) NOT NULL,
  `ricavo` decimal(19,10) NOT NULL,
  `future_sell_usdt` decimal(19,10) NOT NULL,
  `crypto_received` decimal(19,10) NOT NULL,
  `crypto` varchar(256) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `crypto`
--
ALTER TABLE `crypto`
  ADD PRIMARY KEY (`crypto_id`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `personal_data`
--
ALTER TABLE `personal_data`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `trade`
--
ALTER TABLE `trade`
  ADD PRIMARY KEY (`trade_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `crypto`
--
ALTER TABLE `crypto`
  MODIFY `crypto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `personal_data`
--
ALTER TABLE `personal_data`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trade`
--
ALTER TABLE `trade`
  MODIFY `trade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=601;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
