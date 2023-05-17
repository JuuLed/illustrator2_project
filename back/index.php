<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // Si c'est une requête de type OPTIONS, on termine le script ici.
}

require './controllers/SymbolsController.php';
$symbolsController = new SymbolsController();

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
            $response = $symbolsController->getAllSymbols();
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $symbolsController->createSymbol($data);
        } else {
            $response = ['error' => 'Method not allowed'];
            http_response_code(405);
        }
        break;
    case preg_match('/^symbols\/\d+$/', $route) ? true : false:
        $id = explode('/', $route)[1];
        if ($method === 'GET') {
            $response = $symbolsController->getSymbol($id);
        } elseif ($method === 'PUT') {
            $data = json_decode(file_get_contents('php://input'), true);
            $response = $symbolsController->updateSymbol($id, $data);
        } elseif ($method === 'DELETE') {
            $response = $symbolsController->deleteSymbol($id);
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
