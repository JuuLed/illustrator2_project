<?php

require_once './config/database.php';
require_once './models/SymbolCategory.php';

class SymbolCategoryController {
    private $symbolCategoryModel;

    public function __construct() {
        global $pdo;
        $this->symbolCategoryModel = new SymbolCategory($pdo);
    }

    public function getAllCategoriesBySymbolId($symbolId) {
        $categories = $this->symbolCategoryModel->getAllCategoriesBySymbolId($symbolId);
        return ['categories' => $categories];
    }

    public function addCategoryToSymbol($symbolId, $categoryId) {
        $this->symbolCategoryModel->addCategoryToSymbol($symbolId, $categoryId);
        return ['message' => 'Category added to symbol successfully'];
    }

    public function removeCategoryFromSymbol($symbolId, $categoryId) {
        $this->symbolCategoryModel->removeCategoryFromSymbol($symbolId, $categoryId);
        return ['message' => 'Category removed from symbol successfully'];
    }
}
