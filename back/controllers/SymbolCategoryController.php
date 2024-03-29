<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/SymbolCategory.php';

class SymbolCategoryController
{
	protected $symbolCategoryModel;

	public function __construct(SymbolCategory $symbolCategoryModel = null)
	{
		global $pdo;
		$this->symbolCategoryModel = $symbolCategoryModel ? $symbolCategoryModel : new SymbolCategory($pdo);
	}

	public function getAllCategoriesBySymbolId($symbolId)
	{
		$categories = $this->symbolCategoryModel->getAllCategoriesBySymbolId($symbolId);
		return ['categories' => $categories];
	}

	public function addCategoryToSymbol($symbolId, $categoryId)
	{
		$this->symbolCategoryModel->addCategoryToSymbol($symbolId, $categoryId);
		return ['message' => 'Category added to symbol successfully'];
	}

	public function removeCategoryFromSymbol($symbolId, $categoryId)
	{
		$this->symbolCategoryModel->removeCategoryFromSymbol($symbolId, $categoryId);
		return ['message' => 'Category removed from symbol successfully'];
	}
}