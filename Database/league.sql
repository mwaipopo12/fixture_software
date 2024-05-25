-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2024 at 06:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
--
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'Fixture Master', 'admin@gmail.com', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad');

-- --------------------------------------------------------
-- Table structure for table `day_match`
CREATE TABLE `day_match` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `number_of_matches` INT NOT NULL,
  `league_type` ENUM('Premier', 'Second', 'Single_leg', 'First') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name_league` (`name`, `league_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `exceptional_date`
CREATE TABLE `exceptional_date` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `break_type` ENUM('caf_qualifier', 'all_teams') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `fixture`
CREATE TABLE `fixture` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `home_team_id` INT DEFAULT NULL,
  `away_team_id` INT DEFAULT NULL,
  `venue_id` INT DEFAULT NULL,
  `date` DATE DEFAULT NULL,
  `time` TIME DEFAULT NULL,
  `round` INT DEFAULT NULL,
  `league_type` ENUM('Premier', 'Second', 'Single_leg', 'First') NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`home_team_id`) REFERENCES `team` (`id`),
  FOREIGN KEY (`away_team_id`) REFERENCES `team` (`id`),
  FOREIGN KEY (`venue_id`) REFERENCES `venue` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- Table structure for table `competition`
CREATE TABLE `competition` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `season` VARCHAR(100) NOT NULL,
  `league_type` ENUM('Premier', 'Second', 'Single_leg', 'First') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `team`
CREATE TABLE `team` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `region` VARCHAR(100) NOT NULL,
  `big_team` ENUM('yes', 'no') NOT NULL,
  `caf_qualifier` ENUM('yes', 'no') NOT NULL,
  `league_type` ENUM('Premier', 'Second', 'Single_leg', 'First') NOT NULL,
  `venue_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name_league` (`name`, `league_type`),
  FOREIGN KEY (`venue_id`) REFERENCES `venue` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for table `venue`
CREATE TABLE `venue` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `region` VARCHAR(100) NOT NULL,
  `quality` ENUM('light', 'no light') NOT NULL,
  `league_type` ENUM('Premier', 'Second', 'Single_leg', 'First') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name_league` (`name`, `league_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
