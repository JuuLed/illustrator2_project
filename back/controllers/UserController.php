<?php

require_once './config/database.php';
require_once './models/User.php';

use \Firebase\JWT\JWT;

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
			return ['error' => $result['error']];
		} else {
            // Si l'enregistrement a réussi, vous pouvez générer un token JWT
            $userId = $result['user_id'];
            $token = $this->generateToken($userId);

            // Stockez le token dans une session
            $_SESSION['token'] = $token;

            // Ou renvoyez le token en réponse à l'appelant
            // echo json_encode(['token' => $token]);

			return [
				'statut' => 'Registration success!',
				'username' => $result['username'],
				'token' => $token
			];
		}
		
    }

    public function login($email, $password)
    {
        $result = $this->userModel->login($email, $password);

        if (isset($result['error'])) {
            return ['error' => $result['error']];
        } else {
            // Si la connexion a réussi, vous pouvez générer un token JWT
            $userId = $result['user_id'];
            $token = $this->generateToken($userId);

            // Stockez le token dans une session
            $_SESSION['token'] = $token;

			return [
				'statut' => 'Login success!',
				'username' => $result['username'],
				'token' => $token
			];


        }
    }


// Gestion des TOKENS -----------------------------------------------
private function generateToken($userId)
    {
        $key = JWT_SECRET_KEY;
        $tokenId = base64_encode(openssl_random_pseudo_bytes(32));
        $issuedAt = time();
        $notBefore = $issuedAt;
        $expire = $notBefore + (60 * 60 * 24);  // Expire dans 24 heures

        $data = [
            'iat'  => $issuedAt,  // Temps d'émission du token
            'jti'  => $tokenId,   // ID du token
            'iss'  => $_SERVER['SERVER_NAME'], // Émetteur du token
            'nbf'  => $notBefore, // Token non valide avant cette date
            'exp'  => $expire,    // Token expire à cette date
            'data' => [           // Données supplémentaires que vous pouvez inclure dans le token
                'userId' => $userId,
            ],
        ];

        return JWT::encode($data, $key, 'HS256');
    }

    // private function verifyToken($token)
    // {
    //     $key = JWT_SECRET_KEY;

    //     try {
    //         $decoded = JWT::decode($token, $key, array('HS256'));
    //         return $decoded->data->userId;
    //     } catch (Exception $e) {
    //         // Gérer l'erreur de token invalide
    //         return null;
    //     }
    // }

}

?>
