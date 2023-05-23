CREATE TABLE `symbols` (
  `symbol_id` int(11) NOT NULL AUTO_INCREMENT,
  `name_file` varchar(255) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `size` int(3) NOT NULL DEFAULT '50',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`symbol_id`),
  UNIQUE (`name_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `keywords` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol_id` int(11) NOT NULL,
  `lang` varchar(2) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`keyword_id`),
  FOREIGN KEY (`symbol_id`) REFERENCES `symbols`(`symbol_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `lang` varchar(2) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE (`category_name`, `lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `symbol_category` (
  `symbol_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`symbol_id`, `category_id`),
  FOREIGN KEY (`symbol_id`) REFERENCES `symbols`(`symbol_id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`categorie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

