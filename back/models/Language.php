<?php
class Language
{
	protected $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getAllLanguages()
	{
		$query = "SELECT 
					* 
				  FROM 
				  	".TABLE_LANGUAGES;

		$stmt = $this->pdo->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getLanguageByCode($code)
	{
		$query = "SELECT 
					* 
				  FROM 
				  	".TABLE_LANGUAGES."
				  WHERE 
					language_code = :code";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':code', $code);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function createLanguage($code, $name)
	{
		$query = "INSERT INTO 
					".TABLE_LANGUAGES." (language_code, language_name) 
				  VALUES 
					(:code, :name)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':code', $code);
		$stmt->bindParam(':name', $name);
		$stmt->execute();

		return $this->pdo->lastInsertId();
	}

	public function updateLanguage($code, $name)
	{
		$query = "UPDATE 
					".TABLE_LANGUAGES." 
				  SET 
					language = :name 
				  WHERE 
					language_code = :code";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':code', $code);
		$stmt->execute();

		return $stmt->rowCount();
	}

	public function deleteLanguage($code)
	{
		$query = "DELETE FROM 
					".TABLE_LANGUAGES." 
				  WHERE 
					language_code = :code";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':code', $code);
		$stmt->execute();

		return $stmt->rowCount();
	}
}
?>