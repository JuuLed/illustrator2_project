<?php

class SymbolCategory {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCategoriesBySymbolId($symbolId) {
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

    public function addCategoryToSymbol($symbolId, $categoryId) {
        $query = "INSERT INTO 
					symbol_category (symbol_id, category_id) 
                  VALUES 
				  	(:symbolId, :categoryId)";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();
    }

    public function removeCategoryFromSymbol($symbolId, $categoryId) {
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


	//________________________________________
	// Methodes pour symbol_category
	private function updateSymbolCategories($symbolId, $categoryIds) {
		// Supprimer les anciennes entrées de la table de liaison
		$this->deleteSymbolCategories($symbolId);

		// Insérer les nouvelles entrées dans la table de liaison
		$query = "INSERT INTO 
					symbol_category (symbol_id, category_id) 
				  VALUES 
					(:symbolId, :categoryId)";

		$stmt = $this->pdo->prepare($query);

		foreach ($categoryIds as $categoryId) {
			$stmt->bindParam(':symbolId', $symbolId);
			$stmt->bindParam(':categoryId', $categoryId);
			$stmt->execute();
		}
	}

	private function deleteSymbolCategories($symbolId) {
		$query = "DELETE FROM 
					symbol_category 
				  WHERE 
				  	symbol_id = :symbolId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
	}



}
