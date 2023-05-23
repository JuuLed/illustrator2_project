<?php
require_once './config/database.php';
require_once './models/Symbol.php';

class SymbolController {
    protected $symbolModel;

    public function __construct() {
        global $pdo;
        $this->symbolModel = new Symbol($pdo);
    }

    public function getAllSymbols() {
        return $this->symbolModel->getAllSymbols();
    }

    public function getSymbol($id) {
        $symbol = $this->symbolModel->getSymbolById($id);
        if ($symbol) {
            return $symbol;
        } else {
            return ['error' => 'Symbol not found'];
        }
    }

    public function createSymbol($data) {
        $fileName = $data['file_name'];
        $size = $data['size'];
        $active = isset($data['active']) ? $data['active'] : false;
        $deleted = isset($data['deleted']) ? $data['deleted'] : false;

        $result = $this->symbolModel->createSymbol($fileName, $size, $active, $deleted);

        if ($result) {
            return ['symbol_id' => $result, 'file_name' => $fileName, 'size' => $size, 'active' => $active, 'deleted' => $deleted];
        } else {
            return ['error' => 'Failed to create symbol'];
        }
    }

    public function updateSymbol($id, $data) {
        $symbol = $this->symbolModel->getSymbolById($id);

        if (!$symbol) {
            return ['error' => 'Symbol not found'];
        }

        $fileName = isset($data['file_name']) ? $data['file_name'] : $symbol['file_name'];
        $size = isset($data['size']) ? $data['size'] : $symbol['size'];
        $active = isset($data['active']) ? $data['active'] : $symbol['active'];
        $deleted = isset($data['deleted']) ? $data['deleted'] : $symbol['deleted'];

        $result = $this->symbolModel->updateSymbol($id, $fileName, $size, $active, $deleted);

        if ($result > 0) {
            return ['message' => 'Symbol updated successfully'];
        } else {
            return ['error' => 'Symbol update failed'];
        }
    }

    public function deleteSymbol($id) {
        $symbol = $this->symbolModel->getSymbolById($id);

        if (!$symbol) {
            return ['error' => 'Symbol not found'];
        }

        $result = $this->symbolModel->deleteSymbol($id);

        if ($result > 0) {
            return ['message' => 'Symbol deleted successfully'];
        } else {
            return ['error' => 'Symbol deletion failed'];
        }
    }
}
?>