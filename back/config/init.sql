-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 21 juil. 2023 à 11:47
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
  UNIQUE KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`category_id`, `category`, `order`) VALUES
(5, 'Mécanique', 11),
(6, 'Médical', 10),
(7, 'Musique et son', 4),
(8, 'Nature', 8),
(9, 'Noël', 40),
(10, 'Nourriture et Boisson', 9),
(11, 'Objet', 12),
(12, 'Photo - vidéo', 5),
(13, 'Sécurité', 13),
(14, 'Signe et Symbole', 3),
(15, 'Sport', 14),
(16, 'Symboles', 2),
(17, 'Temps - météo', 15),
(18, 'Transport', 16),
(19, 'Vacances', 17),
(20, 'Animal', 6),
(21, 'Anniversaire', 18),
(22, 'Arme', 19),
(23, 'Bâtiment et pays', 20),
(24, 'Boîte', 21),
(25, 'Bulle de parole', 22),
(26, 'Carte et localisation', 23),
(27, 'Communication et Réseau', 24),
(28, 'Doigt', 25),
(29, 'École', 27),
(30, 'Enfant', 28),
(31, 'Entreprise et Finance', 29),
(32, 'Espace', 30),
(33, 'Événement', 31),
(34, 'Fichier', 32),
(35, 'Flèche', 33),
(36, 'Format de fichier', 34),
(37, 'Habille et accessoire', 35),
(38, 'Humain', 36),
(39, 'Illustrations pour gobelets', 1),
(40, 'Industrie', 37),
(41, 'Informatique', 38),
(42, 'Insectes', 39),
(43, 'Maison', 26),
(44, 'Mariage', 41),
(66, 'pouette', 7);

-- --------------------------------------------------------

--
-- Structure de la table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
CREATE TABLE IF NOT EXISTS `keywords` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(100) NOT NULL,
  PRIMARY KEY (`keyword_id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `keywords`
--

INSERT INTO `keywords` (`keyword_id`, `keyword`) VALUES
(28, 'amour'),
(10, 'automobile'),
(25, 'basket'),
(7, 'berger allemand'),
(8, 'BMW'),
(5, 'chien'),
(29, 'couple'),
(26, 'équipe'),
(30, 'festivité'),
(24, 'football'),
(27, 'instrument de musique'),
(6, 'mignon'),
(23, 'sport'),
(9, 'voiture');

-- --------------------------------------------------------

--
-- Structure de la table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `language_code` varchar(2) NOT NULL,
  `language_name` varchar(50) NOT NULL,
  PRIMARY KEY (`language_code`),
  UNIQUE KEY `language_code` (`language_code`),
  UNIQUE KEY `language_name` (`language_name`)
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
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `symbols`
--

INSERT INTO `symbols` (`symbol_id`, `unique_id`, `file_name`, `symbol_name`, `size`, `active`, `deleted`) VALUES
(107, 'c1fa784b', 'c1fa784b-107', 'guitare', 60, 0, 0),
(108, '35616857', '35616857-108', 'basket', 80, 1, 0),
(109, '5a50caf8', '5a50caf8-109', 'football', 70, 1, 0),
(110, '1b96e26f', '1b96e26f-110', 'mariage', 50, 1, 0),
(111, '81d80be0', '81d80be0-111', 'dj', 50, 0, 0),
(112, '60d10c1f', '60d10c1f-112', 'fete musique', 50, 0, 0),
(113, 'ede8b168', 'ede8b168-113', 'ecole', 100, 1, 0);

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
(109, 15),
(109, 39),
(107, 39),
(107, 7),
(108, 39),
(108, 15),
(110, 39),
(110, 44),
(111, 39),
(111, 7),
(113, 39),
(113, 38),
(113, 30),
(113, 29),
(112, 39),
(112, 16),
(111, 38),
(111, 33),
(110, 33);

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
(109, 24),
(109, 23),
(107, 27),
(109, 26),
(108, 26),
(108, 25),
(108, 23),
(110, 28),
(110, 29),
(111, 30),
(112, 30);

-- --------------------------------------------------------

--
-- Structure de la table `translations`
--

DROP TABLE IF EXISTS `translations`;
CREATE TABLE IF NOT EXISTS `translations` (
  `table_name` varchar(50) NOT NULL,
  `row_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `language_code` varchar(2) NOT NULL,
  KEY `translations_ibfk_1` (`language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `translations`
--

INSERT INTO `translations` (`table_name`, `row_id`, `value`, `language_code`) VALUES
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
('keywords', 10, 'automóvel', 'PT'),
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
('keywords', 10, 'automóvel', 'PT'),
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
('categories', 66, 'anglais', 'EN'),
('categories', 66, 'allemand', 'DE'),
('categories', 66, 'espagne', 'ES'),
('categories', 66, 'france', 'FR'),
('categories', 66, 'italie', 'IT'),
('categories', 66, 'portugal', 'PT'),
('keywords', 23, 'sport', 'EN'),
('keywords', 23, 'sport', 'DE'),
('keywords', 23, 'sport', 'ES'),
('keywords', 23, 'sport', 'FR'),
('keywords', 23, 'sport', 'IT'),
('keywords', 23, 'sport', 'PT'),
('keywords', 24, 'football', 'EN'),
('keywords', 24, 'football', 'DE'),
('keywords', 24, 'football', 'ES'),
('keywords', 24, 'football', 'FR'),
('keywords', 24, 'football', 'IT'),
('keywords', 24, 'football', 'PT'),
('keywords', 25, 'basket', 'EN'),
('keywords', 25, 'basket', 'DE'),
('keywords', 25, 'basket', 'ES'),
('keywords', 25, 'basket', 'FR'),
('keywords', 25, 'basket', 'IT'),
('keywords', 25, 'basket', 'PT'),
('keywords', 26, 'équipe', 'EN'),
('keywords', 26, 'équipe', 'DE'),
('keywords', 26, 'équipe', 'ES'),
('keywords', 26, 'équipe', 'FR'),
('keywords', 26, 'équipe', 'IT'),
('keywords', 26, 'équipe', 'PT'),
('keywords', 27, 'instrument de musique', 'EN'),
('keywords', 27, 'instrument de musique', 'DE'),
('keywords', 27, 'instrument de musique', 'ES'),
('keywords', 27, 'instrument de musique', 'FR'),
('keywords', 27, 'instrument de musique', 'IT'),
('keywords', 27, 'instrument de musique', 'PT'),
('keywords', 28, 'amour', 'EN'),
('keywords', 28, 'amour', 'DE'),
('keywords', 28, 'amour', 'ES'),
('keywords', 28, 'amour', 'FR'),
('keywords', 28, 'amour', 'IT'),
('keywords', 28, 'amour', 'PT'),
('keywords', 29, 'couple', 'EN'),
('keywords', 29, 'couple', 'DE'),
('keywords', 29, 'couple', 'ES'),
('keywords', 29, 'couple', 'FR'),
('keywords', 29, 'couple', 'IT'),
('keywords', 29, 'couple', 'PT'),
('keywords', 30, 'festivité', 'EN'),
('keywords', 30, 'festivité', 'DE'),
('keywords', 30, 'festivité', 'ES'),
('keywords', 30, 'festivité', 'FR'),
('keywords', 30, 'festivité', 'IT'),
('keywords', 30, 'festivité', 'PT');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@example.com', 'admin'),
(2, 'testuser', 'testuser@example.com', '$2y$10$dw2UG9P8MAvgWMEwk4AW6eFIh8yKuGmAmG57K7MUz0F47oK2ySPku'),
(22, 'sukuna', 'j@j', '$2y$10$rmB6I8os7iquxiT8.AdDfO6b8fH2QnUPbYmpSkrPW0CBISkhhJ.Lm');

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
