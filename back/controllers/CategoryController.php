<?php
require_once './config/database.php';
require_once './models/Category.php';
require_once './models/Translation.php';
require_once './models/Language.php';

class CategoryController
{
	protected $categoryModel;
	protected $translationModel;
	protected $languageModel;

	public function __construct()
	{
		global $pdo;
		$this->categoryModel = new Category($pdo);
		$this->translationModel = new Translation($pdo);
		$this->languageModel = new Language($pdo);
	}

	public function getAllCategories()
	{
		$categories = $this->categoryModel->getAllCategories();
		$result = [];

		foreach ($categories as $category) {

			$categoryId = $category['category_id'];

			if (!isset($result[$categoryId])) {
				$categoryData = $this->getCategory($categoryId);

				$result[$categoryId] = $categoryData;
			}
		}

		return array_values($result);
	}

	public function getCategory($id)
	{
		$category = $this->categoryModel->getCategoryById($id);
		if ($category) {
			$categoryId = $category['category_id'];
			$categoryName = $category['category'];
			$categoryOrder = $category['order'];

			// Vérifier si la catégorie existe déjà dans le résultat
			$result = [
				'category_id' => $categoryId,
				'category' => $categoryName,
				'order' => $categoryOrder,
				'translations' => []
			];

			// Récupérer les traductions pour la catégorie
			$translations = $this->translationModel->getTranslationByTableAndId('categories', $categoryId);

			foreach ($translations as $translation) {
				$languageCode = $translation['language_code'];
				$value = $translation['value'];

				// Ajouter la traduction au tableau correspondant à la catégorie
				$result['translations'][$languageCode] = $value;
			}

			return $result;
		} else {
			return ['error' => 'Category not found'];
		}
	}

	// Json d'ajout :
	// {
	// 	"category": "pouette",
	// 	"order": 16,
	// 	"translations": {
	// 		"EN": "pouetted",
	// 		"DE": "pouetteEUU",
	// 		"ES": "pouettess",
	// 		"FR": "pouette",
	// 		"IT": "pouetti",
	// 		"PT": "pouettesh"
	// 	}
	// }
	public function createCategory($data)
	{
		$categoryName = $data['category'];
		$categoryOrder = $data['order'];
		$translations = $data['translations'];

		// Créer la catégorie dans la base de données
		$categoryId = $this->categoryModel->createCategory($categoryName, $categoryOrder);

		if ($categoryId) {
			// Créer les traductions pour la catégorie
			foreach ($translations as $languageCode => $translation) {
				$this->translationModel->createTranslation('categories', $categoryId, $translation, $languageCode);
			}

			// Récupérer les traductions pour construire la réponse
			$responseTranslations = [];
			foreach ($translations as $languageCode => $translation) {
				$language = $this->languageModel->getLanguageByCode($languageCode);
				if ($language) {
					$responseTranslations[$languageCode] = $translation;
				}
			}

			// Construire la réponse avec les informations de la catégorie et les traductions
			$response = [
				'category_id' => $categoryId,
				'category' => $categoryName,
				'order' => $categoryOrder,
				'translations' => $responseTranslations
			];

			return $response;
		} else {
			return ['error' => 'Failed to create category'];
		}
	}

	// json de modification :
	// {
	// 	"category": "Modif categorie",
	// 	"order": 24
	// }
	public function updateCategory($id, $data)
	{
		$category = $this->categoryModel->getCategoryById($id);

		if (!$category) {
			return ['error' => 'Category not found'];
		}

		$categoryName = isset($data['category']) ? $data['category'] : $category['category'];
		$categoryOrder = isset($data['order']) ? $data['order'] : $category['order'];

		$result = $this->categoryModel->updateCategory($id, $categoryName, $categoryOrder);

		if ($result > 0) {
			return ['message' => 'Category updated successfully'];
		} else {
			return ['error' => 'Category update failed'];
		}
	}

	public function deleteCategory($id)
	{
		$category = $this->categoryModel->getCategoryById($id);

		if (!$category) {
			return ['error' => 'Category not found'];
		}

		// Supprimer la catégorie de la table "categories"
		$result = $this->categoryModel->deleteCategory($id);

		// Supprimer les traductions associées à la catégorie de la table "translations"
		$this->translationModel->deleteTranslationByTableAndId('categories', $id);

		if ($result > 0) {
			return ['message' => 'Category deleted successfully'];
		} else {
			return ['error' => 'Category deletion failed'];
		}
	}
}
?>