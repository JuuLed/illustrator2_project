<?php
class Category {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCategories() {
        $query = "SELECT 
					categories.category_id, categories.category, categories.order, 
					translations.language_code, translations.value
                  FROM 
				  	categories
                  LEFT JOIN translations 
				  	ON translations.table_name = 'categories' 
					AND translations.row_id = categories.category_id
				  ORDER BY 
				  	categories.order ASC";

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
}
