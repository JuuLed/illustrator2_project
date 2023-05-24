<?php
class Symbol {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllSymbols() {
        $query = "SELECT symbols.*, GROUP_CONCAT(DISTINCT categories.category_id) AS category_ids, GROUP_CONCAT(DISTINCT keywords.keyword_id) AS keyword_ids
                  FROM symbols
                  LEFT JOIN symbol_category ON symbols.symbol_id = symbol_category.symbol_id
                  LEFT JOIN categories ON symbol_category.category_id = categories.category_id
                  LEFT JOIN symbol_keyword ON symbols.symbol_id = symbol_keyword.symbol_id
                  LEFT JOIN keywords ON symbol_keyword.keyword_id = keywords.keyword_id
                  GROUP BY symbols.symbol_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSymbolById($id) {
        $query = "SELECT symbols.*, GROUP_CONCAT(DISTINCT categories.category_id) AS category_ids, GROUP_CONCAT(DISTINCT keywords.keyword_id) AS keyword_ids
                  FROM symbols
                  LEFT JOIN symbol_category ON symbols.symbol_id = symbol_category.symbol_id
                  LEFT JOIN categories ON symbol_category.category_id = categories.category_id
                  LEFT JOIN symbol_keyword ON symbols.symbol_id = symbol_keyword.symbol_id
                  LEFT JOIN keywords ON symbol_keyword.keyword_id = keywords.keyword_id
                  WHERE symbols.symbol_id = :id
                  GROUP BY symbols.symbol_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $symbol = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($symbol) {
			$symbol['category_ids'] = explode(',', $symbol['category_ids']);
			$symbol['keyword_ids'] = explode(',', $symbol['keyword_ids']);
		}

		return $symbol;
	}

    public function createSymbol($fileName, $size, $active, $deleted, $categoryIds, $keywordNames) {
		$query = "INSERT INTO symbols (file_name, size, active, deleted) VALUES (:fileName, :size, :active, :deleted)";
		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':fileName', $fileName);
		$stmt->bindParam(':size', $size);
		$stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
		$stmt->bindParam(':deleted', $deleted, PDO::PARAM_BOOL);
		$stmt->execute();
	
		$symbolId = $this->pdo->lastInsertId();
	
		$this->updateSymbolCategories($symbolId, $categoryIds);
	
		$keywordIds = $this->getOrInsertKeywordIds($keywordNames);
	
		$this->updateSymbolKeywords($symbolId, $keywordIds);
	
		return $symbolId;
	}
	


    public function updateSymbol($id, $fileName, $size, $active, $deleted, $categoryNames, $keywordNames) {
        $query = "UPDATE symbols SET file_name = :fileName, size = :size, active = :active, deleted = :deleted WHERE symbol_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
        $stmt->bindParam(':deleted', $deleted, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $categoryIds = $this->getOrInsertCategoryIds($categoryNames);
        $keywordIds = $this->getOrInsertKeywordIds($keywordNames);

        $this->updateSymbolCategories($id, $categoryIds);
        $this->updateSymbolKeywords($id, $keywordIds);

        return $stmt->rowCount();
    }


    public function deleteSymbol($id) {
        $query = "DELETE FROM symbols WHERE symbol_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $this->deleteSymbolCategories($id);
        $this->deleteSymbolKeywords($id);

        return $stmt->rowCount();
    }

    private function updateSymbolCategories($symbolId, $categoryIds) {
        // Supprimer les anciennes entrées de la table de liaison
        $this->deleteSymbolCategories($symbolId);

        // Insérer les nouvelles entrées dans la table de liaison
        $query = "INSERT INTO symbol_category (symbol_id, category_id) VALUES (:symbolId, :categoryId)";
        $stmt = $this->pdo->prepare($query);

        foreach ($categoryIds as $categoryId) {
            $stmt->bindParam(':symbolId', $symbolId);
            $stmt->bindParam(':categoryId', $categoryId);
            $stmt->execute();
        }
    }

    private function deleteSymbolCategories($symbolId) {
        $query = "DELETE FROM symbol_category WHERE symbol_id = :symbolId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->execute();
    }

    private function updateSymbolKeywords($symbolId, $keywordIds) {
        // Supprimer les anciennes entrées de la table de liaison
        $this->deleteSymbolKeywords($symbolId);

        // Insérer les nouvelles entrées dans la table de liaison
        $query = "INSERT INTO symbol_keyword (symbol_id, keyword_id) VALUES (:symbolId, :keywordId)";
        $stmt = $this->pdo->prepare($query);

        foreach ($keywordIds as $keywordId) {
            $stmt->bindParam(':symbolId', $symbolId);
            $stmt->bindParam(':keywordId', $keywordId);
            $stmt->execute();
        }
    }

    private function deleteSymbolKeywords($symbolId) {
        $query = "DELETE FROM symbol_keyword WHERE symbol_id = :symbolId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':symbolId', $symbolId);
        $stmt->execute();
    }

	private function getOrInsertCategoryIds($categoryNames) {
		$categoryIds = [];			   
		foreach ($categoryNames as $categoryName) {
			$query = "SELECT category_id FROM categories WHERE category_name = :categoryName";
			$stmt = $this->pdo->prepare($query);
			$stmt->bindParam(':categoryName', $categoryName);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($result) {
				$categoryIds[] = $result['category_id'];
			} else {
				$query = "INSERT INTO categories (category_name) VALUES (:categoryName)";
				$stmt = $this->pdo->prepare($query);
				$stmt->bindParam(':categoryName', $categoryName);
				$stmt->execute();
				$categoryIds[] = $this->pdo->lastInsertId();
			}
		}
		return $categoryIds;
	}		   
	private function getOrInsertKeywordIds($keywordNames) {
		$keywordIds = [];
		foreach ($keywordNames as $keywordName) {
			$query = "SELECT keyword_id FROM keywords WHERE keyword = :keywordName";
			$stmt = $this->pdo->prepare($query);
			$stmt->bindParam(':keywordName', $keywordName);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($result) {
				$keywordIds[] = $result['keyword_id'];
			} else {
				$query = "INSERT INTO keywords (keyword) VALUES (:keywordName)";
				$stmt = $this->pdo->prepare($query);
				$stmt->bindParam(':keywordName', $keywordName);
				$stmt->execute();
				$keywordIds[] = $this->pdo->lastInsertId();
			}
		}	
		return $keywordIds;
	}
		
}
?>
