<?php
class Category
{
	protected $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getAllCategories()
	{
		$query = "SELECT 
					*
                  FROM 
				  	categories
				  ORDER BY 
				  	categories.order ASC";

		$stmt = $this->pdo->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCategoryById($id)
	{
		$query = "SELECT 
					* 
				  FROM 
				  	categories 
				  WHERE 
				  	category_id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function createCategory($category, $order)
	{
		$query = "INSERT INTO 
					categories (category, `order`) 
				  VALUES 
				  	(:category, :order)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':category', $category);
		$stmt->bindParam(':order', $order);
		$stmt->execute();

		return $this->pdo->lastInsertId();
	}

	public function updateCategory($id, $category, $order)
	{
		$query = "UPDATE 
					categories 
				  SET 
					category = :category,
					`order` = :order
				  WHERE 
					category_id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':category', $category);
        $stmt->bindParam(':order', $order);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		return $stmt->rowCount();
	}

	public function deleteCategory($id)
	{
		$query = "DELETE FROM 
					categories 
				  WHERE 
					category_id = :id";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		return $stmt->rowCount();
	}
}