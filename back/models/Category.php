<?php
class Category {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCategories() {
        $query = "SELECT c.category_id, c.category, t.language_code, t.value
                  FROM categories c
                  LEFT JOIN translates t ON t.table_name = 'categories' AND t.row_id = c.category_id";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM categories WHERE category_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCategory($category) {
        $query = "INSERT INTO categories (category) VALUES (:category)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function updateCategory($id, $category) {
        $query = "UPDATE categories SET category = :category WHERE category_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function deleteCategory($id) {
        $query = "DELETE FROM categories WHERE category_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }
	//________________________________________
	// Methodes pour les autres controllers
	public function getCategoriesForSymbol($symbolId) {
		$query = "SELECT categories.category_id, categories.category
				  FROM categories
				  JOIN symbol_category ON categories.category_id = symbol_category.category_id
				  WHERE symbol_category.symbol_id = :symbolId";
		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
}
