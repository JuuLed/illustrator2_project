<?php

require_once './config/database.php';
require_once './models/Translation.php';

class TranslationController {
    protected $translationModel;

    public function __construct() {
        global $pdo;
        $this->translationModel = new Translation($pdo);
    }

    public function getAllTranslations() {
        return $this->translationModel->getAllTranslations();
    }

    public function getTranslationByTableAndId($table, $id) {
        $translation = $this->translationModel->getTranslationByTableAndId($table, $id);
        if ($translation) {
            return $translation;
        } else {
            return ['error' => 'Translation not found'];
        }
    }

    public function createTranslation($data) {
        $table = $data['table_name'];
        $id = $data['row_id'];
        $value = $data['value'];
        $langCode = $data['language_code'];

        $result = $this->translationModel->createTranslation($table, $id, $value, $langCode);

        if ($result) {
            return ['table_name' => $table, 'row_id' => $id, 'value' => $value, 'language_code' => $langCode];
        } else {
            return ['error' => 'Failed to create translation'];
        }
    }

    public function updateTranslation($table, $id, $data) {
        $translation = $this->translationModel->getTranslationByTableAndId($table, $id);

        if (!$translation) {
            return ['error' => 'Translation not found'];
        }

        $value = isset($data['value']) ? $data['value'] : $translation['value'];
        $langCode = isset($data['language_code']) ? $data['language_code'] : $translation['language_code'];

        $result = $this->translationModel->updateTranslationByTableAndId($table, $id, $value, $langCode);

        if ($result > 0) {
            return ['message' => 'Translation updated successfully'];
        } else {
            return ['error' => 'Translation update failed'];
        }
    }

    public function deleteTranslation($table, $id) {
        $translation = $this->translationModel->getTranslationByTableAndId($table, $id);

        if (!$translation) {
            return ['error' => 'Translation not found'];
        }

        $result = $this->translationModel->deleteTranslationByTableAndId($table, $id);

        if ($result > 0) {
            return ['message' => 'Translation deleted successfully'];
        } else {
            return ['error' => 'Translation deletion failed'];
        }
    }
}
