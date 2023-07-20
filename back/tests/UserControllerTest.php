<?php

echo "Test script is running...\n";

require_once __DIR__ . '/../controllers/UserController.php';


function testGetAllUsers()
{
	$userController = new UserController();

	$users = $userController->getAllUsers();
	if (!is_array($users)) {
		echo "Failed: Expected an array. \n";
	} else {
		echo "Passed: getAllUsers returns an array. \n";
	}
}

function testGetUserById()
{
	echo "Running testGetUserById()... \n";

	$userController = new UserController();

	// Ici, remplacer 1 par un ID utilisateur valide dans votre base de données.
	$user = $userController->getUserById(1);

	if (!is_array($user)) {
		echo "Failed: Expected an array. \n";
	} else {
		echo "Passed: getUserById returns an array. \n";

		// Vérifier que l'utilisateur a un id, un nom d'utilisateur et un email
		if (!isset($user['user_id']) || !isset($user['username']) || !isset($user['email'])) {
			echo "Failed: The user doesn't have an id, a username or an email. \n";
		} else {
			echo "Passed: The user has an id, a username and an email. \n";
		}
	}
}



function testRegister()
{
	echo "Running testRegister()... \n";
	$userController = new UserController();

	// Ici, remplacer "testUsername", "testEmail", et "testPassword" par des valeurs valides pour un nouvel utilisateur.
	$result = $userController->register("testUsername", "testEmail", "testPassword");

	// Vérifier que l'enregistrement renvoie un tableau
	if (!is_array($result)) {
		echo "Failed: Expected an array. \n";
	} else {
		echo "Passed: register returns an array. \n";

		// Vérifier que le tableau contient une clé 'statut' avec la valeur 'Registration success!'
		if (!isset($result['statut']) || $result['statut'] !== 'Registration success!') {
			echo "Failed: The array doesn't have a 'statut' key with 'Registration success!' value. \n";
		} else {
			echo "Passed: The array has a 'statut' key with 'Registration success!' value. \n";
		}
	}
}

function testLogin()
{
	echo "Running testLogin()... \n";
	$userController = new UserController();

	// Ici, utiliser les mêmes "testEmail", et "testPassword" que dans testRegister().
	$result = $userController->login("testEmail", "testPassword");

	// Vérifier que la connexion renvoie un tableau
	if (!is_array($result)) {
		echo "Failed: Expected an array. \n";
	} else {
		echo "Passed: login returns an array. \n";

		// Vérifier que le tableau contient une clé 'statut' avec la valeur 'Login success!'
		if (!isset($result['statut']) || $result['statut'] !== 'Login success!') {
			echo "Failed: The array doesn't have a 'statut' key with 'Login success!' value. \n";
		} else {
			echo "Passed: The array has a 'statut' key with 'Login success!' value. \n";
		}
	}
}

// Exécute les tests
testGetAllUsers();
testGetUserById();
testRegister();
testLogin();


?>