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
		$query = "SELECT symbols.symbol_id, symbols.unique_id, symbols.file_name, symbols.size, symbols.active, 
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
				  	AND symbols.deleted = 0
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

	public function createSymbol($fileName, $size, $active
	// , $categoryIds, $keywordIds
	)
	{
		// Générer unique_id pour le nouveau symbole
		$uniqueId = bin2hex(openssl_random_pseudo_bytes(4));

		$query = "INSERT INTO 
					symbols (unique_id, file_name, size, active) 
				  VALUES 
				  	(:uniqueId, :fileName, :size, :active)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':uniqueId', $uniqueId);
		$stmt->bindParam(':fileName', $fileName);
		$stmt->bindParam(':size', $size);
		$stmt->bindParam(':active', $active);
		$stmt->execute();

		$symbolId = $this->pdo->lastInsertId();

		// $this->updateSymbolCategories($symbolId, $categoryIds);
		// $this->updateSymbolKeywords($symbolId, $keywordIds);

		return $symbolId;
	}

	public function updateSymbol($id, $fileName, $size, $active
	// , $categoryIds, $keywordIds
	)
	{
		// Marquer le symbole existant comme supprimé
		$this->deleteSymbol($id);

		// Créer un nouveau symbole avec le même unique_id et les nouveaux attributs
		$symbol = $this->getSymbolById($id);
		$newSymbolId = $this->createSymbol(
			$fileName, $size, $active
			// , $categoryIds, $keywordIds
		);

		$query = "UPDATE 
					symbols 
				  SET 
				  	unique_id = :uniqueId 
				  WHERE 
				  	symbol_id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':uniqueId', $symbol['unique_id']);
		$stmt->bindParam(':id', $newSymbolId);
		$stmt->execute();

		return $stmt->rowCount();
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


	// //________________________________________
	// // Methodes pour symbol_category
	// private function updateSymbolCategories($symbolId, $categoryIds)
	// {
	// 	// Supprimer les anciennes entrées de la table de liaison
	// 	$this->deleteSymbolCategories($symbolId);

	// 	// Insérer les nouvelles entrées dans la table de liaison
	// 	$query = "INSERT INTO 
	// 				symbol_category (symbol_id, category_id) 
	// 			  VALUES 
	// 				(:symbolId, :categoryId)";

	// 	$stmt = $this->pdo->prepare($query);

	// 	foreach ($categoryIds as $categoryId) {
	// 		$stmt->bindParam(':symbolId', $symbolId);
	// 		$stmt->bindParam(':categoryId', $categoryId);
	// 		$stmt->execute();
	// 	}
	// }

	// private function deleteSymbolCategories($symbolId)
	// {
	// 	$query = "DELETE FROM 
	// 				symbol_category 
	// 			  WHERE 
	// 			  	symbol_id = :symbolId";

	// 	$stmt = $this->pdo->prepare($query);
	// 	$stmt->bindParam(':symbolId', $symbolId);
	// 	$stmt->execute();
	// }

	// //________________________________________
	// // Methodes pour symbol_keyword
	// private function updateSymbolKeywords($symbolId, $keywordIds)
	// {
	// 	// Supprimer les anciennes entrées de la table de liaison
	// 	$this->deleteSymbolKeywords($symbolId);

	// 	// Insérer les nouvelles entrées dans la table de liaison
	// 	$query = "INSERT INTO 
	// 				symbol_keyword (symbol_id, keyword_id) 
	// 			  VALUES 
	// 				(:symbolId, :keywordId)";

	// 	$stmt = $this->pdo->prepare($query);

	// 	foreach ($keywordIds as $keywordId) {
	// 		$stmt->bindParam(':symbolId', $symbolId);
	// 		$stmt->bindParam(':keywordId', $keywordId);
	// 		$stmt->execute();
	// 	}
	// }

	// private function deleteSymbolKeywords($symbolId)
	// {
	// 	$query = "DELETE FROM 
	// 				symbol_keyword 
	// 			  WHERE 
	// 			  	symbol_id = :symbolId";

	// 	$stmt = $this->pdo->prepare($query);
	// 	$stmt->bindParam(':symbolId', $symbolId);
	// 	$stmt->execute();
	// }
}