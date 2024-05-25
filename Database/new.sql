CREATE TABLE `competition` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `season` varchar(100) NOT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



CREATE TABLE `day_match` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `number_of_matches` int(11) NOT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT NULL,
  UNIQUE KEY `unique_name_league` (`name`, `league_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `big_team` enum('yes','no') NOT NULL,
  `caf_qualifier` enum('yes','no') NOT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT NULL,
  `venue_id` int(11) NOT NULL,
  UNIQUE KEY `unique_name_league` (`name`, `league_type`),
  FOREIGN KEY (`venue_id`) REFERENCES `venue`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `venue` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `quality` enum('light','no light') NOT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT NULL,
  UNIQUE KEY `unique_name_league` (`name`, `league_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE `exceptional_date` (
  `id` int(11) NOT  NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `break_type` enum('caf_qualifier','all_teams') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `fixture` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `home_team_id` int(11) DEFAULT NULL,
  `away_team_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `league_type` enum('Premier','Second','Single_leg','First') NOT NULL,
  FOREIGN KEY (`home_team_id`) REFERENCES `team`(`id`),
  FOREIGN KEY (`away_team_id`) REFERENCES `team`(`id`),
  FOREIGN KEY (`venue_id`) REFERENCES `venue`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


