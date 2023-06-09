<?php

require_once './config/database.php';
require_once './models/Symbol.php';
require_once './models/Translation.php';

require_once './models/SymbolCategory.php';
require_once './models/SymbolKeyword.php';

class SymbolController {
    protected $symbolModel;
    protected $translationModel;
    protected $symbolCategoryModel;
    protected $symbolKeywordModel;

    public function __construct() {
        global $pdo;
        $this->symbolModel = new Symbol($pdo);
        $this->translationModel = new Translation($pdo);

        $this->symbolCategoryModel = new SymbolCategory($pdo);
        $this->symbolKeywordModel = new SymbolKeyword($pdo);
    }

    public function getAllSymbols() {
		$symbols = $this->symbolModel->getAllSymbols();
		$result = [];
	
		foreach ($symbols as $symbol) {
			$symbolId = $symbol['symbol_id'];
	
			if (!isset($result[$symbolId])) {
				$symbolData = $this->getSymbol($symbolId);
	
				$result[$symbolId] = $symbolData;
			}
		}
		return array_values($result);
	}
	
	public function getSymbol($symbol_id) {
		$symbol = $this->symbolModel->getSymbolById($symbol_id);
	
		if ($symbol) {
			$symbolData = [
				'id' => $symbol['symbol_id'],
				'file_name' => $symbol['file_name'],
				'size' => $symbol['size'],
				'active' => $symbol['active'] ? 1 : 0,
				'categories' => [],
				'keywords' => []
			];
	
			// Fetch categories
			$categories = $this->symbolCategoryModel->getAllCategoriesBySymbolId($symbol['symbol_id']);
			foreach ($categories as $category) {
				$translations = $this->translationModel->getTranslationByTableAndId('categories', $category['category_id']);
				$categoryData = [
					'id' => $category['category_id'],
					'category' => $category['category'],
					'translations' => []
				];
				foreach ($translations as $translation) {
					$categoryData['translations'][$translation['language_code']] = $translation['value'];
				}
				$symbolData['categories'][] = $categoryData;
			}
	
			// Fetch keywords
			$keywords = $this->symbolKeywordModel->getAllKeywordsBySymbolId($symbol['symbol_id']);
			foreach ($keywords as $keyword) {
				$translations = $this->translationModel->getTranslationByTableAndId('keywords', $keyword['keyword_id']);
				$keywordData = [
					'id' => $keyword['keyword_id'],
					'keyword' => $keyword['keyword'],
					'translations' => []
				];
				foreach ($translations as $translation) {
					$keywordData['translations'][$translation['language_code']] = $translation['value'];
				}
				$symbolData['keywords'][] = $keywordData;
			}
	
			return $symbolData;
		}
	
		return null;
	}
	
	// Json d'ajout ou de update :
	// {
	// 	"file_name": "example_symbol",
	// 	"size": 50,
	// 	"active": 0,
	// 	"categories": [1],
	// 	"keywords":  [3]
	// }
	public function createSymbol($data) {
		$fileName = $data['file_name'];
		$size = $data['size'];
		$active = $data['active'];
		$categoryIds = $data['categories'];
		$keywordIds = $data['keywords'];
	
		$symbolId = $this->symbolModel->createSymbol($fileName, $size, $active, $categoryIds, $keywordIds);
	
		return $symbolId;
	}
	
	public function updateSymbol($id, $data) {
		$fileName = isset($data['file_name']) ? $data['file_name'] : null;
		$size = isset($data['size']) ? $data['size'] : null;
		$active = isset($data['active']) ? $data['active'] : null;
		// $categoryIds = isset($data['categories']) ? $data['categories'] : null;
		// $keywordIds = isset($data['keywords']) ? $data['keywords'] : null;
	
		// Mise à jour du symbole 
		// $this->symbolModel->updateSymbol($id, $fileName, $size, $active, $categoryIds, $keywordIds);
		$this->symbolModel->updateSymbol($id, $fileName, $size, $active);
	
		return ['message' => 'Symbol updated successfully'];
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
	
}
