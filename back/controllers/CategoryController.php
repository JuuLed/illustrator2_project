<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Translation.php';
require_once __DIR__ . '/../models/Language.php';

class CategoryController
{
	protected $categoryModel;
	protected $translationModel;
	protected $languageModel;

	public function __construct(Category $categoryModel = null, Translation $translationModel = null, Language $languageModel = null)
    {
        global $pdo;
        $this->categoryModel = $categoryModel ? $categoryModel : new Category($pdo);
        $this->translationModel = $translationModel ? $translationModel : new Translation($pdo);
        $this->languageModel = $languageModel ? $languageModel : new Language($pdo);

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
			$translations = $this->translationModel->getTranslationByTableAndId(TABLE_CATEGORIES, $categoryId);

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
		$translations = $data['translations'];

		// Récupérer le dernier ordre enregistré
		$lastOrder = $this->categoryModel->getLastCategoryOrder();

		// Calculer le nouvel ordre en incrémentant le dernier ordre
		$categoryOrder = $lastOrder + 1;

		// Créer la catégorie dans la base de données
		$categoryId = $this->categoryModel->createCategory($categoryName);

		if ($categoryId) {
			// Créer les traductions pour la catégorie
			foreach ($translations as $languageCode => $translation) {
				$this->translationModel->createTranslation(TABLE_CATEGORIES, $categoryId, $translation, $languageCode);
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
		$this->translationModel->deleteTranslationByTableAndId(TABLE_CATEGORIES, $id);

		if ($result > 0) {
			return ['message' => 'Category deleted successfully'];
		} else {
			return ['error' => 'Category deletion failed'];
		}
	}
}
?>