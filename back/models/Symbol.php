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
		$query = "SELECT 
					s.symbol_id, s.unique_id, s.file_name, s.symbol_name, s.size, s.active, 
					c.category_id, c.category, 
					k.keyword_id, k.keyword
				  FROM 
				  	".TABLE_SYMBOLS." s
				  LEFT JOIN ".TABLE_SYMBOL_CATEGORY." sc
				  	ON s.symbol_id = sc.symbol_id
				  LEFT JOIN ".TABLE_CATEGORIES." c
				  	ON sc.category_id = c.category_id
				  LEFT JOIN ".TABLE_SYMBOL_KEYWORD." sk
				  	ON s.symbol_id = sk.symbol_id
				  LEFT JOIN ".TABLE_KEYWORDS." k
				  	ON sk.keyword_id = k.keyword_id
				  WHERE s.deleted = 0";

		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSymbolById($id)
	{
		$query = "SELECT s.*, GROUP_CONCAT(DISTINCT c.category_id) AS category_ids, GROUP_CONCAT(DISTINCT k.keyword_id) AS keyword_ids
                  FROM ".TABLE_SYMBOLS." s
                  LEFT JOIN ".TABLE_SYMBOL_CATEGORY." sc
				  	ON s.symbol_id = sc.symbol_id
                  LEFT JOIN ".TABLE_CATEGORIES." c
				  	ON sc.category_id = c.category_id
                  LEFT JOIN ".TABLE_SYMBOL_KEYWORD." sk
				  	ON s.symbol_id = sk.symbol_id
                  LEFT JOIN ".TABLE_KEYWORDS." k
				  	ON sk.keyword_id = k.keyword_id
                  WHERE s.symbol_id = :id
                  GROUP BY s.symbol_id";

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
					".TABLE_SYMBOLS." (unique_id, symbol_name, size, active) 
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
							".TABLE_SYMBOLS." 
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
		$query = "UPDATE 
					".TABLE_SYMBOLS."
				  SET 
					  symbol_name = :symbolName, size = :size, active = :active 
				  WHERE 
					symbol_id = :id";
	
		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolName', $symbolName);
		$stmt->bindParam(':size', $size);
		$stmt->bindParam(':active', $active);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	
		return $stmt->rowCount();
	}
	

	public function deleteSymbol($id)
	{
		$query = "UPDATE 
					".TABLE_SYMBOLS." 
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