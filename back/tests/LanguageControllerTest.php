<?php

echo "Test Controller of Language script is running...\n";

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controllers/LanguageController.php';

use PHPUnit\Framework\TestCase;

class LanguageControllerTest extends TestCase
{
    private $languageController;

    public function setUp()
    {
        $languageModel = $this->getMockBuilder('Language')
            ->setMethods(['getAllLanguages', 'getLanguageByCode', 'createLanguage', 'updateLanguage', 'deleteLanguage'])
            ->disableOriginalConstructor()
            ->getMock();

        $languageModel->method('getAllLanguages')->willReturn([
            ['language_code' => 'en', 'language_name' => 'English'],
            ['language_code' => 'fr', 'language_name' => 'French']
        ]);

        $languageModel->method('getLanguageByCode')->willReturnCallback(function($code) {
            if ($code == 'en') {
                return ['language_code' => 'en', 'language_name' => 'English'];
            }
            return null;
        });

        $languageModel->method('createLanguage')->willReturn(true);
        $languageModel->method('updateLanguage')->willReturn(1);
        $languageModel->method('deleteLanguage')->willReturn(1);

        $this->languageController = new LanguageController($languageModel);
    }

    public function testGetAllLanguages()
    {
        $result = $this->languageController->getAllLanguages();
        $this->assertTrue(is_array($result), "getAllLanguages should return an array.");
    }

    public function testGetLanguage()
    {
        $result = $this->languageController->getLanguage('en');
        $this->assertTrue(is_array($result), "getLanguage should return an array.");
        $this->assertArrayHasKey('language_name', $result, "Array should have 'language_name' key.");
        $this->assertEquals('English', $result['language_name'], "Language name should match.");

        $result = $this->languageController->getLanguage('de');
        $this->assertTrue(is_array($result), "getLanguage should return an array for non-existent language.");
        $this->assertArrayHasKey('error', $result, "Array should have 'error' key for non-existent language.");
    }

    public function testCreateLanguage()
    {
        $result = $this->languageController->createLanguage(['language_code' => 'de', 'language_name' => 'German']);
        $this->assertTrue(is_array($result), "createLanguage should return an array.");
        $this->assertArrayHasKey('language_code', $result, "Array should have 'language_code' key.");
    }

    public function testUpdateLanguage()
    {
        $result = $this->languageController->updateLanguage('en', ['language_name' => 'American English']);
        $this->assertTrue(is_array($result), "updateLanguage should return an array.");
        $this->assertArrayHasKey('message', $result, "Array should have 'message' key.");
    }

    public function testDeleteLanguage()
    {
        $result = $this->languageController->deleteLanguage('en');
        $this->assertTrue(is_array($result), "deleteLanguage should return an array.");
        $this->assertArrayHasKey('message', $result, "Array should have 'message' key.");
    }
}
