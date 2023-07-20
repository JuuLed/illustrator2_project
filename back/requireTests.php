<?php

// Config
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Models
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Keyword.php';
require_once __DIR__ . '/models/Language.php';
require_once __DIR__ . '/models/Symbol.php';
require_once __DIR__ . '/models/SymbolCategory.php';
require_once __DIR__ . '/models/SymbolKeyword.php';
require_once __DIR__ . '/models/Translation.php';
require_once __DIR__ . '/models/User.php';

// Controllers
require_once __DIR__ . '/controllers/CategoryController.php';
require_once __DIR__ . '/controllers/KeywordController.php';
require_once __DIR__ . '/controllers/LanguageController.php';
require_once __DIR__ . '/controllers/SymbolCategoryController.php';
require_once __DIR__ . '/controllers/SymbolController.php';
require_once __DIR__ . '/controllers/SymbolKeywordController.php';
require_once __DIR__ . '/controllers/TranslationController.php';
require_once __DIR__ . '/controllers/UserController.php';

// Utils
require_once __DIR__ . '/utils/SvgValidator.php';
