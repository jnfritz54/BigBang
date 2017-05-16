
-- --------------------------------------------------------

--
-- Table structure for table `Planetes`
--

CREATE TABLE IF NOT EXISTS `Planetes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `systeme` int(11) NOT NULL,
  `objectOrbited` int(11) DEFAULT NULL,
  `type` varchar(3) NOT NULL,
  `masse` float NOT NULL,
  `particularite` varchar(50) DEFAULT NULL,
  `distanceEtoile` float NOT NULL,
  `inclinaisonOrbite` float NOT NULL,
  `dureeAnnee` float NOT NULL COMMENT '(en jours)',
  `dureeJour` float DEFAULT NULL,
  `albedo` float NOT NULL,
  `rayonnement` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  KEY `systeme` (`systeme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
