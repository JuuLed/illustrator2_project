<?php

class Translation
{
	protected $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getAllTranslations()
	{
		$query = "SELECT 
					* 
				  FROM 
					translations";

		$stmt = $this->pdo->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTranslationByTableAndId($table, $id)
	{
		$query = "SELECT 
					* 
				  FROM 
					translations 
				  WHERE 
					table_name = :table 
				  AND 
					row_id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':table', $table);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function createTranslation($table, $id, $value, $langCode)
	{
		$query = "INSERT INTO 
					translations (table_name, row_id, value, language_code) 
				  VALUES 
					(:table_name, :row_id, :value, :language_code)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':table_name', $table);
		$stmt->bindParam(':row_id', $id);
		$stmt->bindParam(':value', $value);
		$stmt->bindParam(':language_code', $langCode);
		$stmt->execute();

		return $this->pdo->lastInsertId();
	}

	public function updateTranslationByTableAndId($table, $id, $value, $langCode)
	{
		$currentTranslation = $this->getTranslationByTableAndId($table, $id);

		if (!$currentTranslation) {
			return 0; // La traduction n'existe pas, retourne 0 pour indiquer l'échec de la mise à jour
		}

		$newValue = $value !== '' ? $value : $currentTranslation['value'];
		$newLangCode = $langCode !== '' ? $langCode : $currentTranslation['language_code'];

		if ($newValue === $currentTranslation['value'] && $newLangCode === $currentTranslation['language_code']) {
			return 0; // Aucun champ à mettre à jour, retourne 0 pour indiquer l'absence de modification
		}

		$query = "UPDATE 
					translations 
				  SET 
					value = :value, 
					language_code = :language_code 
				  WHERE 
					table_name = :table 
				  AND 
					row_id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':table', $table);
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':value', $newValue);
		$stmt->bindParam(':language_code', $newLangCode);
		$stmt->execute();

		return $stmt->rowCount();
	}


	public function deleteTranslationByTableAndId($table, $id)
	{
		$query = "DELETE FROM 
					translations 
				  WHERE 
					table_name = :table 
				  AND 
					row_id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':table', $table);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		return $stmt->rowCount();
	}
}

?>