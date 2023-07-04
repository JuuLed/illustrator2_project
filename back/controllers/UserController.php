<?php

require_once './config/database.php';
require_once './models/User.php';

class UserController
{
    protected $userModel;

    public function __construct()
    {
        global $pdo;
        $this->userModel = new User($pdo);
    }

    public function getAllUsers()
    {
        $users = $this->userModel->getAllUsers();
        
        // Ici vous pouvez formater les données comme vous le souhaitez ou les passer à une vue

        return $users;
    }

    public function getUserById($id)
    {
        $user = $this->userModel->getUserById($id);
        
        // Ici vous pouvez formater les données comme vous le souhaitez ou les passer à une vue

        return $user;
    }

    public function register($username, $email, $password)
    {
        $result = $this->userModel->register($username, $email, $password);

		if (isset($result['error'])) {
			$message = 'Registration failed! ' . $result['error'];
		} else {
			$message = 'Registration success!';
		}
		
		return $message;
    }

    public function login($email, $password)
    {
        $result = $this->userModel->login($email, $password);

        if (isset($result['error'])) {
            echo 'Login failed! ' . $result['error'];
        } else {
            // Si la connexion a réussi, vous pouvez rediriger l'utilisateur vers une autre page
            // et éventuellement stocker les données de l'utilisateur dans une session
            $_SESSION['user'] = $result;
			return $result;

        }
    }
}

?>
