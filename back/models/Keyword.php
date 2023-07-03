<?php
class Keyword {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllKeywords() {
		$query = "SELECT 
					k.keyword_id, k.keyword, t.language_code, t.value
				  FROM 
				   ".TABLE_KEYWORDS." k
				  LEFT JOIN 
				  	translations t 
				  ON 
				  	t.table_name = '".TABLE_KEYWORDS."' AND t.row_id = k.keyword_id";
	
		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
	
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	

    public function getKeywordById($id) {
        $query = "SELECT 
					* 
				  FROM 
				  	".TABLE_KEYWORDS." 
				  WHERE 
				  	keyword_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createKeyword($keyword) {
        $query = "INSERT INTO 
					".TABLE_KEYWORDS." (keyword) 
				  VALUES (:keyword)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function updateKeyword($id, $keyword) {
        $query = "UPDATE 
					".TABLE_KEYWORDS." 
				  SET 
					keyword = :keyword 
				  WHERE 
					keyword_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function deleteKeyword($id) {
        $query = "DELETE FROM 
					".TABLE_KEYWORDS." 
				  WHERE 
					keyword_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }
	
}
