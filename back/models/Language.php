<?php
class Language {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllLanguages() {
        $query = "SELECT * FROM languages";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLanguageByCode($code) {
        $query = "SELECT * FROM languages WHERE language_code = :code";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
	
	public function getAvailableLanguages() {
        $query = "SELECT * FROM languages";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $languages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($languages as $language) {
            $result[$language['language_code']] = $language['language_name'];
        }

        return $result;
    }

    public function createLanguage($code, $name) {
        $query = "INSERT INTO languages (language_code, language_name) VALUES (:code, :name)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function updateLanguage($code, $name) {
        $query = "UPDATE languages SET language = :name WHERE language_code = :code";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function deleteLanguage($code) {
        $query = "DELETE FROM languages WHERE language_code = :code";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
?>