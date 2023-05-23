<?php
class Category {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCategories() {
        $query = "SELECT * FROM categories";
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

    public function createCategory($name, $languageCode) {
        $query = "INSERT INTO categories (category_name, language_code) VALUES (:name, :languageCode)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':languageCode', $languageCode);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function updateCategory($id, $name, $languageCode) {
        $query = "UPDATE categories SET category_name = :name, language_code = :languageCode WHERE category_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':languageCode', $languageCode);
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
?>