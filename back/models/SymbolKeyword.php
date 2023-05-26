<?php

class SymbolKeyword {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllKeywordsBySymbolId($symbolId) {
		$query = "SELECT k.* 
				  FROM keywords k 
				  INNER JOIN symbol_keyword sk 
				  ON k.keyword_id = sk.keyword_id 
				  WHERE sk.symbol_id = :symbolId";
		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
		$keywords = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $keywords;
	}
	

    public function addKeywordToSymbol($symbolId, $keywordId) {
        $query = "INSERT INTO symbol_keyword (symbol_id, keyword_id) 
                  VALUES (:symbolId, :keywordId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->bindParam(':keywordId', $keywordId);
        $stmt->execute();
    }

    public function removeKeywordFromSymbol($symbolId, $keywordId) {
        $query = "DELETE FROM symbol_keyword 
                  WHERE symbol_id = :symbolId 
                  AND keyword_id = :keywordId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->bindParam(':keywordId', $keywordId);
        $stmt->execute();
    }

}
