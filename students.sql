-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 12, 2024 at 02:25 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yashraj_stdbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `roll_number` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `archived` tinyint(1) DEFAULT 0,
  `active` enum('active','deactivete') DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `roll_number`, `address`, `phone_number`, `email`, `image_path`, `first_name`, `last_name`, `department`, `archived`, `active`, `is_archived`) VALUES
(10, '78', 'sdifh', '87941232', 'hitesh@gmail.com', 'images/stock.jpg', 'Hitesh', 'Patel', 'BCA', 0, NULL, 0),
(11, '12', 'asduyg', '789846513', 'harshil1@gmail.com', 'images/stock.jpg', 'Harshilasdasdad', 'Shah', 'BBA', 0, NULL, 0),
(12, '564', 'dsufgyfuogas', '8794568741', 'mahenpatel@gmail.com', NULL, 'Mahendra ', 'Patel', 'Btech', 0, NULL, 0),
(13, '675', 'asdfhfasihfd', '578954623', 'maheshp@gmail.com', 'images/stock.jpg', 'Mahesh', 'Panday', 'BBA', 0, NULL, 0),
(14, '96', 'asdfosijfd', '7897546432', 'mohini@gmail.com', 'images/stock.jpg', 'Mohini', 'Mehta', 'BBA', 0, NULL, 0),
(15, '489', 'fdgsifhoasohfd', '7894565233', 'mitesh@gmail.com', 'images/stock.jpg', 'Mitesh', 'Kaushil', 'MBA', 0, NULL, 0),
(17, '01', 'ausfdisduf', '1111111111', 'test1@gmail.com', '', 'test', 'test', 'BBA', 0, NULL, 0),
(18, '02', 'dfiugsdf', '222222222', 'test2@gmail.com', '', 'test2', 'test2', 'BBA', 0, NULL, 0),
(19, '03', 'dufgsdfug', '3333333333', 'test3@gmail.com', '', 'test3', 'test4', 'BBA', 0, NULL, 0),
(20, '04', 'asdufygsdufg', '444444444', 'test4@gmail.com', '', 'test4', 'test4', 'BBA', 0, NULL, 0),
(21, '05', 'asdyufgsduf', '5555555555', 'test5@gmail.com', '', 'test5', 'test5', 'BBA', 0, NULL, 0),
(22, '07', 'sidufh', '7777777777', 'test7@gmail.com', 'images/student.png', 'test7', 'test7', 'BBA', 0, NULL, 0),
(23, '07', 'sidufh', '7777777777', 'test7@gmail.com', 'uploads/student.png', 'test7', 'test7', 'BBA', 0, NULL, 0),
(24, '08', 'asfdygsdfyugosfd aisdfgu', '8888888888', 'test8@gmail.com', 'uploads/student.png', 'test8', 'test8', 'BBA', 0, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
