-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: mysql.illinoistrackclub.com
-- Generation Time: Apr 13, 2016 at 08:33 PM
-- Server version: 5.6.28-log
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `illinitrackclub_agprojec`
--
CREATE DATABASE IF NOT EXISTS `illinitrackclub_agprojec` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `illinitrackclub_agprojec`;

-- --------------------------------------------------------

--
-- Table structure for table `records_events`
--

CREATE TABLE `records_events` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `distance` int(11) NOT NULL,
  `season` varchar(16) NOT NULL,
  `relay` tinyint(1) NOT NULL,
  `record_w_id` int(11) NOT NULL,
  `record_w_date` date NOT NULL,
  `record_m_id` int(11) NOT NULL,
  `record_m_date` date NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `records_meets`
--

CREATE TABLE `records_meets` (
  `id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `season` varchar(16) NOT NULL,
  `name` varchar(256) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `host` varchar(256) NOT NULL,
  `location` varchar(128) NOT NULL,
  `course` varchar(128) NOT NULL,
  `resultsURL` varchar(256) NOT NULL,
  `photosURL` varchar(256) NOT NULL,
  `splitsURL` varchar(256) NOT NULL,
  `notes` tinytext NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `records_people`
--

CREATE TABLE `records_people` (
  `id` int(11) NOT NULL,
  `first` varchar(128) NOT NULL,
  `last` varchar(128) NOT NULL,
  `sex` varchar(1) NOT NULL,
  `gradyr` year(4) NOT NULL,
  `email` varchar(128) NOT NULL,
  `alumni` tinyint(1) NOT NULL DEFAULT '0',
  `elite` tinyint(1) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `records_performances`
--

CREATE TABLE `records_performances` (
  `id` int(11) NOT NULL,
  `name_id` int(4) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `year` int(4) NOT NULL,
  `season` varchar(8) NOT NULL,
  `date` date NOT NULL,
  `meet_id` int(4) NOT NULL,
  `event_id` int(3) NOT NULL,
  `seconds` int(5) NOT NULL DEFAULT '0',
  `ms` int(2) NOT NULL DEFAULT '0',
  `mark` decimal(5,2) NOT NULL DEFAULT '0.00',
  `wind` tinyint(1) NOT NULL DEFAULT '0',
  `postgrad` tinyint(1) NOT NULL DEFAULT '0',
  `unattached` tinyint(1) NOT NULL DEFAULT '0',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `records_events`
--
ALTER TABLE `records_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records_meets`
--
ALTER TABLE `records_meets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records_people`
--
ALTER TABLE `records_people`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records_performances`
--
ALTER TABLE `records_performances`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `records_events`
--
ALTER TABLE `records_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5262;
--
-- AUTO_INCREMENT for table `records_meets`
--
ALTER TABLE `records_meets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;
--
-- AUTO_INCREMENT for table `records_people`
--
ALTER TABLE `records_people`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=585;
--
-- AUTO_INCREMENT for table `records_performances`
--
ALTER TABLE `records_performances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5289;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
