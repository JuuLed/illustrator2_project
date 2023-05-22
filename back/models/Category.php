<?php
class Category {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllCategories() {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($categoryId) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCategory($categoryName, $lang) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (category_name, lang) VALUES (?, ?)");
        $stmt->execute([$categoryName, $lang]);
        return $this->pdo->lastInsertId();
    }

    public function updateCategory($categoryId, $categoryName, $lang) {
        $stmt = $this->pdo->prepare("UPDATE categories SET category_name = ?, lang = ? WHERE category_id = ?");
        $stmt->execute([$categoryName, $lang, $categoryId]);
        return $stmt->rowCount();
    }

    public function deleteCategory($categoryId) {
        // Delete associations between the category and symbols in the symbol_category table
        $stmt = $this->pdo->prepare("DELETE FROM symbol_category WHERE category_id = ?");
        $stmt->execute([$categoryId]);

        // Delete the category from the categories table
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->execute([$categoryId]);

        return $stmt->rowCount();
    }

    public function getCategoriesBySymbol($symbolId) {
        $stmt = $this->pdo->prepare("SELECT categories.* FROM categories
            INNER JOIN symbol_category ON categories.category_id = symbol_category.category_id
            WHERE symbol_category.symbol_id = ?");
        $stmt->execute([$symbolId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
	public function removeSymbolFromAllCategories($symbolId) {
		$stmt = $this->pdo->prepare("DELETE FROM symbol_category WHERE symbol_id = ?");
		$stmt->execute([$symbolId]);
	}
	
	public function addSymbolToCategory($symbolId, $categoryId) {
		$stmt = $this->pdo->prepare("INSERT INTO symbol_category (symbol_id, category_id) VALUES (?, ?)");
		$stmt->execute([$symbolId, $categoryId]);
	}
	
}
