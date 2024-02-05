-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2024 at 11:48 AM
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
-- Database: `homemade_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `ShopID` int(11) DEFAULT NULL,
  `CategoryName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `ShopID`, `CategoryName`) VALUES
(1, 1, 'Car'),
(2, 1, 'Kitchen Products');

-- --------------------------------------------------------

--
-- Table structure for table `homefoodshops`
--

CREATE TABLE `homefoodshops` (
  `ShopID` int(11) NOT NULL,
  `ManagerID` int(11) DEFAULT NULL,
  `ShopName` varchar(255) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `ApprovalStatus` enum('Approved','Disapproved') DEFAULT 'Disapproved',
  `ShopPicture` varchar(255) DEFAULT NULL,
  `ContactNumber` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homefoodshops`
--

INSERT INTO `homefoodshops` (`ShopID`, `ManagerID`, `ShopName`, `Location`, `ApprovalStatus`, `ShopPicture`, `ContactNumber`) VALUES
(1, 1, 'Nasir Shop', 'Near Multan Road', 'Approved', 'shop_pictures/WhatsApp Image 2024-01-26 at 10.27.21 PM.jpeg', '31765268270'),
(2, 2, 'Haider Shop', 'lahore', 'Approved', 'shop_pictures/WhatsApp Image 2024-01-26 at 9.25.10 PM.jpeg', '3176526827');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemID` int(11) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `ShopID` int(11) DEFAULT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemID`, `CategoryID`, `ShopID`, `ItemName`, `Price`, `Picture`) VALUES
(1, 1, 1, 'Car Good', 4000.00, 'uploads/WhatsApp Image 2024-01-25 at 1.23.04 PM.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `manager_id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`manager_id`, `Name`, `Email`, `Password`) VALUES
(1, 'NASIR', 'ASAD@gmail.com', '$2y$10$vQjGumFzx3G3fM1gPhJEh.1hTWYlOYpj.lW9x8VkoskofYfcJ2Exi'),
(2, 'NASIR12', 'abbas@gmail.com', '$2y$10$ycTn6k5KdyV5ntLtj50i4.Q426QCeCXlc1Ip21zN93kWJVvfaLqu2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `age`) VALUES
(3, 'Ml12', '$2y$10$h4W/HLHVRCzG9UQY7Z/jkeeQsLuJmfSic7aRVBSN4rSHoK/GRqRcO', 'nasiryt.827@gmail.com', '3176526827', 23);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD KEY `ShopID` (`ShopID`);

--
-- Indexes for table `homefoodshops`
--
ALTER TABLE `homefoodshops`
  ADD PRIMARY KEY (`ShopID`),
  ADD KEY `ManagerID` (`ManagerID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `CategoryID` (`CategoryID`),
  ADD KEY `ShopID` (`ShopID`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `homefoodshops`
--
ALTER TABLE `homefoodshops`
  MODIFY `ShopID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`ShopID`) REFERENCES `homefoodshops` (`ShopID`);

--
-- Constraints for table `homefoodshops`
--
ALTER TABLE `homefoodshops`
  ADD CONSTRAINT `homefoodshops_ibfk_1` FOREIGN KEY (`ManagerID`) REFERENCES `managers` (`manager_id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`ShopID`) REFERENCES `homefoodshops` (`ShopID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
