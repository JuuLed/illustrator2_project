<?php
class Symbol
{
	protected $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getAllSymbols()
	{
		$query = "SELECT symbols.symbol_id, symbols.unique_id, symbols.file_name, symbols.symbol_name, symbols.size, symbols.active, 
						 categories.category_id, categories.category, 
						 keywords.keyword_id, keywords.keyword
				  FROM symbols 
				  LEFT JOIN symbol_category 
				  	ON symbols.symbol_id = symbol_category.symbol_id
				  LEFT JOIN categories 
				  	ON symbol_category.category_id = categories.category_id
				  LEFT JOIN symbol_keyword 
				  	ON symbols.symbol_id = symbol_keyword.symbol_id
				  LEFT JOIN keywords 
				  	ON symbol_keyword.keyword_id = keywords.keyword_id
				  WHERE symbols.deleted = 0";

		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSymbolById($id)
	{
		$query = "SELECT symbols.*, GROUP_CONCAT(DISTINCT categories.category_id) AS category_ids, GROUP_CONCAT(DISTINCT keywords.keyword_id) AS keyword_ids
                  FROM symbols
                  LEFT JOIN symbol_category 
				  	ON symbols.symbol_id = symbol_category.symbol_id
                  LEFT JOIN categories 
				  	ON symbol_category.category_id = categories.category_id
                  LEFT JOIN symbol_keyword 
				  	ON symbols.symbol_id = symbol_keyword.symbol_id
                  LEFT JOIN keywords 
				  	ON symbol_keyword.keyword_id = keywords.keyword_id
                  WHERE symbols.symbol_id = :id
				  	-- AND symbols.deleted = 0
                  GROUP BY symbols.symbol_id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		$symbol = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($symbol) {
			$symbol['category_ids'] = explode(',', $symbol['category_ids']);
			$symbol['keyword_ids'] = explode(',', $symbol['keyword_ids']);
		}

		return $symbol;
	}

	public function createSymbol($symbolName, $size = 50, $active = 0)
	{
		// Générer unique_id pour le nouveau symbole
		$uniqueId = bin2hex(openssl_random_pseudo_bytes(4));

		$query = "INSERT INTO 
					symbols (unique_id, symbol_name, size, active) 
              	  VALUES 
				  	(:uniqueId, :symbolName, :size, :active)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':uniqueId', $uniqueId);
		$stmt->bindParam(':symbolName', $symbolName);
		$stmt->bindParam(':size', $size);
		$stmt->bindParam(':active', $active);
		$stmt->execute();

		$symbolId = $this->pdo->lastInsertId();

		// Mettre à jour la ligne avec le file_name approprié
		$fileName = $uniqueId . '-' . $symbolId;

		$updateQuery = "UPDATE 
							symbols 
						SET 
							file_name = :fileName 
						WHERE 
							symbol_id = :symbolId";

		$updateStmt = $this->pdo->prepare($updateQuery);
		$updateStmt->bindParam(':fileName', $fileName);
		$updateStmt->bindParam(':symbolId', $symbolId);
		$updateStmt->execute();

		return $symbolId;
	}

	public function updateSymbol($id, $symbolName, $size, $active)
	{
		// Stocker les informations du symbole viser a etre modifié
		$symbolDeleted = $this->getSymbolById($id);

		// Récupérer unique_id pour le nouveau symbole modifier
		$uniqueId = $symbolDeleted['unique_id'];

		// // Marquer le symbole existant comme supprimé
		// $this->deleteSymbol($id);

		$query = "INSERT INTO 
					symbols (unique_id, symbol_name, size, active) 
              	  VALUES 
				  	(:uniqueId, :symbolName, :size, :active)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':uniqueId', $uniqueId);
		$stmt->bindParam(':symbolName', $symbolName);
		$stmt->bindParam(':size', $size);
		$stmt->bindParam(':active', $active);
		$stmt->execute();

		$symbolId = $this->pdo->lastInsertId();

		if ($symbolId) {
			// Marquer le symbole existant comme supprimé
			$this->deleteSymbol($id);
		}		

		// Mettre à jour la ligne avec le file_name approprié
		$fileName = $uniqueId . '-' . $symbolId;

		$updateQuery = "UPDATE 
							symbols 
						SET 
							file_name = :fileName 
						WHERE 
							symbol_id = :symbolId";

		$updateStmt = $this->pdo->prepare($updateQuery);
		$updateStmt->bindParam(':fileName', $fileName);
		$updateStmt->bindParam(':symbolId', $symbolId);
		$updateStmt->execute();

		return $symbolId;
	}

	public function deleteSymbol($id)
	{
		$query = "UPDATE 
					symbols 
				  SET 
				  	deleted = 1 
				  WHERE 
					symbol_id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		return $stmt->rowCount();
	}

	
	
}