<?php
class Keyword {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllKeywords() {
        $query = "SELECT * FROM keywords";
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

    public function createKeyword($keyword, $languageCode) {
        $query = "INSERT INTO keywords (keyword, language_code) VALUES (:keyword, :languageCode)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':languageCode', $languageCode);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function updateKeyword($id, $keyword, $languageCode) {
        $query = "UPDATE keywords SET keyword = :keyword, language_code = :languageCode WHERE keyword_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':languageCode', $languageCode);
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
?>