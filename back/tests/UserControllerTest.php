<?php

echo "Test script is running...\n";

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../utils/CookieSetter.php';

use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{
    private $userController;

    public function setUp()
    {
        $userModel = $this->getMockBuilder('User')
            ->setMethods(['getAllUsers', 'getUserById', 'register', 'login'])
            ->disableOriginalConstructor()
            ->getMock();

        $userModel->method('getAllUsers')->willReturn([]);
        $userModel->method('getUserById')->willReturn(['user_id' => 1, 'username' => 'test', 'email' => 'test@example.com']);
        $userModel->method('register')->willReturn(['statut' => 'Registration success!', 'username' => 'test', 'token' => 'testToken', 'user_id' => 1]);
        $userModel->method('login')->willReturn(['statut' => 'Login success!', 'username' => 'test', 'token' => 'testToken', 'user_id' => 1]);

        $cookieSetter = $this->getMockBuilder('CookieSetter')
            ->setMethods(['set'])
            ->getMock();
        $cookieSetter->method('set')->willReturn(null);

        $this->userController = new UserController($userModel, $cookieSetter);
    }

    public function testGetAllUsers()
    {
        $result = $this->userController->getAllUsers();
        $this->assertTrue(is_array($result), "getAllUsers should return an array.");
    }

    public function testGetUserById()
    {
        $result = $this->userController->getUserById(1);
        $this->assertTrue(is_array($result), "getUserById should return an array.");
        $this->assertArrayHasKey('user_id', $result, "Array should have 'user_id' key.");
        $this->assertArrayHasKey('username', $result, "Array should have 'username' key.");
        $this->assertArrayHasKey('email', $result, "Array should have 'email' key.");
    }

    public function testRegister()
    {
        $result = $this->userController->register('test', 'test@example.com', 'password');
        $this->assertTrue(is_array($result), "register should return an array.");
        $this->assertArrayHasKey('statut', $result, "Array should have 'statut' key.");
        $this->assertEquals('Registration success!', $result['statut'], "Statut should be 'Registration success!'.");
        $this->assertArrayHasKey('username', $result, "Array should have 'username' key.");
        $this->assertEquals('test', $result['username'], "Username should match.");
        $this->assertArrayHasKey('token', $result, "Array should have 'token' key.");
    }

    public function testLogin()
    {
        $result = $this->userController->login('test@example.com', 'password');
        $this->assertTrue(is_array($result), "login should return an array.");
        $this->assertArrayHasKey('statut', $result, "Array should have 'statut' key.");
        $this->assertEquals('Login success!', $result['statut'], "Statut should be 'Login success!'.");
        $this->assertArrayHasKey('username', $result, "Array should have 'username' key.");
        $this->assertEquals('test', $result['username'], "Username should match.");
        $this->assertArrayHasKey('token', $result, "Array should have 'token' key.");
    }
}
