CREATE TABLE `events` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `locked` varchar(255) NOT NULL DEFAULT 'no',
  `archived` varchar(255) NOT NULL DEFAULT 'no',
  `type` varchar(255) DEFAULT NULL,
  `current_heat_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci

CREATE TABLE `heats` (
      `id` int(10) NOT NULL,
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
      PRIMARY KEY (`id`,`eventid`),
      KEY `heats_ibfk_1` (`eventid`),
      CONSTRAINT `heats_ibfk_1` FOREIGN KEY (`eventid`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci

CREATE TABLE `rounds` (
      `id` int(10) NOT NULL,
      `heat_id` int(10) NOT NULL,
      `eventid` varchar(255) NOT NULL,
      `pilot` varchar(255) NOT NULL,
      `laps` text DEFAULT NULL,
      `position` int(10) DEFAULT 0,
      PRIMARY KEY (`id`,`heat_id`,`eventid`, `pilot`),
      KEY `rounds_ibfk_1` (`eventid`),
      KEY `rounds_ibfk_2` (`heat_id`),
      CONSTRAINT `rounds_ibfk_1` FOREIGN KEY (`eventid`) REFERENCES `events` (`id`) ON DELETE CASCADE,
      CONSTRAINT `rounds_ibfk_2` FOREIGN KEY (`heat_id`) REFERENCES `heats` (`id`) ON DELETE CASCADE
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
