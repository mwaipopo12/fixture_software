
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: league
--

-- --------------------------------------------------------

--
-- Table structure for table fixture_admin
--

CREATE TABLE fixture_admin (
  admin_id int  primary key auto_increment,
  admin_name varchar(200) NOT NULL,
  admin_email varchar(200) NOT NULL,
  admin_pwd varchar(200) NOT NULL
  
  
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table fixture_admin
--

INSERT INTO fixture_admin ( admin_name, admin_email, admin_pwd) VALUES
( 'Fixture Master', 'admin@gmail.com', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad');

-- --------------------------------------------------------

--
-- Table structure for table stadium
--


create table stadium (
    id int primary key auto_increment,
    name varchar(100),
    stadium_year int (11) NOT NULL,
    stadium_region varchar(100) NOT NULL
    
);


--
-- Dumping data for table stadium


insert into stadium (name,stadium_year, stadium_region) values
('Mkapa/Lupaso','2006','Dar es salaam'),
('Chamanzi', '2013','Dar es salaam'),
('Uhuru', '1962','Dar es salaam'),
('Manungu', '2006', 'Morogoro'),
('Kaitaba','2003','Kagera'),
('Sokoine','2008','Mbeya'),
('Mkwakwani', '2002','Tanga'),
('Liti', '2018','Singida'),
('Jamuhuri','2020','Dodoma'),
('Mbalali','2021','mbeya');

-- -- --------------------------------------------------------

--
-- Table structure for table member
--

CREATE TABLE member (
  member_id int primary key auto_increment,
  member_name varchar(200) NOT NULL,
  member_phone varchar(200) NOT NULL,
  member_dob varchar(200) NOT NULL,
  member_email varchar(200) NOT NULL,
  member_pwd varchar(200) NOT NULL,
  member_dpic varchar(200) NOT NULL
  
);

--
-- Dumping data for table member
--

INSERT INTO member ( member_name, member_phone, member_dob, member_email, member_pwd, member_dpic) VALUES
( 'Martin Emanuel','0712506810', '12.07.1983', 'martinkija.j@gmail.com', '3b20aa38c0656c77419e5aea2549206c9d53cc14', 'CRMS-C-VWBQ-8426 '),
( 'Atupele Mwamingi', '0763892381', '12.07.1983', 'atumwamingi@gmail.com', '4fd98a218aabd00990c1ac4882630dfcaf0fdb91','CRMS-C-LGVI-2974 '),
( 'Evodia Nestory', '0679053529', '12.07.1973', 'evodia@gmail.com', '2fde37a443fa570c74a2b7f8182662d8ba3825a8', 'CRMS-C-UTLD-4057'),
( 'Happyness Shao', '0712826782', '12.07.1963', 'happyness@gmail.com', '63982e54a7aeb0d89910475ba6dbd3ca6dd4e5a1','CRMS-C-WJYQ-9524 '),
( 'Emanuel Mwaipopo',  '0765432456', '12.07.179', 'mwaipopo@gmail.com', '1f82ea75c5cc526729e2d581aeb3aeccfef4407e', 'CRMS-C-THFX-6351 ');

-- -- --------------------------------------------------------
--
-- Table structure for table referee
--

CREATE TABLE referee (
  id int primary key auto_increment,
  name varchar(200) NOT NULL,
  referee_phone varchar(200) NOT NULL,
  referee_dob varchar(200) NOT NULL,
  referee_email varchar(200) NOT NULL,
  referee_pwd varchar(200) NOT NULL,
  referee_dpic varchar(200) NOT NULL
  
);

--
-- Dumping data for table referee
--

INSERT INTO referee ( name, referee_phone, referee_dob, referee_email, referee_pwd, referee_dpic) VALUES
( 'Emanuel','0712506810', '12.07.1983', 'martinkija.j@gmail.com', '3b20aa38c0656c77419e5aea2549206c9d53cc14', 'CRMS-C-VWBQ-8426 '),
( 'Mwamingi', '0763892381', '12.07.1983', 'atumwamingi@gmail.com', '4fd98a218aabd00990c1ac4882630dfcaf0fdb91','CRMS-C-LGVI-2974 '),
( 'Evodia', '0679053529', '12.07.1973', 'evodia@gmail.com', '2fde37a443fa570c74a2b7f8182662d8ba3825a8', 'CRMS-C-UTLD-4057 '),
( 'Happyness ', '0712826782', '12.07.1963', 'happyness@gmail.com', '63982e54a7aeb0d89910475ba6dbd3ca6dd4e5a1','CRMS-C-WJYQ-9524 '),
( 'Nwaipopo',  '0765432456', '12.07.179', 'mwaipopo@gmail.com', '1f82ea75c5cc526729e2d581aeb3aeccfef4407e', 'CRMS-C-THFX-6351 ');


--
-- Table structure for table team
--


create table  team (
    id int primary key auto_increment,
    name varchar(100),
    team_region varchar(200) NOT NULL,
    team_email varchar(200) NOT NULL,
    team_pwd varchar(200) NOT NULL,
    team_logo varchar(200) NOT NULL,
    stadium_id int,
    constraint FK_team_stadium foreign key (stadium_id) references stadium(id)

);
--
-- Dumping data for table referee
--

insert into team (name,team_region, team_email, team_pwd, team_logo,stadium_id)values 
('Simba', 'Dar es salaam','kmc@gmail.com', '1e9ec10c3a9dd60267b61c2060b0b350319336d5', 'b.jpg',1),
('Azam','Dar es salaam','kmc@gmail.com', '1e9ec10c3a9dd60267b61c2060b0b350319336d5', 'b.jpg',2),
('Kmc', 'Dar es salaam','kmc@gmail.com', '1e9ec10c3a9dd60267b61c2060b0b350319336d5', 'b.jpg',3),
('Mtimbwa','Morogoro', 'mtibwa@gmail.com','90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', 'a.jpg',4),
('Kagera', 'Kagera','kagera@gmail.com', '1e9ec10c3a9dd60267b61c2060b0b350319336d5', 'b.jpg',5),
('Prison', 'Mbeya','prison@gmail.com', '1e9ec10c3a9dd60267b61c2060b0b350319336d5', 'b.jpg',6),
('Coast', 'Tanga','coast@gmail.com', '1e9ec10c3a9dd60267b61c2060b0b350319336d5', 'b.jpg',7),
('Singida' ,'Liti','singida@gmail.com', '1e9ec10c3a9dd60267b61c2060b0b350319336d5', 'b.jpg',8),
('Dodoma', 'Dodoma','dodoma@gmail.com', '1e9ec10c3a9dd60267b61c2060b0b350319336d5', 'b.jpg',9),
('Ihefu', 'Mbeya','ihefu@gmail.com', '1e9ec10c3a9dd60267b61c2060b0b350319336d5', 'b.jpg',10);


--
-- Table structure for table fixture
--

create table fixture (
    id int primary key auto_increment,
    home_team_id int,
    away_team_id int,
    referee_id int,
    stadium_id int,
    date date,
    time time,
    constraint FK_fixture_home_team foreign key (home_team_id) references team(id),
    constraint FK_fixture_away_team foreign key (away_team_id) references team(id),
    constraint FK_fixture_referee foreign key (referee_id) references referee(id),
    constraint FK_fixture_stadium foreign key (stadium_id) references stadium (id)
);

--
-- Table structure for table pwd_resets
--

CREATE TABLE pwd_resets (
  id int(20)  PRIMARY KEY NOT NULL,
  email varchar(200) NOT NULL,
  token longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table pwd_resets
--

INSERT INTO pwd_resets (id, email, token) VALUES
(1, 'admin@gmail.com', '9LFPQX4TCGYM2JBHE0Z1');


-- --------------------------------------------------------

--
-- Table structure for table password_resets
--

CREATE TABLE password_resets (
  email varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  token varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for table pwd_resets
--
ALTER TABLE pwd_resets ADD PRIMARY KEY ('id');

--
-- Indexes for table password_resets
--
ALTER TABLE password_resets ADD KEY 'password_resets_email_index' ('email');





/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
