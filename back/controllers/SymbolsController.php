<?php
require_once './config/database.php';
require_once './models/Symbol.php';
require_once './models/Category.php';
require_once './models/Keyword.php';

class SymbolsController {
    protected $symbolModel;
    protected $categoryModel;
    protected $keywordModel;

    public function __construct() {
        global $pdo;
        $this->symbolModel = new Symbol($pdo);
        $this->categoryModel = new Category($pdo);
        $this->keywordModel = new Keyword($pdo);
    }

    public function getAllSymbols() {
        $symbols = $this->symbolModel->getAllSymbols();
        $filteredSymbols = [];

        foreach ($symbols as $symbol) {
            if (!$symbol['deleted']) {
                $symbol['categories'] = $this->categoryModel->getCategoriesBySymbol($symbol['symbol_id']);
                $symbol['keywords'] = $this->keywordModel->getKeywordsBySymbol($symbol['symbol_id']);
                $filteredSymbols[] = $symbol;
            }
        }

        return $filteredSymbols;
    }

    public function getSymbol($id) {
        $symbol = $this->symbolModel->getSymbolById($id);
        if ($symbol) {
            $symbol['categories'] = $this->categoryModel->getCategoriesBySymbol($id);
            $symbol['keywords'] = $this->keywordModel->getKeywordsBySymbol($id);
            return $symbol;
        } else {
            return ['error' => 'Symbol not found'];
        }
    }

    public function createSymbol($data) {
        $symbolId = $this->symbolModel->createSymbol($data);
        if (is_numeric($symbolId)) {
            $this->updateSymbolCategories($symbolId, $data['categories']);
            $this->updateSymbolKeywords($symbolId, $data['keywords']);
            return ['symbol_id' => $symbolId];
        } else {
            return ['error' => 'Failed to create symbol'];
        }
    }

    public function updateSymbol($id, $data) {
        $symbol = $this->symbolModel->getSymbolById($id);

        if (!$symbol) {
            return ['error' => 'Symbol not found'];
        }

        $validation = $this->validateSymbolData($data);

        if ($validation === true) {
            $updatedData = [
                'name_file' => isset($data['name_file']) ? $data['name_file'] : $symbol['name_file'],
                'active' => isset($data['active']) ? $data['active'] : $symbol['active'],
                'size' => isset($data['size']) ? $data['size'] : $symbol['size'],
            ];

            $result = $this->symbolModel->updateSymbol($id, $updatedData);

            if ($result > 0) {
                $this->updateSymbolCategories($id, isset($data['categories']) ? $data['categories'] : []);
                $this->updateSymbolKeywords($id, isset($data['keywords']) ? $data['keywords'] : []);
                return ['message' => 'Symbol updated successfully'];
            } else {
                return ['error' => 'Symbol update failed'];
            }
        } else {
            return ['error' => 'Invalid data', 'details' => $validation];
        }
    }

    public function deleteSymbol($id) {
        $symbol = $this->symbolModel->getSymbolById($id);

        if (!$symbol) {
            return ['error' => 'Symbol not found'];
        }

        $result = $this->symbolModel->deleteSymbol($id);

        if ($result > 0) {
            return ['message' => 'Symbol deleted successfully'];
        } else {
            return ['error' => 'Symbol deletion failed'];
        }
    }

    private function updateSymbolCategories($symbolId, $categories) {
        $this->categoryModel->removeSymbolFromAllCategories($symbolId);

        foreach ($categories as $categoryId) {
            $this->categoryModel->addSymbolToCategory($symbolId, $categoryId);
        }
    }

    private function updateSymbolKeywords($symbolId, $keywords) {
        $this->keywordModel->deleteKeywordsBySymbol($symbolId);

        foreach ($keywords as $lang => $keyword) {
            $this->keywordModel->addKeyword($symbolId, $lang, $keyword);
        }
    }

    private function validateSymbolData($data) {
        $errors = [];

        if (!isset($data['name_file']) || empty($data['name_file'])) {
            $errors[] = 'name_file is required';
        }

        if (!isset($data['active']) || !in_array($data['active'], [0, 1])) {
            $errors[] = 'active must be 0 or 1';
        }

        if (!isset($data['size']) || !is_numeric($data['size']) || $data['size'] < 1 || $data['size'] > 100) {
            $errors[] = 'size must be a number between 1 and 100';
        }

        return empty($errors) ? true : $errors;
    }
}
