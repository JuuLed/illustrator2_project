<?php
require './config/database.php';
require './models/Symbol.php';
require './models/Category.php';
require './models/Keyword.php';

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
            $this->updateCategories($symbolId, $data['categories']);
            $this->updateKeywords($symbolId, $data['keywords']);
            return ['symbol_id' => $symbolId];
        } else {
            return ['error' => 'Failed to create symbol'];
        }
    }

    public function updateSymbol($id, $data) {
        $result = $this->symbolModel->updateSymbol($id, $data);
        if ($result > 0) {
            $this->updateCategories($id, $data['categories']);
            $this->updateKeywords($id, $data['keywords']);
            return ['message' => 'Symbol updated successfully'];
        } else {
            return ['error' => 'Symbol update failed'];
        }
    }

    public function deleteSymbol($id) {
        $result = $this->symbolModel->deleteSymbol($id);
        if ($result > 0) {
            return ['message' => 'Symbol deleted successfully'];
        } else {
            return ['error' => 'Symbol delete failed'];
        }
    }

    private function updateCategories($symbolId, $categories) {
        // Remove existing associations between the symbol and categories
        $this->categoryModel->removeSymbolFromAllCategories($symbolId);

        // Add associations between the symbol and selected categories
        foreach ($categories as $categoryId) {
            $this->categoryModel->addSymbolToCategory($symbolId, $categoryId);
        }
    }

    private function updateKeywords($symbolId, $keywords) {
        // Remove existing keywords for the symbol
        $this->keywordModel->deleteKeywordsBySymbol($symbolId);

        // Add new keywords for the symbol
        foreach ($keywords as $lang => $keyword) {
            $this->keywordModel->addKeyword($symbolId, $lang, $keyword);
        }
    }
}
