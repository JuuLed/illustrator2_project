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
					".TABLE_TRANSLATIONS;

		$stmt = $this->pdo->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getTranslationByTableAndId($table, $id)
	{
		$query = "SELECT 
					* 
				  FROM 
					".TABLE_TRANSLATIONS." 
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
					".TABLE_TRANSLATIONS." (table_name, row_id, value, language_code) 
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

	public function updateTranslationByTableAndId($table, $id, $translations)
	{
		$currentTranslation = $this->getTranslationByTableAndId($table, $id);
	
		if (!$currentTranslation) {
			return 0; // La traduction n'existe pas, retourne 0 pour indiquer l'échec de la mise à jour
		}
	
		$updatedTranslations = 0;
	
		foreach ($translations as $langCode => $value) {
			$currentValue = isset($currentTranslation['value']) ? $currentTranslation['value'] : null;
			$value = isset($value) ? $value : $currentValue;
	
			$currentLangCode = isset($currentTranslation['language_code']) ? $currentTranslation['language_code'] : null;
			$newLangCode = isset($langCode) && $langCode !== '' ? $langCode : $currentLangCode;
	
			if ($value !== $currentValue || $newLangCode !== $currentLangCode) {
				$query = "UPDATE 
							".TABLE_TRANSLATIONS."  
						  SET 
							value = :value 
						  WHERE 
							table_name = :table 
						  AND 
							row_id = :id 
						  AND 
							language_code = :language_code";
	
				$stmt = $this->pdo->prepare($query);
				$stmt->bindParam(':table', $table);
				$stmt->bindParam(':id', $id);
				$stmt->bindParam(':value', $value);
				$stmt->bindParam(':language_code', $newLangCode);
				$stmt->execute();
	
				$updatedTranslations += $stmt->rowCount();
			}
		}
	
		return $updatedTranslations;
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