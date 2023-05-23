CREATE TABLE `languages` (
  `language_code` varchar(2) NOT NULL,
  `language_name` varchar(255) NOT NULL,
  PRIMARY KEY (`language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `symbols` (
  `symbol_id` int NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `size` int DEFAULT NULL,
  `active` boolean DEFAULT 0,
  `deleted` boolean DEFAULT 0,
  PRIMARY KEY (`symbol_id`),
  UNIQUE (`file_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `keywords` (
  `keyword_id` int NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `language_code` varchar(2) NOT NULL,
  PRIMARY KEY (`keyword_id`),
  FOREIGN KEY (`language_code`) REFERENCES `languages`(`language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
--   a rajouter si l'ont souhaite optimiser l'ordre dans lequel les catégories apparaissent
--   `category_order` int DEFAULT NULL, 
  `language_code` varchar(2) NOT NULL,
  PRIMARY KEY (`category_id`),
  FOREIGN KEY (`language_code`) REFERENCES `languages`(`language_code`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `symbol_keyword` (
  `symbol_id` int NOT NULL,
  `keyword_id` int NOT NULL,
  PRIMARY KEY (`symbol_id`, `keyword_id`),
  FOREIGN KEY (`symbol_id`) REFERENCES `symbols`(`symbol_id`),
  FOREIGN KEY (`keyword_id`) REFERENCES `keywords`(`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `symbol_category` (
  `symbol_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`symbol_id`, `category_id`),
  FOREIGN KEY (`symbol_id`) REFERENCES `symbols`(`symbol_id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `languages` (`language_code`, `language_name`) VALUES
('EN', 'Anglais'),
('DE', 'Allemand'),
('ES', 'Espagnol'),
('FR', 'Français'),
('IT', 'Italien'),
('PT', 'Portugais');
