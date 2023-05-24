<?php
require_once './config/database.php';
require_once './models/Category.php';
require_once './models/Translate.php';
require_once './models/Language.php';

class CategoryController {
    protected $categoryModel;
	protected $translateModel;
	protected $languageModel;

    public function __construct() {
        global $pdo;
        $this->categoryModel = new Category($pdo);
		$this->translateModel = new Translate($pdo);
		$this->languageModel = new Language($pdo);
    }

	public function getAllCategories() {
		$categories = $this->categoryModel->getAllCategories();
	
		$result = [];
	
		foreach ($categories as $category) {
			$categoryId = $category['category_id'];
			$categoryName = $category['category'];
	
			// Récupérer les traductions de la catégorie
			$translations = $this->translateModel->getTranslateByTableAndId('categories', $categoryId);
	
			$categoryTranslations = [];
			foreach ($translations as $translation) {
				$languageCode = $translation['language_code'];
				$value = $translation['value'];
				$categoryTranslations[$languageCode] = $value;
			}
	
			$result[] = [
				'category_id' => $categoryId,
				'category' => $categoryName,
				'translates' => $categoryTranslations
			];
		}
	
		return $result;
	}

    public function getCategory($id) {
		$category = $this->categoryModel->getCategoryById($id);
		if ($category) {
			// Récupérer les traductions de la catégorie
			$translations = $this->translateModel->getTranslateByTableAndId('categories', $id);
	
			// Ajouter les traductions à la réponse
			$category['translates'] = $translations;
	
			return $category;
		} else {
			return ['error' => 'Category not found'];
		}
	}

	// Json d'ajout :
	// {
	// 	"category": "Category Name",
	// 	"translates": {
	// 	  "EN": "Translation in English",
	// 	  "DE": "Translation in German",
	// 	  "ES": "Translation in Spanish",
	// 	  "FR": "Translation in French",
	// 	  "IT": "Translation in Italian",
	// 	  "PT": "Translation in Portuguese"
	// 	}
	//   }	  
	public function createCategory($data) {
		$categoryName = $data['category'];
		$translations = $data['translates'];
	
		// Créer la catégorie dans la base de données
		$categoryId = $this->categoryModel->createCategory($categoryName);
	
		if ($categoryId) {
			// Récupérer toutes les langues disponibles
			$availableLanguages = $this->languageModel->getAvailableLanguages();
	
			// Créer les traductions pour la catégorie
			foreach ($translations as $languageCode => $translation) {
				// Vérifier si la langue est disponible
				if (isset($availableLanguages[$languageCode])) {
					$this->translateModel->createTranslate('categories', $categoryId, $translation, $languageCode);
				}
			}
	
			// Récupérer les traductions pour construire la réponse
			$responseTranslations = [];
			foreach ($translations as $languageCode => $translation) {
				// Vérifier si la langue est disponible
				if (isset($availableLanguages[$languageCode])) {
					$responseTranslations[$languageCode] = $translation;
				}
			}
	
			// Construire la réponse avec les informations de la catégorie et les traductions
			$response = [
				'category_id' => $categoryId,
				'category' => $categoryName,
				'translates' => $responseTranslations
			];
	
			return $response;
		} else {
			return ['error' => 'Failed to create category'];
		}
	}
	
	
    public function updateCategory($id, $data) {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            return ['error' => 'Category not found'];
        }

        $categoryName = isset($data['category']) ? $data['category'] : $category['category'];

        $result = $this->categoryModel->updateCategory($id, $categoryName);

        if ($result > 0) {
            return ['message' => 'Category updated successfully'];
        } else {
            return ['error' => 'Category update failed'];
        }
    }

    public function deleteCategory($id) {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            return ['error' => 'Category not found'];
        }

        $result = $this->categoryModel->deleteCategory($id);

        if ($result > 0) {
            return ['message' => 'Category deleted successfully'];
        } else {
            return ['error' => 'Category deletion failed'];
        }
    }
}
