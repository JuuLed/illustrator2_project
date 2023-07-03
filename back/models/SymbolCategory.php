<?php

class SymbolCategory
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getAllCategoriesBySymbolId($symbolId)
	{
		$query = "SELECT 
					c.* 
                  FROM 
				  	".TABLE_CATEGORIES." c
                  INNER JOIN ".TABLE_SYMBOL_CATEGORY." sc
                  	ON c.category_id = sc.category_id 
                  WHERE 
				  	sc.symbol_id = :symbolId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
		$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $categories;
	}

	public function addCategoryToSymbol($symbolId, $categoryId)
	{
		$query = "INSERT INTO 
					".TABLE_SYMBOL_CATEGORY." (symbol_id, category_id) 
                  VALUES 
				  	(:symbolId, :categoryId)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->bindParam(':categoryId', $categoryId);
		$stmt->execute();
	}

	public function removeCategoryFromSymbol($symbolId, $categoryId)
	{
		$query = "DELETE FROM 
					".TABLE_SYMBOL_CATEGORY." 
                  WHERE 
				  	symbol_id = :symbolId 
                  AND 
				  	category_id = :categoryId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->bindParam(':categoryId', $categoryId);
		$stmt->execute();
	}

	public function updateSymbolCategories($symbolId, $categoryIds)
	{	
		$query = "INSERT INTO 
					".TABLE_SYMBOL_CATEGORY." (symbol_id, category_id) 
				  VALUES 
					(:symbolId, :categoryId)";
	
		$stmt = $this->pdo->prepare($query);

		// Si un seul identifiant de categorie est fourni, le convertir en tableau
		if (!is_array($categoryIds)) {
			$categoryIds = [$categoryIds];
		}
	
		foreach ($categoryIds as $categoryId) {
			$stmt->bindParam(':symbolId', $symbolId);
			$stmt->bindParam(':categoryId', $categoryId);
			$stmt->execute();
		}
	}

	public function deleteSymbolCategories($symbolId)
	{
		$query = "DELETE FROM 
					".TABLE_SYMBOL_CATEGORY." 
				  WHERE 
				  	symbol_id = :symbolId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
	}



}