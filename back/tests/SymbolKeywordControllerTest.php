<?php

echo "Test Controller of Symbol-Keyword script is running...\n";

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controllers/SymbolKeywordController.php';

use PHPUnit\Framework\TestCase;

class SymbolKeywordControllerTest extends TestCase
{
    private $symbolKeywordController;

    public function setUp(): void
    {
        $symbolKeywordModel = $this->getMockBuilder('SymbolKeyword')
            ->setMethods(['getAllKeywordsBySymbolId', 'addKeywordToSymbol', 'removeKeywordFromSymbol'])
            ->disableOriginalConstructor()
            ->getMock();

        $symbolKeywordModel->method('getAllKeywordsBySymbolId')->willReturn([
            ['keyword_id' => 1, 'keyword' => 'testKeyword']
        ]);

        $symbolKeywordModel->method('addKeywordToSymbol')->willReturn(true);
        $symbolKeywordModel->method('removeKeywordFromSymbol')->willReturn(true);

        $this->symbolKeywordController = new SymbolKeywordController($symbolKeywordModel);
    }

    public function testGetAllKeywordsBySymbolId()
    {
        $result = $this->symbolKeywordController->getAllKeywordsBySymbolId(1);
        $this->assertTrue(is_array($result), "getAllKeywordsBySymbolId should return an array.");
        $this->assertArrayHasKey('keywords', $result, "Array should have 'keywords' key.");
    }

    public function testAddKeywordToSymbol()
    {
        $result = $this->symbolKeywordController->addKeywordToSymbol(1, 1);
        $this->assertTrue(is_array($result), "addKeywordToSymbol should return an array.");
        $this->assertArrayHasKey('message', $result, "Array should have 'message' key.");
        $this->assertEquals('Keyword added to symbol successfully', $result['message'], "Message should match.");
    }

    public function testRemoveKeywordFromSymbol()
    {
        $result = $this->symbolKeywordController->removeKeywordFromSymbol(1, 1);
        $this->assertTrue(is_array($result), "removeKeywordFromSymbol should return an array.");
        $this->assertArrayHasKey('message', $result, "Array should have 'message' key.");
        $this->assertEquals('Keyword removed from symbol successfully', $result['message'], "Message should match.");
    }
}
