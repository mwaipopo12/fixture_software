-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2024 at 12:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


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

--
-- Table structure for table `competition`
--

CREATE TABLE `competition` (
  `id` int(11) NOT  AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `season` varchar(100) NOT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `competition`
--

INSERT INTO `competition` (`id`, `name`, `start_date`, `end_date`, `season`, `league_type`) VALUES
(1, 'NBC ', '2024-08-23', '2025-05-24', '2024/2025', 'Premier'),
(2, 'CHAMPIONSHIP', '2024-09-07', '2025-05-04', '2024/2025', 'Second'),
(3, 'FA cup', '2024-12-12', '2024-12-15', '2024/2025', 'Single_leg');

-- --------------------------------------------------------

--
-- Table structure for table `day_match`
--


CREATE TABLE `day_match` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `number_of_matches` int(11) NOT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT NULL,
  UNIQUE KEY `unique_name_league` (`name`, `league_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `day_match`
--

INSERT INTO `day_match` (`id`, `name`, `number_of_matches`, `league_type`) VALUES
(1, 'Friday',2, 'Premier'),
(2, 'Saturday',3, 'Premier'),
(3, 'Sunday',3, 'Premier'),
(4, 'Monday',1, 'Premier'),
(5, 'Saturday',4, 'Second'),
(6, 'Sunday',3, 'Second'),
(7, 'Monday',2, 'First'),
(8, 'Sunday',2, 'First'),
(9, 'Wednesday', 8, 'Single_leg');

-- --------------------------------------------------------

--
-- Table structure for table `exceptional_date`
--

CREATE TABLE `exceptional_date` (
  `id` int(11) NOT  NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `break_type` enum('caf_qualifier','all_teams') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `exceptional_date`
--

INSERT INTO `exceptional_date` (`id`, `name`, `start_date`, `end_date`, `break_type`) VALUES
(1, 'CAF IC Preliminary Round first leg ', '2024-08-18', '2024-08-20', 'caf_qualifier'),
(2, 'CAF IC Preliminary Round Second leg', '2024-08-25', '2024-08-27', 'caf_qualifier'),
(3, 'CAF IC Second  Preliminary Round First Leg', '2024-09-15', '2024-09-17', 'caf_qualifier'),
(4, 'CHAN 2024 Qualifier First Round', '2024-09-22', '2024-09-24', 'all_teams'),
(5, 'CAF Second Preliminary Second Leg', '2024-09-29', '2024-10-01', 'caf_qualifier'),
(6, 'CHAN 2024 Qualifiers First Round Second Leg', '2024-10-06', '2024-10-08', 'all_teams'),
(7, 'AFCON Qualifier Guinea 2025', '2024-11-13', '2024-11-21', 'all_teams'),
(8, 'CAF IC MD 1 Group Stage', '2024-11-24', '2024-11-26', 'caf_qualifier'),
(9, 'CAF IC MD 2 Group Stage', '2024-12-01', '2024-12-03', 'caf_qualifier'),
(10, 'CAF IC MD 3 Group Stage', '2024-12-08', '2024-12-10', 'caf_qualifier'),
(11, 'Federation Cup Round 64 ', '2024-12-12', '2024-12-15', 'all_teams'),
(12, 'CHAN Qualifiers Second Round First Leg', '2024-12-15', '2024-12-17', 'all_teams'),
(13, 'CAF IC MD 4 Group Stage', '2024-12-19', '2024-12-20', 'caf_qualifier'),
(14, 'CHAN Qualifier Second Round Second Leg', '2024-12-22', '2024-12-24', 'all_teams'),
(15, 'Mapinduzi Cup ', '2025-01-01', '2025-01-13', 'all_teams'),
(16, 'AFCON Finals 2023 Ivory coast', '2025-01-13', '2025-02-11', 'all_teams'),
(17, 'Federation Cup Round 32', '2025-02-20', '2025-02-21', 'all_teams'),
(18, 'CAF IC MD 5 Group Stage', '2025-02-23', '2025-02-25', 'caf_qualifier'),
(19, 'CAF IC MD 6 Group Stage', '2025-03-01', '2025-03-03', 'caf_qualifier'),
(20, 'CHAN Qualifiers Third Round First Leg', '2025-03-08', '2025-03-10', 'all_teams'),
(21, 'CHAN Qualifiers Third Round Second Leg', '2025-03-15', '2025-03-17', 'all_teams'),
(22, 'Federation CUP Round 16', '2025-04-02', '2025-04-04', 'all_teams'),
(23, 'Federation CUP Quarter Finals', '2025-04-23', '2025-04-24', 'all_teams');

-- --------------------------------------------------------

--
-- Table structure for table `fixture`
--

CREATE TABLE `fixture` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `home_team_name` int(11) DEFAULT NULL,
  `away_team_name` int(11) DEFAULT NULL,
  `venue_name` int(11) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--
CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `big_team` enum('yes','no') NOT NULL,
  `caf_qualifier` enum('yes','no') NOT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT NULL,
  `venue_name` varchar(255) NOT NULL ,
   UNIQUE KEY `unique_name_league` (`name`, `league_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `region`, `big_team`, `caf_qualifier`, `league_type`, `venue_id`) VALUES
(2, 'Young Africa SC', 'Dar es Salaam', 'yes', 'yes', 'Premier', 'Mkapa Stadium '),
(3, 'Simba SC ', 'Dar es Salaam', 'yes', 'yes', 'Premier', 'Mkapa Stadium '),
(4, 'Azam FC', 'Dar es Salaam', 'yes', 'yes', 'Premier', 'Azam Complex'),
(6, 'KMC FC', 'Dar es Salaam', 'no', 'no', 'Premier', 'Uhuru Stadium'),
(7, 'Coastal Union FC', 'Tanga', 'no', 'no', 'Premier', 'Mkwakwani Stadium'),
(8, 'Ihefu Fc', 'Mbeya', 'no', 'no', 'Premier', 'Highland Estates'),
(9, 'Mtibwa FC', 'Morogoro', 'no', 'no', 'Premier', 'Manungu Stadium'),
(10, 'Kagera Sugar FC', 'Kagera', 'no', 'no', 'Premier', 'Kaitaba Stadium'),
(11, 'Geita Gold FC ', 'Geita', 'no', 'no', 'Premier', 'Nyankumbu Stadium'),
(12, 'Prison FC', 'Mbeya', 'no', 'no', 'Premier', 'Sokoine Stadium'),
(13, 'Singida Fountain Gate FC', 'Singida', 'no', 'yes', 'Premier', 'Liti Stadium'),
(14, 'Dodoma Mji FC', 'Dodoma', 'no', 'no', 'Premier', 'Jamuhuri  Stadium'),
(15, 'Mashujaa FC', 'Kigoma', 'no', 'no', 'First', 'Lake Tanganyika '),
(16, 'Tabora United FC', 'Tabora', 'no', 'no', 'Premier', 'Ali Hassan Mwinyi'),
(17, 'JKT FC', 'Arusha', 'no', 'no', 'Premier', 'Black Rhino '),
(18, 'Namungo FC', 'Lindi', 'no', 'no', 'Premier', 'Majaliwa Stadium'),
(19, 'Simba', 'Dar es Salaam', 'no', 'no', 'Second', 'Mkapa Stadium'),
(20, 'Azam FC', 'Dar es Salaam', 'no', 'no', 'Single_leg','Azam Complex');


-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `quality` enum('light','no light') NOT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT ,
   UNIQUE KEY `unique_name_league` (`name`, `league_type`),
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`id`, `name`, `region`, `quality`, `league_type`) VALUES
(1, 'Mkapa Stadium ', 'Dar es Salaam', 'light', 'Premier'),
(2, 'Uhuru Stadium', 'Dar es Salaam', 'light', 'Premier'),
(3, 'Azam Complex', 'Dar es Salaam', 'light', 'Premier'),
(4, 'Manungu Stadium', 'Morogoro', 'no light', 'Premier'),
(5, 'Highland Estates', 'Mbeya', 'no light', 'Premier'),
(6, 'Majaliwa Stadium', 'Lindi', 'light', 'Prier'),
(7, 'Jamhuri  Stadium', 'Dodoma', 'light', 'Premier'),
(8, 'Lake Tanganyika ', 'Kigoma', 'no light', 'Pemremier'),
(9, 'Liti Stadium', 'Singida', 'no light', 'Premier'),
(10, 'Ali Hassan Mwinyi', 'Tabora', 'no light', 'Premier'),
(11, 'Kaitaba Stadium', 'Kagera', 'light', 'Premier'),
(12, 'Black Rhino ', 'Arusha', 'no light', 'Premier'),
(13, 'Nyankumbu Stadium', 'Geita', 'no light', 'Premier'),
(14, 'Mkwakwani Stadium', 'Tanga', 'light', 'Premier'),
(15, 'Sokoine Stadium', 'Mbeya', 'no light', 'Premier'),
(16, 'Sokoine Stadium', 'Mbeya', 'no light', 'Second'),
(17, 'Jamhuri Stadium', 'Morogoro', 'no light', 'First'),
(18, 'Jamhuri Stadium', 'Tanga', 'no light', 'Single_leg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competition`
--
ALTER TABLE `competition`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `day_match`
--
ALTER TABLE `day_match`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `exceptional_date`
--
ALTER TABLE `exceptional_date`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fixture`
--
ALTER TABLE `fixture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_fixture_home_team` (`home_team_name`),
  ADD KEY `FK_fixture_away_team` (`away_team_name`),
  ADD KEY `FK_fixture_venue_name` (`venue_name`),
  ADD KEY `FK_fixture_region` (`region`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `FK_team_venue_name` (`venue_name`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_region` (`region`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `competition`
--
ALTER TABLE `competition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `day_match`
--
ALTER TABLE `day_match`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `exceptional_date`
--
ALTER TABLE `exceptional_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `fixture`
--
ALTER TABLE `fixture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `venue`
--
ALTER TABLE `venue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
