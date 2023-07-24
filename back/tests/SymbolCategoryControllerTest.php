<?php

echo "Test Controller of Symbol-Category script is running...\n";

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controllers/SymbolCategoryController.php';

use PHPUnit\Framework\TestCase;

class SymbolCategoryControllerTest extends TestCase
{
    private $symbolCategoryController;

    public function setUp(): void
    {
        $symbolCategoryModel = $this->getMockBuilder('SymbolCategory')
            ->setMethods(['getAllCategoriesBySymbolId', 'addCategoryToSymbol', 'removeCategoryFromSymbol'])
            ->disableOriginalConstructor()
            ->getMock();

        $symbolCategoryModel->method('getAllCategoriesBySymbolId')->willReturn([
            ['category_id' => 1, 'category' => 'testCategory']
        ]);

        $symbolCategoryModel->method('addCategoryToSymbol')->willReturn(true);
        $symbolCategoryModel->method('removeCategoryFromSymbol')->willReturn(true);

        $this->symbolCategoryController = new SymbolCategoryController($symbolCategoryModel);
    }

    public function testGetAllCategoriesBySymbolId()
    {
        $result = $this->symbolCategoryController->getAllCategoriesBySymbolId(1);
        $this->assertTrue(is_array($result), "getAllCategoriesBySymbolId should return an array.");
        $this->assertArrayHasKey('categories', $result, "Array should have 'categories' key.");
    }

    public function testAddCategoryToSymbol()
    {
        $result = $this->symbolCategoryController->addCategoryToSymbol(1, 1);
        $this->assertTrue(is_array($result), "addCategoryToSymbol should return an array.");
        $this->assertArrayHasKey('message', $result, "Array should have 'message' key.");
        $this->assertEquals('Category added to symbol successfully', $result['message'], "Message should match.");
    }

    public function testRemoveCategoryFromSymbol()
    {
        $result = $this->symbolCategoryController->removeCategoryFromSymbol(1, 1);
        $this->assertTrue(is_array($result), "removeCategoryFromSymbol should return an array.");
        $this->assertArrayHasKey('message', $result, "Array should have 'message' key.");
        $this->assertEquals('Category removed from symbol successfully', $result['message'], "Message should match.");
    }
}
