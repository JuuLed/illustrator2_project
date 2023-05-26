<?php

class SymbolCategory {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCategoriesBySymbolId($symbolId) {
        $query = "SELECT c.* 
                  FROM categories c 
                  INNER JOIN symbol_category sc 
                  	ON c.category_id = sc.category_id 
                  WHERE sc.symbol_id = :symbolId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->execute();
		$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $categories;
    }

    public function addCategoryToSymbol($symbolId, $categoryId) {
        $query = "INSERT INTO symbol_category (symbol_id, category_id) 
                  VALUES (:symbolId, :categoryId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();
    }

    public function removeCategoryFromSymbol($symbolId, $categoryId) {
        $query = "DELETE FROM symbol_category 
                  WHERE symbol_id = :symbolId 
                  AND category_id = :categoryId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();
    }

}
