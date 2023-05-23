<?php
class Symbol {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllSymbols() {
        $query = "SELECT * FROM symbols";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSymbolById($id) {
        $query = "SELECT * FROM symbols WHERE symbol_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createSymbol($fileName, $size, $active, $deleted) {
        $query = "INSERT INTO symbols (file_name, size, active, deleted) VALUES (:fileName, :size, :active, :deleted)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
        $stmt->bindParam(':deleted', $deleted, PDO::PARAM_BOOL);
        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    public function updateSymbol($id, $fileName, $size, $active, $deleted) {
        $query = "UPDATE symbols SET file_name = :fileName, size = :size, active = :active, deleted = :deleted WHERE symbol_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':active', $active, PDO::PARAM_BOOL);
        $stmt->bindParam(':deleted', $deleted, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function deleteSymbol($id) {
        $query = "DELETE FROM symbols WHERE symbol_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
?>