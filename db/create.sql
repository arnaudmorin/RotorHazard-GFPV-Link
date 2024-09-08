CREATE TABLE `events` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `locked` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci

CREATE TABLE `races` (
  `name` varchar(255) NOT NULL,
  `eventid` varchar(255) NOT NULL,
  `pilot1` varchar(255) DEFAULT NULL,
  `pilot2` varchar(255) DEFAULT NULL,
  `pilot3` varchar(255) DEFAULT NULL,
  `pilot4` varchar(255) DEFAULT NULL,
  `freq1` varchar(255) DEFAULT NULL,
  `freq2` varchar(255) DEFAULT NULL,
  `freq3` varchar(255) DEFAULT NULL,
  `freq4` varchar(255) DEFAULT NULL,
  `position1` varchar(255) DEFAULT NULL,
  `position2` varchar(255) DEFAULT NULL,
  `position3` varchar(255) DEFAULT NULL,
  `position4` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`name`,`eventid`),
  KEY `races_ibfk_1` (`eventid`),
  CONSTRAINT `races_ibfk_1` FOREIGN KEY (`eventid`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci

CREATE TABLE `ranks` (
  `eventid` varchar(255) NOT NULL,
  `pilot` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `extra` text DEFAULT NULL,
  PRIMARY KEY (`pilot`,`eventid`),
  KEY `ranks_ibfk_1` (`eventid`),
  CONSTRAINT `ranks_ibfk_1` FOREIGN KEY (`eventid`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci

CREATE TABLE `laps` (
  `eventid` varchar(255) NOT NULL,
  `pilot` varchar(255) NOT NULL,
  `laps` text DEFAULT NULL,
  PRIMARY KEY (`pilot`,`eventid`),
  KEY `laps_ibfk_1` (`eventid`),
  CONSTRAINT `laps_ibfk_1` FOREIGN KEY (`eventid`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci
