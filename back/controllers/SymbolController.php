<?php

require_once './config/database.php';
require_once './models/Symbol.php';
require_once './models/Translation.php';

require_once './models/SymbolCategory.php';
require_once './models/SymbolKeyword.php';

class SymbolController
{
	protected $symbolModel;
	protected $translationModel;
	protected $symbolCategoryModel;
	protected $symbolKeywordModel;

	public function __construct()
	{
		global $pdo;
		$this->symbolModel = new Symbol($pdo);
		$this->translationModel = new Translation($pdo);

		$this->symbolCategoryModel = new SymbolCategory($pdo);
		$this->symbolKeywordModel = new SymbolKeyword($pdo);
	}

	public function getAllSymbols()
	{
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

	public function getSymbol($symbol_id)
	{
		$symbol = $this->symbolModel->getSymbolById($symbol_id);

		if ($symbol) {
			$symbolData = [
				'id' => $symbol['symbol_id'],
				'unique_id' => $symbol['unique_id'],
				'file_name' => $symbol['file_name'],
				'symbol_name' => $symbol['symbol_name'],
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

	// Json d'ajout :
	// {
	// 	"symbol_name": "Anniversaire",
	// 	"size": 50,
	// 	"active": 0
	// }
	public function createSymbol($data)
	{
		$symbolName = $data['symbol_name'];
		$size = $data['size'];
		$active = $data['active'];

		$symbolId = $this->symbolModel->createSymbol($symbolName, $size, $active);

		return $symbolId;
	}

	// Json de update :
	// {
	// 	"symbol_name": "Anniversaire",
	// 	"size": 50,
	// 	"active": 0,
	// 	"categories": [5, 15],
	// 	"keywords": [10]
	// }
	public function updateSymbol($id, $data)
	{
		$symbolName = isset($data['symbol_name']) ? $data['symbol_name'] : null;
		$size = isset($data['size']) ? $data['size'] : null;
		$active = isset($data['active']) ? $data['active'] : null;
		$categoryIds = isset($data['categories']) ? $data['categories'] : null;
		$keywordIds = isset($data['keywords']) ? $data['keywords'] : null;

		// Mise à jour du symbole 
		$newId = $this->symbolModel->updateSymbol($id, $symbolName, $size, $active);

		// Ajout des anciennes catégories et mots-clés lié au symbole mise a jour
		$this->symbolCategoryModel->updateSymbolCategories($newId, $categoryIds);
		$this->symbolKeywordModel->updateSymbolKeywords($newId, $keywordIds);

		//! ???
		//? Suppression des catégories et mots-clés du symbole supprimer
		// $this->symbolCategoryModel->deleteSymbolCategories($id);
		// $this->symbolKeywordModel->deleteSymbolKeywords($id);

		return ['message' => 'Symbol updated successfully'];
	}

	public function deleteSymbol($id)
	{
		$symbol = $this->symbolModel->getSymbolById($id);

		if (!$symbol) {
			return ['error' => 'Symbol not found'];
		}

		// Suppression des catégories et mots-clés du symbole supprimer
		$this->symbolCategoryModel->deleteSymbolCategories($id);
		$this->symbolKeywordModel->deleteSymbolKeywords($id);

		$result = $this->symbolModel->deleteSymbol($id);

		if ($result > 0) {
			return ['message' => 'Symbol deleted successfully'];
		} else {
			return ['error' => 'Symbol deletion failed'];
		}
	}

}