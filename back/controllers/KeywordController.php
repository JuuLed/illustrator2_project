<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Keyword.php';
require_once __DIR__ . '/../models/Translation.php';
require_once __DIR__ . '/../models/Language.php';

class KeywordController
{
	protected $keywordModel;
	protected $translationModel;
	protected $languageModel;

	public function __construct(Keyword $keywordModel = null, Translation $translationModel = null, Language $languageModel = null)
    {
        global $pdo;
        $this->keywordModel = $keywordModel ? $keywordModel : new Keyword($pdo);
        $this->translationModel = $translationModel ? $translationModel : new Translation($pdo);
        $this->languageModel = $languageModel ? $languageModel : new Language($pdo);

    }

	public function getAllKeywords()
	{
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

	public function getKeyword($id)
	{
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

	public function createKeyword($data)
	{
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

	public function updateKeyword($id, $data)
	{
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

	public function deleteKeyword($id)
	{
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