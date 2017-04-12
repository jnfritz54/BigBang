
CREATE TABLE IF NOT EXISTS `Stars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `systeme` int(11) DEFAULT NULL,
  `typeOrigine` varchar(1) NOT NULL,
  `periode` varchar(100) DEFAULT NULL,
  `typeSurcharge` varchar(2) DEFAULT NULL,
  `age` float NOT NULL,
  `masseOrigine` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
