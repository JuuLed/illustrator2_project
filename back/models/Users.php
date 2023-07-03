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
					* 
				  FROM 
					".TABLE_USERS;

		$stmt = $this->pdo->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getUserById($table, $id)
	{
		$query = "SELECT 
					* 
				  FROM 
					".TABLE_USERS." 
				  WHERE 
				  	user_id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':table', $table);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function register($username, $email, $password)
	{
		$query = "INSERT INTO 
					".TABLE_USERS." (username, email, password) 
				  VALUES 
					(:username, :email, :password)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
		$stmt->execute();

		return $this->pdo->lastInsertId();
	}

}

?>