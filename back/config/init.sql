-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 09 juin 2023 à 13:02
-- Version du serveur :  10.6.5-MariaDB
-- Version de PHP : 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `illustrator2`
--
-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4;


--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`category_id`, `category`, `order`) VALUES
(5, 'Mécanique', 5),
(6, 'Médical', 4),
(7, 'Musique et son', 3),
(8, 'Nature', 2),
(9, 'Noël', 1),
(10, 'Nourriture et Boisson', 6),
(11, 'Objet', 7),
(12, 'Photo - vidéo', 8),
(13, 'Sécurité', 9),
(14, 'Signe et Symbole', 10),
(15, 'Sport', 11),
(16, 'Symboles', 12),
(17, 'Temps - météo', 13),
(18, 'Transport', 14),
(19, 'Vacances', 15),
(20, 'Animal', 16),
(21, 'Anniversaire', 17),
(22, 'Arme', 18),
(23, 'Bâtiment et pays', 19),
(24, 'Boîte', 20),
(25, 'Bulle de parole', 21),
(26, 'Carte et localisation', 22),
(27, 'Communication et Réseau', 23),
(28, 'Doigt', 24),
(29, 'École', 25),
(30, 'Enfant', 26),
(31, 'Entreprise et Finance', 27),
(32, 'Espace', 28),
(33, 'Événement', 29),
(34, 'Fichier', 30),
(35, 'Flèche', 31),
(36, 'Format de fichier', 32),
(37, 'Habille et accessoire', 33),
(38, 'Humain', 34),
(39, 'Illustrations pour gobelets', 35),
(40, 'Industrie', 36),
(41, 'Informatique', 37),
(42, 'Insectes', 38),
(43, 'Maison', 39),
(44, 'Mariage', 40);


-- --------------------------------------------------------

--
-- Structure de la table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
CREATE TABLE IF NOT EXISTS `keywords` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(100) NOT NULL,
  PRIMARY KEY (`keyword_id`),
  UNIQUE `keyword` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `keywords`
--

INSERT INTO `keywords` (`keyword_id`, `keyword`) VALUES
(5, 'chien'),
(6, 'mignon'),
(7, 'berger allemand'),
(8, 'BMW'),
(9, 'voiture'),
(10, 'automobile');

-- --------------------------------------------------------

--
-- Structure de la table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `language_code` varchar(2) NOT NULL,
  `language_name` varchar(50) NOT NULL,
  PRIMARY KEY (`language_code`),
  UNIQUE `language_code` (`language_code`),
  UNIQUE `language_name` (`language_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `languages`
--

INSERT INTO `languages` (`language_code`, `language_name`) VALUES
('DE', 'Allemand'),
('EN', 'Anglais'),
('ES', 'Espagnol'),
('FR', 'Français'),
('IT', 'Italien'),
('PT', 'Portugais');

-- --------------------------------------------------------

--
-- Structure de la table `symbols`
--

DROP TABLE IF EXISTS `symbols`;
CREATE TABLE IF NOT EXISTS `symbols` (
  `symbol_id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(8) NOT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `symbol_name` varchar(100) NOT NULL,
  `size` int(11) DEFAULT 50,
  `active` tinyint(1) DEFAULT 0,
  `deleted` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`symbol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;


--
-- Déchargement des données de la table `symbols`
--

INSERT INTO `symbols` (`symbol_id`, `unique_id`, `file_name`, `symbol_name`, `size`, `active`, `deleted`) VALUES
(3, 'f4ze6Sg7', 'f4ze6Sg7-3', 'Neuneu', 60, 0, 0),
(5, 'Ma5q6ze7', 'Ma5q6ze7-5', 'img 5', 50, 1, 0),
(22, '9S3alE0q', '9S3alE0q-22', 'illsutration22', 70, 0, 0),
(27, 'la3S8zP6', 'la3S8zP6-27', 'Noel', 65, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `symbol_category`
--

DROP TABLE IF EXISTS `symbol_category`;
CREATE TABLE IF NOT EXISTS `symbol_category` (
  `symbol_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `symbol_id` (`symbol_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `symbol_category`
--

INSERT INTO `symbol_category` (`symbol_id`, `category_id`) VALUES
(3, 20),
(3, 8),
(5, 5),
(5, 8),
(5, 15),
(22, 12),
(22, 40),
(3, 21),
(27, 5),
(27, 8),
(3, 23),
(3, 22);

-- --------------------------------------------------------

--
-- Structure de la table `symbol_keyword`
--

DROP TABLE IF EXISTS `symbol_keyword`;
CREATE TABLE IF NOT EXISTS `symbol_keyword` (
  `symbol_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  KEY `symbol_id` (`symbol_id`),
  KEY `keyword_id` (`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `symbol_keyword`
--

INSERT INTO `symbol_keyword` (`symbol_id`, `keyword_id`) VALUES
(3, 5),
(3, 6),
(5, 8),
(5, 9),
(5, 10),
(22, 10),
(27, 10),
(3, 7),
(22, 5);

-- --------------------------------------------------------

--
-- Structure de la table `translations`
--

DROP TABLE IF EXISTS `translations`;
CREATE TABLE IF NOT EXISTS `translations` (
  `table_name` varchar(50) NOT NULL,
  `row_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `language_code` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Structure de la table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Déchargement des données de la table `users`
INSERT INTO `users` (`user_id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@example.com', 'admin');


--
-- Déchargement des données de la table `translations`
--

INSERT INTO `translations` (`table_name`, `row_id`, `value`, `language_code`) VALUES
('categories', 5, 'Mechanical', 'EN'),
('categories', 5, 'Mechanik', 'DE'),
('categories', 5, 'Mecánica', 'ES'),
('categories', 5, 'Mécanique', 'FR'),
('categories', 5, 'Meccanica', 'IT'),
('categories', 5, 'Mecânica', 'PT'),
('categories', 6, 'Medical', 'EN'),
('categories', 6, 'Medizinisch', 'DE'),
('categories', 6, 'Médico', 'ES'),
('categories', 6, 'Médical', 'FR'),
('categories', 6, 'Medico', 'IT'),
('categories', 6, 'Médico', 'PT'),
('categories', 7, 'Music and Sound', 'EN'),
('categories', 7, 'Musik und Ton', 'DE'),
('categories', 7, 'Música y Sonido', 'ES'),
('categories', 7, 'Musique et son', 'FR'),
('categories', 7, 'Musica e suono', 'IT'),
('categories', 7, 'Música e Som', 'PT'),
('categories', 8, 'Nature', 'EN'),
('categories', 8, 'Natur', 'DE'),
('categories', 8, 'Naturaleza', 'ES'),
('categories', 8, 'Nature', 'FR'),
('categories', 8, 'Natura', 'IT'),
('categories', 8, 'Natureza', 'PT'),
('categories', 9, 'Christmas', 'EN'),
('categories', 9, 'Weihnachten', 'DE'),
('categories', 9, 'Navidad', 'ES'),
('categories', 9, 'Noël', 'FR'),
('categories', 9, 'Natale', 'IT'),
('categories', 9, 'Natal', 'PT'),
('categories', 10, 'Food and Drink', 'EN'),
('categories', 10, 'Essen und Trinken', 'DE'),
('categories', 10, 'Comida y Bebida', 'ES'),
('categories', 10, 'Nourriture et Boisson', 'FR'),
('categories', 10, 'Cibo e Bevande', 'IT'),
('categories', 10, 'Comida e Bebida', 'PT'),
('categories', 11, 'Object', 'EN'),
('categories', 11, 'Objekt', 'DE'),
('categories', 11, 'Objeto', 'ES'),
('categories', 11, 'Objet', 'FR'),
('categories', 11, 'Oggetto', 'IT'),
('categories', 11, 'Objeto', 'PT'),
('categories', 12, 'Photo - Video', 'EN'),
('categories', 12, 'Foto - Video', 'DE'),
('categories', 12, 'Foto - Vídeo', 'ES'),
('categories', 12, 'Photo - Vidéo', 'FR'),
('categories', 12, 'Foto - Video', 'IT'),
('categories', 12, 'Foto - Vídeo', 'PT'),
('categories', 13, 'Safety', 'EN'),
('categories', 13, 'Sicherheit', 'DE'),
('categories', 13, 'Seguridad', 'ES'),
('categories', 13, 'Sécurité', 'FR'),
('categories', 13, 'Sicurezza', 'IT'),
('categories', 13, 'Segurança', 'PT'),
('categories', 14, 'Sign and Symbol', 'EN'),
('categories', 14, 'Zeichen und Symbol', 'DE'),
('categories', 14, 'Signo y Símbolo', 'ES'),
('categories', 14, 'Signe et Symbole', 'FR'),
('categories', 14, 'Segno e Simbolo', 'IT'),
('categories', 14, 'Sinal e Símbolo', 'PT'),
('categories', 15, 'Sport', 'EN'),
('categories', 15, 'Sport', 'DE'),
('categories', 15, 'Deporte', 'ES'),
('categories', 15, 'Sport', 'FR'),
('categories', 15, 'Sport', 'IT'),
('categories', 15, 'Esporte', 'PT'),
('categories', 16, 'Symbols', 'EN'),
('categories', 16, 'Symbole', 'DE'),
('categories', 16, 'Símbolos', 'ES'),
('categories', 16, 'Symboles', 'FR'),
('categories', 16, 'Simboli', 'IT'),
('categories', 16, 'Símbolos', 'PT'),
('categories', 17, 'Weather', 'EN'),
('categories', 17, 'Wetter', 'DE'),
('categories', 17, 'Tiempo - Clima', 'ES'),
('categories', 17, 'Temps - Météo', 'FR'),
('categories', 17, 'Tempo - Meteo', 'IT'),
('categories', 17, 'Tempo - Clima', 'PT'),
('categories', 18, 'Transportation', 'EN'),
('categories', 18, 'Transport', 'DE'),
('categories', 18, 'Transporte', 'ES'),
('categories', 18, 'Transport', 'FR'),
('categories', 18, 'Trasporto', 'IT'),
('categories', 18, 'Transporte', 'PT'),
('categories', 19, 'Holidays', 'EN'),
('categories', 19, 'Urlaub', 'DE'),
('categories', 19, 'Vacaciones', 'ES'),
('categories', 19, 'Vacances', 'FR'),
('categories', 19, 'Vacanze', 'IT'),
('categories', 19, 'Férias', 'PT'),
('categories', 20, 'Animal', 'EN'),
('categories', 20, 'Tier', 'DE'),
('categories', 20, 'Animal', 'ES'),
('categories', 20, 'Animal', 'FR'),
('categories', 20, 'Animale', 'IT'),
('categories', 20, 'Animal', 'PT'),
('categories', 21, 'Birthday', 'EN'),
('categories', 21, 'Geburtstag', 'DE'),
('categories', 21, 'Cumpleaños', 'ES'),
('categories', 21, 'Anniversaire', 'FR'),
('categories', 21, 'Compleanno', 'IT'),
('categories', 21, 'Aniversário', 'PT'),
('categories', 22, 'Weapon', 'EN'),
('categories', 22, 'Waffe', 'DE'),
('categories', 22, 'Arma', 'ES'),
('categories', 22, 'Arme', 'FR'),
('categories', 22, 'Arma', 'IT'),
('categories', 22, 'Arma', 'PT'),
('categories', 23, 'Building and Country', 'EN'),
('categories', 23, 'Gebäude und Land', 'DE'),
('categories', 23, 'Edificio y País', 'ES'),
('categories', 23, 'Bâtiment et pays', 'FR'),
('categories', 23, 'Edificio e Paese', 'IT'),
('categories', 23, 'Edifício e País', 'PT'),
('categories', 24, 'Box', 'EN'),
('categories', 24, 'Kiste', 'DE'),
('categories', 24, 'Caja', 'ES'),
('categories', 24, 'Boîte', 'FR'),
('categories', 24, 'Scatola', 'IT'),
('categories', 24, 'Caixa', 'PT'),
('categories', 25, 'Speech Bubble', 'EN'),
('categories', 25, 'Sprechblase', 'DE'),
('categories', 25, 'Bocadillo de Diálogo', 'ES'),
('categories', 25, 'Bulle de parole', 'FR'),
('categories', 25, 'Fumetto', 'IT'),
('categories', 25, 'Balão de Fala', 'PT'),
('categories', 26, 'Map and Location', 'EN'),
('categories', 26, 'Karte und Standort', 'DE'),
('categories', 26, 'Mapa y Localización', 'ES'),
('categories', 26, 'Carte et localisation', 'FR'),
('categories', 26, 'Mappa e Posizione', 'IT'),
('categories', 26, 'Mapa e Localização', 'PT'),
('categories', 27, 'Communication and Network', 'EN'),
('categories', 27, 'Kommunikation und Netzwerk', 'DE'),
('categories', 27, 'Comunicación y Red', 'ES'),
('categories', 27, 'Communication et Réseau', 'FR'),
('categories', 27, 'Comunicazione e Rete', 'IT'),
('categories', 27, 'Comunicação e Rede', 'PT'),
('categories', 28, 'Finger', 'EN'),
('categories', 28, 'Finger', 'DE'),
('categories', 28, 'Dedo', 'ES'),
('categories', 28, 'Doigt', 'FR'),
('categories', 28, 'Dito', 'IT'),
('categories', 28, 'Dedo', 'PT'),
('categories', 29, 'School', 'EN'),
('categories', 29, 'Schule', 'DE'),
('categories', 29, 'Escuela', 'ES'),
('categories', 29, 'École', 'FR'),
('categories', 29, 'Scuola', 'IT'),
('categories', 29, 'Escola', 'PT'),
('categories', 30, 'Child', 'EN'),
('categories', 30, 'Kind', 'DE'),
('categories', 30, 'Niño', 'ES'),
('categories', 30, 'Enfant', 'FR'),
('categories', 30, 'Bambino', 'IT'),
('categories', 30, 'Criança', 'PT'),
('categories', 31, 'Business and Finance', 'EN'),
('categories', 31, 'Unternehmen und Finanzen', 'DE'),
('categories', 31, 'Empresa y Finanzas', 'ES'),
('categories', 31, 'Entreprise et Finance', 'FR'),
('categories', 31, 'Business e Finanza', 'IT'),
('categories', 31, 'Negócios e Finanças', 'PT'),
('categories', 32, 'Space', 'EN'),
('categories', 32, 'Raum', 'DE'),
('categories', 32, 'Espacio', 'ES'),
('categories', 32, 'Espace', 'FR'),
('categories', 32, 'Spazio', 'IT'),
('categories', 32, 'Espaço', 'PT'),
('categories', 33, 'Event', 'EN'),
('categories', 33, 'Ereignis', 'DE'),
('categories', 33, 'Evento', 'ES'),
('categories', 33, 'Événement', 'FR'),
('categories', 33, 'Evento', 'IT'),
('categories', 33, 'Evento', 'PT'),
('categories', 34, 'File', 'EN'),
('categories', 34, 'Datei', 'DE'),
('categories', 34, 'Archivo', 'ES'),
('categories', 34, 'Fichier', 'FR'),
('categories', 34, 'File', 'IT'),
('categories', 34, 'Arquivo', 'PT'),
('categories', 35, 'Arrow', 'EN'),
('categories', 35, 'Pfeil', 'DE'),
('categories', 35, 'Flecha', 'ES'),
('categories', 35, 'Flèche', 'FR'),
('categories', 35, 'Freccia', 'IT'),
('categories', 35, 'Seta', 'PT'),
('categories', 36, 'File Format', 'EN'),
('categories', 36, 'Dateiformat', 'DE'),
('categories', 36, 'Formato de archivo', 'ES'),
('categories', 36, 'Format de fichier', 'FR'),
('categories', 36, 'Formato di file', 'IT'),
('categories', 36, 'Formato de arquivo', 'PT'),
('categories', 37, 'Clothing and Accessory', 'EN'),
('categories', 37, 'Kleidung und Zubehör', 'DE'),
('categories', 37, 'Ropa y Accesorio', 'ES'),
('categories', 37, 'Habille et accessoire', 'FR'),
('categories', 37, 'Abbigliamento e Accessorio', 'IT'),
('categories', 37, 'Vestuário e Acessório', 'PT'),
('categories', 38, 'Human', 'EN'),
('categories', 38, 'Mensch', 'DE'),
('categories', 38, 'Humano', 'ES'),
('categories', 38, 'Humain', 'FR'),
('categories', 38, 'Umano', 'IT'),
('categories', 38, 'Humano', 'PT'),
('categories', 39, 'Cup Illustrations', 'EN'),
('categories', 39, 'Becher Illustrationen', 'DE'),
('categories', 39, 'Ilustraciones de Tazas', 'ES'),
('categories', 39, 'Illustrations pour gobelets', 'FR'),
('categories', 39, 'Illustrazioni per Tazze', 'IT'),
('categories', 39, 'Ilustrações para Copos', 'PT'),
('categories', 40, 'Industry', 'EN'),
('categories', 40, 'Industrie', 'DE'),
('categories', 40, 'Industria', 'ES'),
('categories', 40, 'Industrie', 'FR'),
('categories', 40, 'Industria', 'IT'),
('categories', 40, 'Indústria', 'PT'),
('categories', 41, 'Computing', 'EN'),
('categories', 41, 'Informatik', 'DE'),
('categories', 41, 'Informática', 'ES'),
('categories', 41, 'Informatique', 'FR'),
('categories', 41, 'Informatica', 'IT'),
('categories', 41, 'Informática', 'PT'),
('categories', 42, 'Insects', 'EN'),
('categories', 42, 'Insekten', 'DE'),
('categories', 42, 'Insectos', 'ES'),
('categories', 42, 'Insectes', 'FR'),
('categories', 42, 'Insetti', 'IT'),
('categories', 42, 'Insetos', 'PT'),
('categories', 43, 'House', 'EN'),
('categories', 43, 'Haus', 'DE'),
('categories', 43, 'Casa', 'ES'),
('categories', 43, 'Maison', 'FR'),
('categories', 43, 'Casa', 'IT'),
('categories', 43, 'Casa', 'PT'),
('categories', 44, 'Wedding', 'EN'),
('categories', 44, 'Hochzeit', 'DE'),
('categories', 44, 'Boda', 'ES'),
('categories', 44, 'Mariage', 'FR'),
('categories', 44, 'Matrimonio', 'IT'),
('categories', 44, 'Casamento', 'PT'),
('keywords', 5, 'dog', 'EN'),
('keywords', 5, 'Hund', 'DE'),
('keywords', 5, 'perro', 'ES'),
('keywords', 5, 'chien', 'FR'),
('keywords', 5, 'cane', 'IT'),
('keywords', 5, 'cão', 'PT'),
('keywords', 6, 'cute', 'EN'),
('keywords', 6, 'süß', 'DE'),
('keywords', 6, 'bonito', 'ES'),
('keywords', 6, 'mignon', 'FR'),
('keywords', 6, 'carino', 'IT'),
('keywords', 6, 'fofo', 'PT'),
('keywords', 7, 'German Shepherd', 'EN'),
('keywords', 7, 'Deutscher Schäferhund', 'DE'),
('keywords', 7, 'Pastor Alemán', 'ES'),
('keywords', 7, 'Berger allemand', 'FR'),
('keywords', 7, 'Pastore tedesco', 'IT'),
('keywords', 7, 'Pastor Alemão', 'PT'),
('keywords', 8, 'BMW', 'EN'),
('keywords', 8, 'BMW', 'DE'),
('keywords', 8, 'BMW', 'ES'),
('keywords', 8, 'BMW', 'FR'),
('keywords', 8, 'BMW', 'IT'),
('keywords', 8, 'BMW', 'PT'),
('keywords', 9, 'car', 'EN'),
('keywords', 9, 'Auto', 'DE'),
('keywords', 9, 'coche', 'ES'),
('keywords', 9, 'voiture', 'FR'),
('keywords', 9, 'auto', 'IT'),
('keywords', 9, 'carro', 'PT'),
('keywords', 10, 'automobile', 'EN'),
('keywords', 10, 'Automobil', 'DE'),
('keywords', 10, 'automóvil', 'ES'),
('keywords', 10, 'automobile', 'FR'),
('keywords', 10, 'automobile', 'IT'),
('keywords', 10, 'automóvel', 'PT');


--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `symbol_category`
--
ALTER TABLE `symbol_category`
  ADD CONSTRAINT `symbol_category_ibfk_1` FOREIGN KEY (`symbol_id`) REFERENCES `symbols` (`symbol_id`),
  ADD CONSTRAINT `symbol_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Contraintes pour la table `symbol_keyword`
--
ALTER TABLE `symbol_keyword`
  ADD CONSTRAINT `symbol_keyword_ibfk_1` FOREIGN KEY (`symbol_id`) REFERENCES `symbols` (`symbol_id`),
  ADD CONSTRAINT `symbol_keyword_ibfk_2` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`keyword_id`);

--
-- Contraintes pour la table `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`language_code`) REFERENCES `languages` (`language_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
