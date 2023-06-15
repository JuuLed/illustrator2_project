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
					categories.* 
                  FROM 
				  	categories
                  INNER JOIN symbol_category
                  	ON categories.category_id = symbol_category.category_id 
                  WHERE 
				  	symbol_category.symbol_id = :symbolId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
		$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $categories;
	}

	public function addCategoryToSymbol($symbolId, $categoryId)
	{
		$query = "INSERT INTO 
					symbol_category (symbol_id, category_id) 
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
					symbol_category 
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
					symbol_category (symbol_id, category_id) 
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
					symbol_category 
				  WHERE 
				  	symbol_id = :symbolId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
	}



}