<?php
// Informations de connexion à la base de données :
//! _____ docker : _____
// define('DB_HOST', 'db');
// define('DB_USER', 'dbuser');
// define('DB_PASS', '1llustr@t0rV2');
// define('DB_NAME', 'illustrator2');

//! _____ local : _____
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'illustrator2');


// Chemin du dossier où les symboles sont stockés
// define('SYMBOLS_PATH', '../assets/');
define('SYMBOLS_PATH', __DIR__.'/../assets/');

// variable du token utilisateur
define('JWT_SECRET_KEY', 'P7UZSOYLI2AdG+WAOh5v/R4rV5u9WIdROns/Ssa/gBJ0f7JsqXH7ZH4Ie/S/8o+bR1yGaj0Lpe750F3z2tSRBQ==');

// Noms des tables de la base de données
define('TABLE_SYMBOLS', 'symbols');
define('TABLE_KEYWORDS', 'keywords');
define('TABLE_CATEGORIES', 'categories');
define('TABLE_SYMBOL_KEYWORD', 'symbol_keyword');
define('TABLE_SYMBOL_CATEGORY', 'symbol_category');
define('TABLE_LANGUAGES', 'languages');
define('TABLE_TRANSLATIONS', 'translations');
define('TABLE_USERS', 'users');


