<?php

class Translate {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllTranslates() {
        $query = "SELECT * FROM translates";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTranslateByTableAndId($table, $id) {
		$query = "SELECT * FROM translates WHERE table_name = :table AND row_id = :id";
		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':table', $table);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	

    public function createTranslate($table, $id, $value, $langCode) {
        $query = "INSERT INTO translates (table_name, row_id, value, language_code) 
                  VALUES (:table_name, :row_id, :value, :language_code)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':table_name', $table);
        $stmt->bindParam(':row_id', $id);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':language_code', $langCode);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function updateTranslateByTableAndId($table, $id, $value, $langCode) {
		$currentTranslate = $this->getTranslateByTableAndId($table, $id);
	
		if (!$currentTranslate) {
			return 0; // La traduction n'existe pas, retourne 0 pour indiquer l'échec de la mise à jour
		}
	
		$newValue = $value !== '' ? $value : $currentTranslate['value'];
		$newLangCode = $langCode !== '' ? $langCode : $currentTranslate['language_code'];
	
		if ($newValue === $currentTranslate['value'] && $newLangCode === $currentTranslate['language_code']) {
			return 0; // Aucun champ à mettre à jour, retourne 0 pour indiquer l'absence de modification
		}
	
		$query = "UPDATE translates SET value = :value, language_code = :language_code 
				  WHERE table_name = :table AND row_id = :id";
		$stmt = $this->pdo->prepare($query);
		$stmt->bindParam(':table', $table);
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':value', $newValue);
		$stmt->bindParam(':language_code', $newLangCode);
		$stmt->execute();
	
		return $stmt->rowCount();
	}
	

    public function deleteTranslateByTableAndId($table, $id) {
        $query = "DELETE FROM translates WHERE table_name = :table AND row_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':table', $table);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }
}

?>
