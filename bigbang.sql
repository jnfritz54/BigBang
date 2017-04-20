
CREATE TABLE IF NOT EXISTS `Stars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `systeme` int(11) DEFAULT NULL,
  `typeOrigine` varchar(1) NOT NULL,
  `periode` varchar(100) DEFAULT NULL,
  `typeSurcharge` varchar(2) DEFAULT NULL,
  `age` float NOT NULL,
  `masseOrigine` float NOT NULL,
  `rayonnement` float DEFAULT NULL,
  `rayon` FLOAT NOT NULL ;
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `Systemes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `angle` float NOT NULL,
  `distance` float NOT NULL,
  `altitude` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS `Planetes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `systeme` int(11) NOT NULL,
  `objectOrbited` int(11) DEFAULT NULL,
  `type` varchar(3) NOT NULL,
  `masse` float NOT NULL,
  `particularite` varchar(50) DEFAULT NULL,
  `distanceEtoile` float NOT NULL,
  `dureeJour` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
