<?php
require_once './config/database.php';
require_once './models/Language.php';

class LanguageController
{
	protected $languageModel;

	public function __construct()
	{
		global $pdo;
		$this->languageModel = new Language($pdo);
	}

	public function getAllLanguages()
	{
		return $this->languageModel->getAllLanguages();
	}

	public function getLanguage($code)
	{
		$language = $this->languageModel->getLanguageByCode($code);
		if ($language) {
			return $language;
		} else {
			return ['error' => 'Language not found'];
		}
	}

	public function createLanguage($data)
	{
		$languageCode = $data['language_code'];
		$languageName = $data['language_name'];

		$result = $this->languageModel->createLanguage($languageCode, $languageName);

		if ($result) {
			return ['language_code' => $languageCode, 'language_name' => $languageName];
		} else {
			return ['error' => 'Failed to create language'];
		}
	}

	public function updateLanguage($code, $data)
	{
		$language = $this->languageModel->getLanguageByCode($code);

		if (!$language) {
			return ['error' => 'Language not found'];
		}

		$languageName = isset($data['language_name']) ? $data['language_name'] : $language['language_name'];

		$result = $this->languageModel->updateLanguage($code, $languageName);

		if ($result > 0) {
			return ['message' => 'Language updated successfully'];
		} else {
			return ['error' => 'Language update failed'];
		}
	}

	public function deleteLanguage($code)
	{
		$language = $this->languageModel->getLanguageByCode($code);

		if (!$language) {
			return ['error' => 'Language not found'];
		}

		$result = $this->languageModel->deleteLanguage($code);

		if ($result > 0) {
			return ['message' => 'Language deleted successfully'];
		} else {
			return ['error' => 'Language deletion failed'];
		}
	}
}
?>