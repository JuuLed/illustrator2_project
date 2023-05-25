<?php

require_once './config/database.php';
require_once './models/Symbol.php';
require_once './models/Translate.php';
require_once './models/Language.php';
require_once './models/Category.php';
require_once './models/Keyword.php';

class SymbolController {
    protected $symbolModel;
    protected $translateModel;
    protected $languageModel;
    protected $categoryModel;
    protected $keywordModel;

    public function __construct() {
        global $pdo;
        $this->symbolModel = new Symbol($pdo);
        $this->translateModel = new Translate($pdo);
        $this->languageModel = new Language($pdo);
        $this->categoryModel = new Category($pdo);
        $this->keywordModel = new Keyword($pdo);
    }

    public function getAllSymbols() {
		$symbols = $this->symbolModel->getAllSymbols();
		$result = [];
	
		foreach ($symbols as $symbol) {
			$symbolId = $symbol['symbol_id'];
	
			// Vérifier si le symbole existe déjà dans le résultat
			if (!isset($result[$symbolId])) {
				// Créer une nouvelle entrée pour le symbole
				$symbolData = [
					'id' => $symbolId,
					'name' => $symbol['file_name'],
					'size' => $symbol['size'],
					'status' => $symbol['active'] ? 1 : 0,
					'categories' => [],
					'keywords' => []
				];
	
				$result[$symbolId] = $symbolData;
			}
	
			// Ajouter les catégories du symbole
			$categoryData = [
				'id' => $symbol['category_id'],
				'category' => $symbol['category'],
				'translations' => []
			];
			$translations = $this->translateModel->getTranslateByTableAndId('categories', $symbol['category_id']);
			foreach ($translations as $translation) {
				$categoryData['translations'][$translation['language_code']] = $translation['value'];
			}
	
			$result[$symbolId]['categories'][] = $categoryData;
	
			// Vérifier si le symbole a des mots-clés
			if (!empty($symbol['keyword_id']) && !empty($symbol['keyword'])) {
				// Ajouter les mots-clés du symbole
				$keywordData = [
					'id' => $symbol['keyword_id'],
					'keyword' => $symbol['keyword'],
					'translations' => []
				];
				$translations = $this->translateModel->getTranslateByTableAndId('keywords', $symbol['keyword_id']);
				foreach ($translations as $translation) {
					$keywordData['translations'][$translation['language_code']] = $translation['value'];
				}
	
				$result[$symbolId]['keywords'][] = $keywordData;
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
			$categories = $this->categoryModel->getCategoriesForSymbol($symbol['symbol_id']);
			foreach ($categories as $category) {
				$translations = $this->translateModel->getTranslateByTableAndId('categories', $category['category_id']);
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
			$keywords = $this->keywordModel->getKeywordsForSymbol($symbol['symbol_id']);
			foreach ($keywords as $keyword) {
				$translations = $this->translateModel->getTranslateByTableAndId('keywords', $keyword['keyword_id']);
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
		$categoryIds = isset($data['categories']) ? $data['categories'] : null;
		$keywordIds = isset($data['keywords']) ? $data['keywords'] : null;
	
		// Mise à jour du symbole 
		$this->symbolModel->updateSymbol($id, $fileName, $size, $active, $categoryIds, $keywordIds);
	
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
