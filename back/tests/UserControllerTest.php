<?

require_once '../requireTests.php';

// Dans UserControllerTest.php

require_once '../requireTests.php';

function testGetAllUsers()
{
    $controller = new UserController();
    $users = $controller->getAllUsers();
    
    // Par exemple, vérifiez que le résultat est un tableau
    if (is_array($users)) {
        echo "testGetAllUsers passed!\n";
    } else {
        echo "testGetAllUsers failed: expected array, got " . gettype($users) . "\n";
    }
}

// Appeler la fonction de test
testGetAllUsers();
