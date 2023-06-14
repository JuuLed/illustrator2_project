<?php

// Chemin du dossier où les symboles sont stockés
define('SYMBOLS_PATH', '/back/assets/');

// Informations de connexion à la base de données
//! _____ docker : _____
// define('DB_HOST', 'db');
//! _____ local : _____
define('DB_HOST', 'localhost');

define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'illustrator2');

// Noms des tables de la base de données
define('TABLE_SYMBOLS', 'symbols');
define('TABLE_KEYWORDS', 'keywords');
define('TABLE_CATEGORIES', 'categories');
define('TABLE_SYMBOL_KEYWORD', 'symbol_keyword');
define('TABLE_SYMBOL_CATEGORY', 'symbol_category');
define('TABLE_LANGUAGES', 'languages');
define('TABLE_TRANSLATIONS', 'translations');
