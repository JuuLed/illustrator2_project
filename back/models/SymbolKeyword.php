<?php

class SymbolKeyword
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function getAllKeywordsBySymbolId($symbolId)
	{
		$query = "SELECT 
					k.* 
				  FROM 
				  	".TABLE_KEYWORDS." k
				  INNER JOIN ".TABLE_SYMBOL_KEYWORD." sk
				  	ON k.keyword_id = sk.keyword_id 
				  WHERE 
				  	sk.symbol_id = :symbolId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
		$keywords = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $keywords;
	}


	public function addKeywordToSymbol($symbolId, $keywordId)
	{
		$query = "INSERT INTO 
					".TABLE_SYMBOL_KEYWORD." (symbol_id, keyword_id) 
                  VALUES 
				  	(:symbolId, :keywordId)";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->bindParam(':keywordId', $keywordId);
		$stmt->execute();
	}

	public function removeKeywordFromSymbol($symbolId, $keywordId)
	{
		$query = "DELETE FROM 
					".TABLE_SYMBOL_KEYWORD." 
                  WHERE 
				  	symbol_id = :symbolId 
                  AND 
				  	keyword_id = :keywordId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->bindParam(':keywordId', $keywordId);
		$stmt->execute();
	}

	public function updateSymbolKeywords($symbolId, $keywordIds)
	{
		$query = "INSERT INTO 
					".TABLE_SYMBOL_KEYWORD." (symbol_id, keyword_id) 
				  VALUES 
					(:symbolId, :keywordId)";

		$stmt = $this->pdo->prepare($query);

		// Si un seul identifiant de mot-clé est fourni, le convertir en tableau
		if (!is_array($keywordIds)) {
			$keywordIds = [$keywordIds];
		}

		foreach ($keywordIds as $keywordId) {
			$stmt->bindParam(':symbolId', $symbolId);
			$stmt->bindParam(':keywordId', $keywordId);
			$stmt->execute();
		}
	}

	public function deleteSymbolKeywords($symbolId)
	{
		$query = "DELETE FROM 
					".TABLE_SYMBOL_KEYWORD." 
				  WHERE 
				  	symbol_id = :symbolId";

		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':symbolId', $symbolId);
		$stmt->execute();
	}
}