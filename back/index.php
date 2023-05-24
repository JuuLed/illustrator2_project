<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // Si c'est une requête de type OPTIONS, on termine le script ici.
}

require_once './controllers/SymbolController.php';
require_once './controllers/CategoryController.php';
require_once './controllers/KeywordController.php';
require_once './controllers/LanguageController.php';
require_once './controllers/TranslateController.php';

$symbolController = new SymbolController();
$categoryController = new CategoryController();
$keywordController = new KeywordController();
$languageController = new LanguageController();
$translateController = new TranslateController();

$request = $_SERVER['REQUEST_URI'];

// Chemin de base de votre API REST
$base_path = '/illustrator2_project/back/index.php'; 
// Supprimez la partie du chemin de base de l'URI
$route = str_replace($base_path, '', $request);
// Supprimez les éventuels paramètres de requête de l'URI
$route = strtok($route, '?');
// Supprimez les éventuels slashs initiaux ou finaux de la route
$route = trim($route, '/');

$method = $_SERVER['REQUEST_METHOD'];

switch ($route) {
    case 'symbols':
        if ($method === 'GET') {
            $response = $symbolController->getAllSymbols();
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $symbolController->createSymbol($data);
        } else {
            $response = ['error' => 'Method not allowed'];
            http_response_code(405);
        }
    break;
    case preg_match('/^symbols\/\d+$/', $route) ? true : false:
        $id = explode('/', $route)[1];
        if ($method === 'GET') {
            $response = $symbolController->getSymbol($id);
        } elseif ($method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $symbolController->updateSymbol($id, $data);
        } elseif ($method === 'DELETE') {
            $response = $symbolController->deleteSymbol($id);
        } else {
            $response = ['error' => 'Method not allowed'];
            http_response_code(405);
        }
     break;

    case 'categories':
        if ($method === 'GET') {
            $response = $categoryController->getAllCategories();
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $categoryController->createCategory($data);
        } else {
            $response = ['error' => 'Method not allowed'];
            http_response_code(405);
        }
    break;
    case preg_match('/^categories\/\d+$/', $route) ? true : false:
        $id = explode('/', $route)[1];
        if ($method === 'GET') {
            $response = $categoryController->getCategory($id);
        } elseif ($method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $categoryController->updateCategory($id, $data);
        } elseif ($method === 'DELETE') {
            $response = $categoryController->deleteCategory($id);
        } else {
            $response = ['error' => 'Method not allowed'];
            http_response_code(405);
        }
    break;

    case 'keywords':
        if ($method === 'GET') {
            $response = $keywordController->getAllKeywords();
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $keywordController->createKeyword($data);
        } else {
            $response = ['error' => 'Method not allowed'];
            http_response_code(405);
        }
    break;
    case preg_match('/^keywords\/\d+$/', $route) ? true : false:
        $id = explode('/', $route)[1];
        if ($method === 'GET') {
            $response = $keywordController->getKeyword($id);
        } elseif ($method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $keywordController->updateKeyword($id, $data);
        } elseif ($method === 'DELETE') {
            $response = $keywordController->deleteKeyword($id);
        } else {
            $response = ['error' => 'Method not allowed'];
            http_response_code(405);
        }
    break;

	case 'languages':
		if ($method === 'GET') {
			$response = $languageController->getAllLanguages();
		} elseif ($method === 'POST') {
			$data = json_decode(file_get_contents('php://input'), true);
			$response = $languageController->createLanguage($data);
		} else {
			$response = ['error' => 'Method not allowed'];
			http_response_code(405);
		}
	break;
	case preg_match('/^languages\/\d+$/', $route) ? true : false:
		$id = explode('/', $route)[1];
		if ($method === 'GET') {
			$response = $languageController->getLanguage($id);
		} elseif ($method === 'PUT') {
			$data = json_decode(file_get_contents('php://input'), true);
			$response = $languageController->updateLanguage($id, $data);
		} elseif ($method === 'DELETE') {
			$response = $languageController->deleteLanguage($id);
		} else {
			$response = ['error' => 'Method not allowed'];
			http_response_code(405);
		}
	break;

	case 'translates':
        if ($method === 'GET') {
            $response = $translateController->getAllTranslates();
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $translateController->createTranslate($data);
        } else {
            $response = ['error' => 'Method not allowed'];
            http_response_code(405);
        }
    break;
    case preg_match('/^translates\/[^\/]+\/\d+$/', $route) ? true : false:
        $params = explode('/', $route);
        $table = $params[1];
        $id = $params[2];

        if ($method === 'GET') {
            $response = $translateController->getTranslateByTableAndId($table, $id);
        } elseif ($method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $translateController->updateTranslate($table, $id, $data);
        } elseif ($method === 'DELETE') {
            $response = $translateController->deleteTranslate($table, $id);
        } else {
            $response = ['error' => 'Method not allowed'];
            http_response_code(405);
        }
    break;

    default:
        $response = ['error' => 'Endpoint not found'];
        http_response_code(404);
        break;
}

header('Content-Type: application/json');
echo json_encode($response);
