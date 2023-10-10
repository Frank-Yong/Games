-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2021 at 11:55 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myfm`
--

-- --------------------------------------------------------

--
-- Table structure for table `estimatescore`
--

CREATE TABLE `estimatescore` (
  `id` int(11) NOT NULL,
  `gameid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `scor` varchar(20) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `estimatescore`
--

INSERT INTO `estimatescore` (`id`, `gameid`, `userid`, `scor`, `data`) VALUES
(1, 134, 12, '2:7', '2021-11-23 11:33:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `estimatescore`
--
ALTER TABLE `estimatescore`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `meciid` (`gameid`,`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `estimatescore`
--
ALTER TABLE `estimatescore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
