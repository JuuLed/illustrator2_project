<?php
require_once './config/database.php';
require_once './models/Keyword.php';
require_once './models/Translation.php';
require_once './models/Language.php';

class KeywordController {
    protected $keywordModel;
    protected $translationModel;
	protected $languageModel;

    public function __construct() {
        global $pdo;
        $this->keywordModel = new Keyword($pdo);
        $this->translationModel = new Translation($pdo);
		$this->languageModel = new Language($pdo);
    }

    public function getAllKeywords() {
		$keywords = $this->keywordModel->getAllKeywords();
		$result = [];
	
		foreach ($keywords as $keyword) {
			$keywordId = $keyword['keyword_id'];
			$keywordName = $keyword['keyword'];
	
			// Vérifier si le mot-clé existe déjà dans le résultat
			if (!isset($result[$keywordId])) {
				$result[$keywordId] = [
					'keyword_id' => $keywordId,
					'keyword' => $keywordName,
					'translations' => []
				];
			}
	
			// Récupérer les traductions pour le mot-clé
			$translations = $this->translationModel->getTranslationByTableAndId(TABLE_KEYWORDS, $keywordId);
	
			foreach ($translations as $translation) {
				$languageCode = $translation['language_code'];
				$value = $translation['value'];
	
				// Ajouter la traduction au tableau correspondant au mot-clé
				$result[$keywordId]['translations'][$languageCode] = $value;
			}
		}
	
		return array_values($result);
	}
	
    public function getKeyword($id) {
		$keyword = $this->keywordModel->getKeywordById($id);
		if ($keyword) {
			$keywordId = $keyword['keyword_id'];
			$keywordName = $keyword['keyword'];
	
			// Vérifier si le mot-clé existe déjà dans le résultat
			$result = [
				'keyword_id' => $keywordId,
				'keyword' => $keywordName,
				'translations' => []
			];
	
			// Récupérer les traductions pour le mot-clé
			$translations = $this->translationModel->getTranslationByTableAndId(TABLE_KEYWORDS, $keywordId);
	
			foreach ($translations as $translation) {
				$languageCode = $translation['language_code'];
				$value = $translation['value'];
	
				// Ajouter la traduction au tableau correspondant au mot-clé
				$result['translations'][$languageCode] = $value;
			}
	
			return $result;
		} else {
			return ['error' => 'Keyword not found'];
		}
	}

	// Json d'ajout :
	// {
	// 	"keyword": "Keyword 1",
	// 	"translations": {
	// 		"EN": "Translation in English",
	// 		"DE": "Translation in German",
	// 		"ES": "Translation in Spanish",
	// 		"FR": "Translation in French",
	// 		"IT": "Translation in Italian",
	// 		"PT": "Translation in Portuguese"
	// 	}
	// }	
    public function createKeyword($data) {
        $keywordName = $data['keyword'];
        $translations = $data['translations'];

        // Créer le mot-clé dans la base de données
        $keywordId = $this->keywordModel->createKeyword($keywordName);

        if ($keywordId) {
            // Créer les traductions pour le mot-clé
            foreach ($translations as $languageCode => $translation) {
                $this->translationModel->createTranslation(TABLE_KEYWORDS, $keywordId, $translation, $languageCode);
            }

            // Récupérer les traductions pour construire la réponse
            $responseTranslations = [];
            foreach ($translations as $languageCode => $translation) {
                $language = $this->languageModel->getLanguageByCode($languageCode);
                if ($language) {
                    $responseTranslations[$languageCode] = $translation;
                }
            }

            // Construire la réponse avec les informations du mot-clé et les traductions
            $response = [
                'keyword_id' => $keywordId,
                'keyword' => $keywordName,
                'translations' => $responseTranslations
            ];

            return $response;
        } else {
            return ['error' => 'Failed to create keyword'];
        }
    }

	// json de modification :
	// 	{
	// 		"keyword": "Modif du mot-clé"
	// 	  }
    public function updateKeyword($id, $data) {
		$keyword = $this->keywordModel->getKeywordById($id);
	
		if (!$keyword) {
			return ['error' => 'Keyword not found'];
		}
	
		$keywordName = isset($data['keyword']) ? $data['keyword'] : $keyword['keyword'];
	
		$result = $this->keywordModel->updateKeyword($id, $keywordName);
	
		if ($result > 0) {
			return ['message' => 'Keyword updated successfully'];
		} else {
			return ['error' => 'Keyword update failed'];
		}
	}
	
	

    public function deleteKeyword($id) {
		$keyword = $this->keywordModel->getKeywordById($id);
	
		if (!$keyword) {
			return ['error' => 'Keyword not found'];
		}
	
		// Supprimer le mot-clé de la table "keywords"
		$result = $this->keywordModel->deleteKeyword($id);
	
		// Supprimer les traductions associées au mot-clé de la table "translations"
		$this->translationModel->deleteTranslationByTableAndId(TABLE_KEYWORDS, $id);
	
		if ($result > 0) {
			return ['message' => 'Keyword deleted successfully'];
		} else {
			return ['error' => 'Keyword deletion failed'];
		}
	}
	
}
?>
