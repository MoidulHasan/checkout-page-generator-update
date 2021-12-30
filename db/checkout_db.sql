-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2021 at 06:46 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `checkout_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkout_file`
--

CREATE TABLE `checkout_file` (
  `file_id` int(20) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `PAYMENT_URL` varchar(255) NOT NULL,
  `CORP_ID` varchar(255) NOT NULL,
  `API_USERNAME` varchar(255) NOT NULL,
  `API_PASSWORD` varchar(255) NOT NULL,
  `AGENT_ID` varchar(255) NOT NULL,
  `PAYMENT_TYPE` varchar(255) NOT NULL,
  `PRODUCT_CODE` varchar(255) NOT NULL,
  `PAYMENT_PROCESS` varchar(255) NOT NULL,
  `SOURCE` varchar(255) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `plan_type` varchar(100) NOT NULL,
  `Yearly_Payment_Fee` varchar(10) NOT NULL,
  `Monthly_Payment_Fee` varchar(10) NOT NULL,
  `Yearly_payment_special` text NOT NULL,
  `Enrollment_Fee` varchar(5) NOT NULL,
  `thank_you_page_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` int(10) NOT NULL,
  `coupon_code` varchar(100) NOT NULL,
  `coupon_value` varchar(100) NOT NULL,
  `coupon_message` text NOT NULL,
  `checkout_file_id` int(10) NOT NULL,
  `coupon_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(5) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_password`) VALUES
(1, 'admin', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkout_file`
--
ALTER TABLE `checkout_file`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkout_file`
--
ALTER TABLE `checkout_file`
  MODIFY `file_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
