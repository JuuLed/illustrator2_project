<?php
require_once './config/database.php';
require_once './models/Category.php';

class CategoryController {
    protected $categoryModel;

    public function __construct() {
        global $pdo;
        $this->categoryModel = new Category($pdo);
    }

    public function getAllCategories() {
        return $this->categoryModel->getAllCategories();
    }

    public function getCategory($id) {
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            return $category;
        } else {
            return ['error' => 'Category not found'];
        }
    }

    public function createCategory($data) {
        $categoryId = $this->categoryModel->addCategory($data['category_name'], $data['lang']);
        if (is_numeric($categoryId)) {
            return ['category_id' => $categoryId];
        } else {
            return ['error' => 'Failed to create category'];
        }
    }

    public function updateCategory($id, $data) {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            return ['error' => 'Category not found'];
        }

        $result = $this->categoryModel->updateCategory($id, $data['category_name'], $data['lang']);

        if ($result > 0) {
            return ['message' => 'Category updated successfully'];
        } else {
            return ['error' => 'Category update failed'];
        }
    }

    public function deleteCategory($id) {
        $result = $this->categoryModel->deleteCategory($id);
        if ($result > 0) {
            return ['message' => 'Category deleted successfully'];
        } else {
            return ['error' => 'Category delete failed'];
        }
    }
	public function removeSymbolFromAllCategories($symbolId) {
		$this->categoryModel->removeSymbolFromAllCategories($symbolId);
	}
	
	public function addSymbolToCategory($symbolId, $categoryId) {
		$this->categoryModel->addSymbolToCategory($symbolId, $categoryId);
	}
	
}
