<?php

echo "Test script is running...\n";

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controllers/TranslationController.php';

use PHPUnit\Framework\TestCase;

class TranslationControllerTest extends TestCase
{
    private $translationController;

    public function setUp()
    {
        $translationModel = $this->getMockBuilder('Translation')
            ->setMethods(['getAllTranslations', 'getTranslationByTableAndId', 'createTranslation', 'updateTranslationByTableAndId', 'deleteTranslationByTableAndId'])
            ->disableOriginalConstructor()
            ->getMock();

        $translationModel->method('getAllTranslations')->willReturn([]);
        $translationModel->method('getTranslationByTableAndId')->willReturn(['table_name' => 'test', 'row_id' => 1, 'value' => 'testValue', 'language_code' => 'en']);
        $translationModel->method('createTranslation')->willReturn(1);
        $translationModel->method('updateTranslationByTableAndId')->willReturn(1);
        $translationModel->method('deleteTranslationByTableAndId')->willReturn(1);

        $this->translationController = new TranslationController($translationModel);
    }

    public function testGetAllTranslations()
    {
        $result = $this->translationController->getAllTranslations();
        $this->assertTrue(is_array($result), "getAllTranslations should return an array.");
    }

    public function testGetTranslationByTableAndId()
    {
        $result = $this->translationController->getTranslationByTableAndId('test', 1);
        $this->assertTrue(is_array($result), "getTranslationByTableAndId should return an array.");
        $this->assertArrayHasKey('table_name', $result, "Array should have 'table_name' key.");
        $this->assertArrayHasKey('row_id', $result, "Array should have 'row_id' key.");
        $this->assertArrayHasKey('value', $result, "Array should have 'value' key.");
        $this->assertArrayHasKey('language_code', $result, "Array should have 'language_code' key.");
    }

    public function testCreateTranslation()
    {
        $result = $this->translationController->createTranslation(['table_name' => 'test', 'row_id' => 1, 'value' => 'testValue', 'language_code' => 'en']);
        $this->assertTrue(is_array($result), "createTranslation should return an array.");
        $this->assertArrayHasKey('table_name', $result, "Array should have 'table_name' key.");
        $this->assertArrayHasKey('row_id', $result, "Array should have 'row_id' key.");
        $this->assertArrayHasKey('value', $result, "Array should have 'value' key.");
        $this->assertArrayHasKey('language_code', $result, "Array should have 'language_code' key.");
    }

    public function testUpdateTranslation()
    {
        $result = $this->translationController->updateTranslation('test', 1, ['en' => 'updatedTestValue']);
        $this->assertTrue(is_array($result), "updateTranslation should return an array.");
        $this->assertArrayHasKey('message', $result, "Array should have 'message' key.");
        $this->assertEquals('Translations updated successfully', $result['message'], "Message should be 'Translations updated successfully'.");
    }

    public function testDeleteTranslation()
    {
        $result = $this->translationController->deleteTranslation('test', 1);
        $this->assertTrue(is_array($result), "deleteTranslation should return an array.");
        $this->assertArrayHasKey('message', $result, "Array should have 'message' key.");
        $this->assertEquals('Translation deleted successfully', $result['message'], "Message should be 'Translation deleted successfully'.");
    }
}
