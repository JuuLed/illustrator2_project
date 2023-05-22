<?php
class Keyword {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllKeywords() {
        $stmt = $this->pdo->query("SELECT * FROM keywords");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKeywordById($keywordId) {
        $stmt = $this->pdo->prepare("SELECT * FROM keywords WHERE keyword_id = ?");
        $stmt->execute([$keywordId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addKeyword($symbolId, $lang, $keyword) {
        $stmt = $this->pdo->prepare("INSERT INTO keywords (symbol_id, lang, keyword) VALUES (?, ?, ?)");
        $stmt->execute([$symbolId, $lang, $keyword]);
        return $this->pdo->lastInsertId();
    }

    public function updateKeyword($keywordId, $lang, $keyword) {
        $stmt = $this->pdo->prepare("UPDATE keywords SET lang = ?, keyword = ? WHERE keyword_id = ?");
        $stmt->execute([$lang, $keyword, $keywordId]);
        return $stmt->rowCount();
    }

    public function deleteKeyword($keywordId) {
        $stmt = $this->pdo->prepare("DELETE FROM keywords WHERE keyword_id = ?");
        $stmt->execute([$keywordId]);
        return $stmt->rowCount();
    }

    public function getKeywordsBySymbol($symbolId) {
        $stmt = $this->pdo->prepare("SELECT * FROM keywords WHERE symbol_id = ?");
        $stmt->execute([$symbolId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
	public function deleteKeywordsBySymbol($symbolId) {
		$stmt = $this->pdo->prepare("DELETE FROM keywords WHERE symbol_id = ?");
		$stmt->execute([$symbolId]);
	}
	
}
