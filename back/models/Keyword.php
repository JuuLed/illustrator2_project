<?php
class Keyword {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllKeywords() {
		$query = "SELECT k.keyword_id, k.keyword, t.language_code, t.value
				  FROM keywords k
				  LEFT JOIN translates t ON t.table_name = 'keywords' AND t.row_id = k.keyword_id";
	
		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
	
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	

    public function getKeywordById($id) {
        $query = "SELECT * FROM keywords WHERE keyword_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createKeyword($keyword) {
        $query = "INSERT INTO keywords (keyword) VALUES (:keyword)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function updateKeyword($id, $keyword) {
        $query = "UPDATE keywords SET keyword = :keyword WHERE keyword_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function deleteKeyword($id) {
        $query = "DELETE FROM keywords WHERE keyword_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
