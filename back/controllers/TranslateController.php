<?php

require_once './config/database.php';
require_once './models/Translate.php';

class TranslateController {
    protected $translateModel;

    public function __construct() {
        global $pdo;
        $this->translateModel = new Translate($pdo);
    }

    public function getAllTranslates() {
        return $this->translateModel->getAllTranslates();
    }

    public function getTranslateByTableAndId($table, $id) {
        $translate = $this->translateModel->getTranslateByTableAndId($table, $id);
        if ($translate) {
            return $translate;
        } else {
            return ['error' => 'Translate not found'];
        }
    }

    public function createTranslate($data) {
        $table = $data['table_name'];
        $id = $data['row_id'];
        $value = $data['value'];
        $langCode = $data['language_code'];

        $result = $this->translateModel->createTranslate($table, $id, $value, $langCode);

        if ($result) {
            return ['table_name' => $table, 'row_id' => $id, 'value' => $value, 'language_code' => $langCode];
        } else {
            return ['error' => 'Failed to create translate'];
        }
    }

    public function updateTranslate($table, $id, $data) {
        $translate = $this->translateModel->getTranslateByTableAndId($table, $id);

        if (!$translate) {
            return ['error' => 'Translate not found'];
        }

        $value = isset($data['value']) ? $data['value'] : $translate['value'];
        $langCode = isset($data['language_code']) ? $data['language_code'] : $translate['language_code'];

        $result = $this->translateModel->updateTranslateByTableAndId($table, $id, $value, $langCode);

        if ($result > 0) {
            return ['message' => 'Translate updated successfully'];
        } else {
            return ['error' => 'Translate update failed'];
        }
    }

    public function deleteTranslate($table, $id) {
        $translate = $this->translateModel->getTranslateByTableAndId($table, $id);

        if (!$translate) {
            return ['error' => 'Translate not found'];
        }

        $result = $this->translateModel->deleteTranslateByTableAndId($table, $id);

        if ($result > 0) {
            return ['message' => 'Translate deleted successfully'];
        } else {
            return ['error' => 'Translate deletion failed'];
        }
    }
}
