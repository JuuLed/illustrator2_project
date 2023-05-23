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
        $categoryName = $data['category_name'];
        $languageCode = $data['language_code'];

        $result = $this->categoryModel->createCategory($categoryName, $languageCode);

        if ($result) {
            return ['category_id' => $result, 'category_name' => $categoryName, 'language_code' => $languageCode];
        } else {
            return ['error' => 'Failed to create category'];
        }
    }

    public function updateCategory($id, $data) {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            return ['error' => 'Category not found'];
        }

        $categoryName = isset($data['category_name']) ? $data['category_name'] : $category['category_name'];
        $languageCode = isset($data['language_code']) ? $data['language_code'] : $category['language_code'];

        $result = $this->categoryModel->updateCategory($id, $categoryName, $languageCode);

        if ($result > 0) {
            return ['message' => 'Category updated successfully'];
        } else {
            return ['error' => 'Category update failed'];
        }
    }

    public function deleteCategory($id) {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            return ['error' => 'Category not found'];
        }

        $result = $this->categoryModel->deleteCategory($id);

        if ($result > 0) {
            return ['message' => 'Category deleted successfully'];
        } else {
            return ['error' => 'Category deletion failed'];
        }
    }
}
?>