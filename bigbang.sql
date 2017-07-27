-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 20, 2017 at 10:43 AM
-- Server version: 5.5.55-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `perso`
--

-- --------------------------------------------------------

--
-- Table structure for table `Lifeforms`
--

CREATE TABLE IF NOT EXISTS `Lifeforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `originPlanet` int(11) NOT NULL,
  `originSystem` int(11) NOT NULL,
  `sensPrincipal` varchar(50) NOT NULL,
  `appendice` varchar(50) DEFAULT NULL,
  `nombreMembres` int(11) NOT NULL,
  `epiderme` tinyint(4) NOT NULL,
  `regime` tinyint(2) NOT NULL,
  `governement` varchar(100) DEFAULT NULL,
  `dureeVie` int(11) DEFAULT NULL,
  `milieu` varchar(50) NOT NULL,
  `avancement` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `originSystem` (`originSystem`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=980 ;

-- --------------------------------------------------------

--
-- Table structure for table `Planetes`
--

CREATE TABLE IF NOT EXISTS `Planetes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `systeme` int(11) NOT NULL,
  `objectOrbited` int(11) DEFAULT NULL,
  `type` varchar(3) NOT NULL,
  `sousType` int(11) NOT NULL,
  `masse` float NOT NULL,
  `particularite` varchar(50) DEFAULT NULL,
  `distanceEtoile` float NOT NULL,
  `inclinaisonOrbite` float NOT NULL,
  `dureeAnnee` float NOT NULL COMMENT '(en jours)',
  `dureeJour` float DEFAULT NULL,
  `albedo` float NOT NULL,
  `rayonnement` float DEFAULT NULL,
  `hydrometrie` int(11) DEFAULT NULL,
  `eden` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7835594 ;

-- --------------------------------------------------------

--
-- Table structure for table `Stars`
--

CREATE TABLE IF NOT EXISTS `Stars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `systeme` int(11) DEFAULT NULL,
  `distanceBarycentre` float NOT NULL DEFAULT '0',
  `typeOrigine` varchar(1) NOT NULL,
  `periode` varchar(100) DEFAULT NULL,
  `typeSurcharge` varchar(2) DEFAULT NULL,
  `age` float NOT NULL,
  `masseOrigine` float NOT NULL,
  `rayonnement` float DEFAULT NULL,
  `rayon` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `systeme` (`systeme`),
  KEY `systeme_2` (`systeme`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1801358 ;

-- --------------------------------------------------------

--
-- Table structure for table `Systemes`
--

CREATE TABLE IF NOT EXISTS `Systemes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `angle` float NOT NULL,
  `distance` float NOT NULL,
  `altitude` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000001 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
