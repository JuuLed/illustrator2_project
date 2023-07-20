<?php

echo "Test script is running...\n";

require_once __DIR__ . '/../controllers/UserController.php';


function testGetAllUsers() {
    $userController = new UserController();

    $users = $userController->getAllUsers();
    if(!is_array($users)) {
        echo "Failed: Expected an array. \n";
    } else {
        echo "Passed: getAllUsers returns an array. \n";
    }
}

function testGetUserById() {
    $userController = new UserController();
    
    // Ici, remplacer 1 par un ID utilisateur valide dans votre base de données.
    $user = $userController->getUserById(1); 

    if(!is_array($user)) {
        echo "Failed: Expected an array. \n";
    } else {
        echo "Passed: getUserById returns an array. \n";
    }
}

// Exécute les tests
testGetAllUsers();
testGetUserById();

?>