<?php

class User
{
	protected $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getAllUsers()
	{
		$query = "SELECT 
					u.user_id, u.username, u.password 
				  FROM 
					".TABLE_USERS." u";

		$stmt = $this->pdo->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getUserByUsername($username)
	{
		$query = "SELECT
					u.user_id, u.username, u.password
				  FROM
				  	".TABLE_USERS." u
				  WHERE
				  	username = :username";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getUserByEmail($email)
	{
		$query = "SELECT 
					u.user_id, u.email, u.password
				  FROM 
				  	".TABLE_USERS." u
				  WHERE 
				  	email = :email";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function createUser($username, $email, $password)
	{
		$query = "INSERT INTO 
					".TABLE_USERS." 
					(username, email, password) 
				  VALUES 
				  	(:username, :email, :password)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->execute();

		return $this->pdo->lastInsertId();
	}

	public function getUserById($userId)
	{
		$query = "SELECT 
					u.user_id, u.username, u.email
				  FROM 
				  	".TABLE_USERS." u
				  WHERE 
				  	user_id = :user_id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function register($username, $email, $password)
    {
        // vérifier si l'utilisateur existe déjà
        if($this->getUserByUsername($username) || $this->getUserByEmail($email)) {
            return ["error" => "Username or Email already exists."];
        }

        // créer un nouvel utilisateur
        $userId = $this->createUser($username, $email, password_hash($password, PASSWORD_DEFAULT));

        // obtenir les informations de l'utilisateur
        if ($userId) {
            $user = $this->getUserById($userId);
            return $user;
        }

        return ["error" => "Registration failed."];
    }

    public function login($email, $password)
    {
        // vérifier si l'utilisateur existe
        $user = $this->getUserByEmail($email);
        if (!$user) {
            return ["error" => "Email doesn't exist."];
        }

        // vérifier le mot de passe
        if (password_verify($password, $user['password'])) {
            // Retourner les informations de l'utilisateur
            $user = $this->getUserById($user['user_id']);
            return $user;
        }

        return ["error" => "Invalid password."];
    }
}

?>
