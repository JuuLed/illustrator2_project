<?php

class SymbolKeyword {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllKeywordsBySymbolId($symbolId) {
		$query = "SELECT 
					keywords.* 
				  FROM 
				  	keywords
				  INNER JOIN symbol_keyword
				  	ON keywords.keyword_id = symbol_keyword.keyword_id 
				  WHERE 
				  	symbol_keyword.symbol_id = :symbolId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
		$keywords = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $keywords;
	}
	

    public function addKeywordToSymbol($symbolId, $keywordId) {
        $query = "INSERT INTO 
					symbol_keyword (symbol_id, keyword_id) 
                  VALUES 
				  	(:symbolId, :keywordId)";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->bindParam(':keywordId', $keywordId);
        $stmt->execute();
    }

    public function removeKeywordFromSymbol($symbolId, $keywordId) {
        $query = "DELETE FROM 
					symbol_keyword 
                  WHERE 
				  	symbol_id = :symbolId 
                  AND 
				  	keyword_id = :keywordId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->bindParam(':keywordId', $keywordId);
        $stmt->execute();
    }



	//________________________________________
	// Methodes pour symbol_keyword
	private function updateSymbolKeywords($symbolId, $keywordIds)
	{
		// Supprimer les anciennes entrées de la table de liaison
		$this->deleteSymbolKeywords($symbolId);

		// Insérer les nouvelles entrées dans la table de liaison
		$query = "INSERT INTO 
					symbol_keyword (symbol_id, keyword_id) 
				  VALUES 
					(:symbolId, :keywordId)";

		$stmt = $this->pdo->prepare($query);

		foreach ($keywordIds as $keywordId) {
			$stmt->bindParam(':symbolId', $symbolId);
			$stmt->bindParam(':keywordId', $keywordId);
			$stmt->execute();
		}
	}

	private function deleteSymbolKeywords($symbolId)
	{
		$query = "DELETE FROM 
					symbol_keyword 
				  WHERE 
				  	symbol_id = :symbolId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
	}
}
