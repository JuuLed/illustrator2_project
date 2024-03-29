<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Symbol.php';
require_once __DIR__ . '/../models/Translation.php';

require_once __DIR__ . '/../models/SymbolCategory.php';
require_once __DIR__ . '/../models/SymbolKeyword.php';

class SymbolController
{
	protected $symbolModel;
	protected $translationModel;
	protected $symbolCategoryModel;
	protected $symbolKeywordModel;

	public function __construct(
		Symbol $symbolModel = null,
		Translation $translationModel = null,
		SymbolCategory $symbolCategoryModel = null,
		SymbolKeyword $symbolKeywordModel = null
	) {
		global $pdo;
		$this->symbolModel = $symbolModel ? $symbolModel : new Symbol($pdo);
		$this->translationModel = $translationModel ? $translationModel : new Translation($pdo);

		$this->symbolCategoryModel = $symbolCategoryModel ? $symbolCategoryModel : new SymbolCategory($pdo);
		$this->symbolKeywordModel = $symbolKeywordModel ? $symbolKeywordModel : new SymbolKeyword($pdo);
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

				'src' => (DB_HOST == "db") ?
				('http://localhost:8000/assets/' . $symbol['file_name'] . ".svg") :
				(str_replace("C:\\wamp64\\www\\", "http://" . DB_HOST . "/", SYMBOLS_PATH) . $symbol['file_name'] . ".svg"),

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


	public function createSymbol($file, $symbolName)
	{
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

			$symbolData = $this->getSymbol($symbolId);

			$newFileName = SYMBOLS_PATH . $symbolData['file_name'] . '.svg';
			rename($fileDestination, $newFileName);

			// Retournez une réponse appropriée
			$response = array(
				"status" => "success",
				"message" => "Symbol has been successfully created.",
				"data" => array(
					"symbol_name" => $symbolName,
					"file_name" => $newFileName
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

	public function updateSvg($id, $newSvgFile)
	{
		// Vérifiez le fichier SVG
		$isSvgValid = $this->checkSvgFile($newSvgFile);
		if(!$isSvgValid){
			return ['error' => 'Invalid SVG file.'];
		}

		// Obtenez l'information du symbole existant
		$existingSymbol = $this->symbolModel->getSymbolById($id);
		
		// Assurez-vous que le symbole existe sinon retourner erreur
		if (!$existingSymbol) {
			return ['error' => 'Symbol not found'];
		}
	
		// Créez un nouveau symbole avec le même symbolName, size et 
		// active status, et l'unique_id existant
		$newSymbolId = $this->symbolModel->createSymbol(
			$existingSymbol['symbolName'], 
			$existingSymbol['size'], 
			$existingSymbol['active'], 
			$existingSymbol['unique_id']
		);
			
		// Récupérez toutes les liaisons (relations) de l'ancien symbole
		$categories = $this->symbolCategoryModel->getAllCategoriesBySymbolId($id);
		$keywords = $this->symbolKeywordModel->getAllKeywordsBySymbolId($id);
	
		// Ajoutez les liaisons (relations) au nouveau symbole
		foreach ($categories as $category) {
			$this->symbolCategoryModel->addCategoryToSymbol(
				$newSymbolId, 
				$category['category_id']
			);
		}
		foreach ($keywords as $keyword) {
			$this->symbolKeywordModel->addKeywordToSymbol(
				$newSymbolId, 
				$keyword['keyword_id']
			);
		}
	
		// Supprimez l'ancien symbole 
		$this->symbolModel->deleteSymbol($id);

    	return ['message' => 'SVG file updated successfully'];
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