<?php

require_once __DIR__ . '/.././config/database.php';
require_once __DIR__ . '/.././models/User.php';
require_once __DIR__ . '/../utils/RealCookieSetter.php';

use \Firebase\JWT\JWT;

class UserController
{
    protected $userModel;
    protected $cookieSetter;

    public function __construct(User $userModel = null, CookieSetter $cookieSetter = null)
    {
        global $pdo;
        $this->userModel = $userModel ? $userModel : new User($pdo);
        $this->cookieSetter = $cookieSetter ? $cookieSetter : new RealCookieSetter();

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

			// Stockez le token dans un cookie sécurisé
			$expire = time() + 60*60*24; // Expire dans 24 heures
			// HTTPS =
			//! setcookie('token', $token, $expire, '/', '', true, true);
			// HTTP =
			// setcookie('token', $token, $expire, '/', '', false, true);

			$this->cookieSetter->set('token', $token, $expire, '/', '', false, true);

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

			// Stockez le token dans un cookie sécurisé
			$expire = time() + 60*60*24; // Expire dans 24 heures
			// HTTPS =
			//! setcookie('token', $token, $expire, '/', '', true, true);
			// HTTP =
			// setcookie('token', $token, $expire, '/', '', false, true);
			$this->cookieSetter->set('token', $token, $expire, '/', '', false, true);

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

		if (!isset($_SERVER['SERVER_NAME'])) {
			$_SERVER['SERVER_NAME'] = DB_HOST;
		}

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
	
private function verifyToken($token)
{
    $key = JWT_SECRET_KEY;

    try {
        $decoded = JWT::decode($token, $key, array('HS256'));
        return $decoded->data->userId;
    } catch (Exception $e) {
        // Gérer l'erreur de token invalide
        return ['error' => 'Invalid token: ' . $e->getMessage()];
    }
}


}

?>
