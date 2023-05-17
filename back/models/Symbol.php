<?php
class Symbol {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllSymbols() {
        $stmt = $this->pdo->query("SELECT * FROM symbols WHERE deleted = FALSE");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSymbolById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM symbols WHERE symbol_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createSymbol($data) {
        $validation = $this->validateSymbolData($data);
        if ($validation === true) {
            $stmt = $this->pdo->prepare("INSERT INTO symbols (name_file, active, size) VALUES (?, ?, ?)");
            $stmt->execute([$data['name_file'], $data['active'], $data['size']]);
            $symbolId = $this->pdo->lastInsertId();

            // Insert associations between the symbol and categories
            if (isset($data['categories']) && is_array($data['categories'])) {
                foreach ($data['categories'] as $categoryId) {
                    $stmt = $this->pdo->prepare("INSERT INTO symbol_category (symbol_id, category_id) VALUES (?, ?)");
                    $stmt->execute([$symbolId, $categoryId]);
                }
            }

            return $symbolId;
        } else {
            http_response_code(400);
            return ['error' => 'Invalid data', 'details' => $validation];
        }
    }

    public function updateSymbol($id, $data) {
        $validation = $this->validateSymbolData($data);
        if ($validation === true) {
            $stmt = $this->pdo->prepare("UPDATE symbols SET name_file = ?, active = ?, size = ? WHERE symbol_id = ?");
            $stmt->execute([$data['name_file'], $data['active'], $data['size'], $id]);

            // Delete old associations between the symbol and categories
            $stmt = $this->pdo->prepare("DELETE FROM symbol_category WHERE symbol_id = ?");
            $stmt->execute([$id]);

            // Insert new associations between the symbol and categories
            if (isset($data['categories']) && is_array($data['categories'])) {
                foreach ($data['categories'] as $categoryId) {
                    $stmt = $this->pdo->prepare("INSERT INTO symbol_category (symbol_id, category_id) VALUES (?, ?)");
                    $stmt->execute([$id, $categoryId]);
                }
            }

            return $stmt->rowCount();
        } else {
            http_response_code(400);
            return ['error' => 'Invalid data', 'details' => $validation];
        }
    }

    public function deleteSymbol($id) {
        $stmt = $this->pdo->prepare("UPDATE symbols SET deleted = TRUE WHERE symbol_id = ?");
        $stmt->execute([$id]);

        // Delete associations between the symbol and categories
        // $stmt = $this->pdo->prepare("DELETE FROM symbol_category WHERE symbol_id = ?");
        // $stmt->execute([$id]);

        return ['message' => 'The symbol was deleted successfully!'];
    }

    private function validateSymbolData($data) {
        $errors = [];

        if (!isset($data['name_file']) || empty($data['name_file'])) {
            $errors[] = 'name_file is required';
        }

        if (!isset($data['active']) || !in_array($data['active'], [0, 1])) {
            $errors[] = 'active must be 0 or 1';
        }

        if (!isset($data['size']) || !is_numeric($data['size']) || $data['size'] < 1 || $data['size'] > 100) {
            $errors[] = 'size must be a number between 1 and 100';
        }

        return empty($errors) ? true : $errors;
    }
}
?>
