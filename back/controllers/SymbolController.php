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
				'src' => SYMBOLS_PATH . $symbol['file_name'],
				'categories' => [],
				'keywords' => []
			];

			// Fetch categories
			$categories = $this->symbolCategoryModel->getAllCategoriesBySymbolId($symbol['symbol_id']);
			foreach ($categories as $category) {
				$translations = $this->translationModel->getTranslationByTableAndId(TABLE_CATEGORIES, $category['category_id']);
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
				$translations = $this->translationModel->getTranslationByTableAndId(TABLE_KEYWORDS, $keyword['keyword_id']);
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
	// public function createSymbol($data)
	// {
	// 	$symbolName = $data['symbol_name'];
	// 	$size = $data['size'];
	// 	$active = $data['active'];

	// 	$symbolId = $this->symbolModel->createSymbol($symbolName, $size, $active);

	// 	return $symbolId;
	// }

	public function createSymbol($file, $symbolName) {
		// Récupérez les informations du fichier
		$fileName = $file['name'];
		$fileTmpName = $file['tmp_name'];
		$fileSize = $file['size'];
		$fileError = $file['error'];
		$fileType = $file['type'];
	
		// Validez les informations du fichier et le nom du symbole avant de les utiliser
	
		// Utilisez move_uploaded_file pour déplacer le fichier dans le dossier de destination
		$fileDestination = SYMBOLS_PATH . $fileName;
		if (move_uploaded_file($fileTmpName, $fileDestination)) {
			// Enregistrez les informations du fichier et le nom du symbole dans votre base de données
		
			// Appel à la méthode du model pour créer le symbole
			$symbolId = $this->symbolModel->createSymbol($symbolName);
		
			// Retournez une réponse appropriée
			$response = array(
				"status" => "success",
				"message" => "Symbol has been successfully created.",
				"data" => array(
					"symbol_name" => $symbolName,
					"file_name" => $fileName
				)
			);
		} else {
			$response = array(
				"status" => "error",
				"message" => "Failed to upload file."
			);
		}
	
		return $response;
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

	// Verification du .svg
	function checkSvgFile($file_path)
	{
		$svg_content = file_get_contents($file_path);

		// Cherchez toutes les occurrences de fill et stroke avec leur couleur respective
		preg_match_all('/(fill|stroke):\s*(#[0-9a-fA-F]{6}|#[0-9a-fA-F]{3})/i', $svg_content, $matches);

		// Initialise un tableau pour stocker les couleurs uniques trouvées
		$colors = array();

		foreach ($matches[2] as $color) {
			// Si une couleur autre que #000000 est trouvée, refusez le fichier
			if (strtolower($color) !== "#000000") {
				return false;
			}
			if (!in_array($color, $colors)) {
				$colors[] = $color;
			}
		}

		// Si plus d'une couleur est trouvée, refusez le fichier
		if (count($colors) > 1) {
			return false;
		}

		echo "Colors: ";
		print_r($colors); // Imprimez le tableau des couleurs

		return true;
	}
}