-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 03, 2011 at 10:08 PM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE IF NOT EXISTS `alerts` (
  `alertId` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `alert` varchar(50) NOT NULL,
  `towhom` varchar(10) NOT NULL,
  `void` varchar(10) NOT NULL,
  PRIMARY KEY (`alertId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`alertId`, `gameid`, `alert`, `towhom`, `void`) VALUES
(18, 8, 'You sunk Atish Destroyer', 'def123', 'void');

-- --------------------------------------------------------

--
-- Table structure for table `challenge`
--

CREATE TABLE IF NOT EXISTS `challenge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(10) NOT NULL,
  `challengedby` varchar(10) NOT NULL,
  `challengedto` varchar(10) NOT NULL,
  `challenge` int(11) NOT NULL,
  `ingame` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `challenge`
--


-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE IF NOT EXISTS `game` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `gameId` int(11) NOT NULL,
  `player1` varchar(20) NOT NULL,
  `player2` varchar(20) NOT NULL,
  `cell0_0` varchar(40) DEFAULT '0_0',
  `cell0_1` varchar(40) DEFAULT '0_0',
  `cell0_2` varchar(40) DEFAULT '0_0',
  `cell0_3` varchar(40) DEFAULT '0_0',
  `cell0_4` varchar(40) DEFAULT '0_0',
  `cell0_5` varchar(40) DEFAULT '0_0',
  `cell0_6` varchar(40) DEFAULT '0_0',
  `cell0_7` varchar(40) DEFAULT '0_0',
  `cell0_8` varchar(40) DEFAULT '0_0',
  `cell0_9` varchar(40) DEFAULT '0_0',
  `cell1_0` varchar(40) DEFAULT '0_0',
  `cell1_1` varchar(40) DEFAULT '0_0',
  `cell1_2` varchar(40) DEFAULT '0_0',
  `cell1_3` varchar(40) DEFAULT '0_0',
  `cell1_4` varchar(40) DEFAULT '0_0',
  `cell1_5` varchar(40) DEFAULT '0_0',
  `cell1_6` varchar(40) DEFAULT '0_0',
  `cell1_7` varchar(40) DEFAULT '0_0',
  `cell1_8` varchar(40) DEFAULT '0_0',
  `cell1_9` varchar(40) DEFAULT '0_0',
  `cell2_0` varchar(40) DEFAULT '0_0',
  `cell2_1` varchar(40) DEFAULT '0_0',
  `cell2_2` varchar(40) DEFAULT '0_0',
  `cell2_3` varchar(40) DEFAULT '0_0',
  `cell2_4` varchar(40) DEFAULT '0_0',
  `cell2_5` varchar(40) DEFAULT '0_0',
  `cell2_6` varchar(40) DEFAULT '0_0',
  `cell2_7` varchar(40) DEFAULT '0_0',
  `cell2_8` varchar(40) DEFAULT '0_0',
  `cell2_9` varchar(40) DEFAULT '0_0',
  `cell3_0` varchar(40) DEFAULT '0_0',
  `cell3_1` varchar(40) DEFAULT '0_0',
  `cell3_2` varchar(40) DEFAULT '0_0',
  `cell3_3` varchar(40) DEFAULT '0_0',
  `cell3_4` varchar(40) DEFAULT '0_0',
  `cell3_5` varchar(40) DEFAULT '0_0',
  `cell3_6` varchar(40) DEFAULT '0_0',
  `cell3_7` varchar(40) DEFAULT '0_0',
  `cell3_8` varchar(40) DEFAULT '0_0',
  `cell3_9` varchar(40) DEFAULT '0_0',
  `cell4_0` varchar(40) DEFAULT '0_0',
  `cell4_1` varchar(40) DEFAULT '0_0',
  `cell4_2` varchar(40) DEFAULT '0_0',
  `cell4_3` varchar(40) DEFAULT '0_0',
  `cell4_4` varchar(40) DEFAULT '0_0',
  `cell4_5` varchar(40) DEFAULT '0_0',
  `cell4_6` varchar(40) DEFAULT '0_0',
  `cell4_7` varchar(40) DEFAULT '0_0',
  `cell4_8` varchar(40) DEFAULT '0_0',
  `cell4_9` varchar(40) DEFAULT '0_0',
  `cell5_0` varchar(40) DEFAULT '0_0',
  `cell5_1` varchar(40) DEFAULT '0_0',
  `cell5_2` varchar(40) DEFAULT '0_0',
  `cell5_3` varchar(40) DEFAULT '0_0',
  `cell5_4` varchar(40) DEFAULT '0_0',
  `cell5_5` varchar(40) DEFAULT '0_0',
  `cell5_6` varchar(40) DEFAULT '0_0',
  `cell5_7` varchar(40) DEFAULT '0_0',
  `cell5_8` varchar(40) DEFAULT '0_0',
  `cell5_9` varchar(40) DEFAULT '0_0',
  `cell6_0` varchar(40) DEFAULT '0_0',
  `cell6_1` varchar(40) DEFAULT '0_0',
  `cell6_2` varchar(40) DEFAULT '0_0',
  `cell6_3` varchar(40) DEFAULT '0_0',
  `cell6_4` varchar(40) DEFAULT '0_0',
  `cell6_5` varchar(40) DEFAULT '0_0',
  `cell6_6` varchar(40) DEFAULT '0_0',
  `cell6_7` varchar(40) DEFAULT '0_0',
  `cell6_8` varchar(40) DEFAULT '0_0',
  `cell6_9` varchar(40) DEFAULT '0_0',
  `cell7_0` varchar(40) DEFAULT '0_0',
  `cell7_1` varchar(40) DEFAULT '0_0',
  `cell7_2` varchar(40) DEFAULT '0_0',
  `cell7_3` varchar(40) DEFAULT '0_0',
  `cell7_4` varchar(40) DEFAULT '0_0',
  `cell7_5` varchar(40) DEFAULT '0_0',
  `cell7_6` varchar(40) DEFAULT '0_0',
  `cell7_7` varchar(40) DEFAULT '0_0',
  `cell7_8` varchar(40) DEFAULT '0_0',
  `cell7_9` varchar(40) DEFAULT '0_0',
  `cell8_0` varchar(40) DEFAULT '0_0',
  `cell8_1` varchar(40) DEFAULT '0_0',
  `cell8_2` varchar(40) DEFAULT '0_0',
  `cell8_3` varchar(40) DEFAULT '0_0',
  `cell8_4` varchar(40) DEFAULT '0_0',
  `cell8_5` varchar(40) DEFAULT '0_0',
  `cell8_6` varchar(40) DEFAULT '0_0',
  `cell8_7` varchar(40) DEFAULT '0_0',
  `cell8_8` varchar(40) DEFAULT '0_0',
  `cell8_9` varchar(40) DEFAULT '0_0',
  `cell9_0` varchar(40) DEFAULT '0_0',
  `cell9_1` varchar(40) DEFAULT '0_0',
  `cell9_2` varchar(40) DEFAULT '0_0',
  `cell9_3` varchar(40) DEFAULT '0_0',
  `cell9_4` varchar(40) DEFAULT '0_0',
  `cell9_5` varchar(40) DEFAULT '0_0',
  `cell9_6` varchar(40) DEFAULT '0_0',
  `cell9_7` varchar(40) DEFAULT '0_0',
  `cell9_8` varchar(40) DEFAULT '0_0',
  `cell9_9` varchar(40) DEFAULT '0_0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `game`
--


-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `Userid` varchar(20) NOT NULL,
  `Message_Content` text NOT NULL,
  `Message_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `player_game`
--

CREATE TABLE IF NOT EXISTS `player_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_gameid` int(11) NOT NULL,
  `player1` varchar(11) NOT NULL,
  `player2` varchar(11) NOT NULL,
  `turn` varchar(11) NOT NULL,
  `flag` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `player_game`
--

INSERT INTO `player_game` (`id`, `player_gameid`, `player1`, `player2`, `turn`, `flag`) VALUES
(1, 8, 'def123', 'abc12', 'abc12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `player_ships_pos`
--

CREATE TABLE IF NOT EXISTS `player_ships_pos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` varchar(10) NOT NULL,
  `aircraft` varchar(20) NOT NULL,
  `battleship` varchar(20) NOT NULL,
  `submarine` varchar(20) NOT NULL,
  `cruiser` varchar(20) NOT NULL,
  `destroyer` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `player_ships_pos`
--


-- --------------------------------------------------------

--
-- Table structure for table `shiporientation`
--

CREATE TABLE IF NOT EXISTS `shiporientation` (
  `shipsorientID` int(11) NOT NULL AUTO_INCREMENT,
  `playerid` varchar(20) NOT NULL,
  `aircraft` varchar(20) NOT NULL,
  `battleship` varchar(20) NOT NULL,
  `submarine` varchar(20) NOT NULL,
  `cruiser` varchar(20) NOT NULL,
  `destroyer` varchar(20) NOT NULL,
  PRIMARY KEY (`shipsorientID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `shiporientation`
--

INSERT INTO `shiporientation` (`shipsorientID`, `playerid`, `aircraft`, `battleship`, `submarine`, `cruiser`, `destroyer`) VALUES
(1, 'def123', 'false', 'false', 'true', 'true', 'true'),
(2, 'abc12', 'true', 'true', 'false', 'false', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `ships`
--

CREATE TABLE IF NOT EXISTS `ships` (
  `gameid` int(11) NOT NULL,
  `playerid` varchar(20) NOT NULL,
  `aircraft` int(11) NOT NULL,
  `battleship` int(11) NOT NULL,
  `submarine` int(11) NOT NULL,
  `cruiser` int(11) NOT NULL,
  `destroyer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ships`
--

INSERT INTO `ships` (`gameid`, `playerid`, `aircraft`, `battleship`, `submarine`, `cruiser`, `destroyer`) VALUES
(8, 'def123', 5, 4, 3, 3, 2),
(8, 'abc12', 5, 4, 3, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE IF NOT EXISTS `userinfo` (
  `userId` varchar(8) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Firstname` varchar(20) NOT NULL,
  `Lastname` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Timestamp` varchar(30) NOT NULL,
  `Token` varchar(50) NOT NULL,
  `logged` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userinfo`
--

INSERT INTO `userinfo` (`userId`, `Password`, `Firstname`, `Lastname`, `Email`, `Timestamp`, `Token`, `logged`) VALUES
('abc12', '3ef10af53040bdc894556fc4cd2c1a0ffacaa13f', 'Atish', 'Shinde', 'aatish.shinde@gmail.com', '10/16/11', '235529039901f01916d40', '0'),
('def123', '589c22335a381f122d129225f5c0ba3056ed5811', 'def', 'def', 'def@gmail.com', '11/03/11', '8102761231f0191aee7', '0'),
('dan', '2591e5f46f28d303f9dc027d475a5c60d8dea17a', 'dan', '', 'dan@rit.edu', '11/16/11', '172311f0191b3fb', '0');
