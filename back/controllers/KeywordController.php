<?php
require_once './config/database.php';
require_once './models/Keyword.php';

class KeywordController {
    protected $keywordModel;

    public function __construct() {
        global $pdo;
        $this->keywordModel = new Keyword($pdo);
    }

    public function getAllKeywords() {
        return $this->keywordModel->getAllKeywords();
    }

    public function getKeyword($id) {
        $keyword = $this->keywordModel->getKeywordById($id);
        if ($keyword) {
            return $keyword;
        } else {
            return ['error' => 'Keyword not found'];
        }
    }

    public function createKeyword($data) {
        $keywordName = $data['keyword'];
        $languageCode = $data['language_code'];

        $result = $this->keywordModel->createKeyword($keywordName, $languageCode);

        if ($result) {
            return ['keyword_id' => $result, 'keyword' => $keywordName, 'language_code' => $languageCode];
        } else {
            return ['error' => 'Failed to create keyword'];
        }
    }

    public function updateKeyword($id, $data) {
        $keyword = $this->keywordModel->getKeywordById($id);

        if (!$keyword) {
            return ['error' => 'Keyword not found'];
        }

        $keywordName = isset($data['keyword']) ? $data['keyword'] : $keyword['keyword'];
        $languageCode = isset($data['language_code']) ? $data['language_code'] : $keyword['language_code'];

        $result = $this->keywordModel->updateKeyword($id, $keywordName, $languageCode);

        if ($result > 0) {
            return ['message' => 'Keyword updated successfully'];
        } else {
            return ['error' => 'Keyword update failed'];
        }
    }

    public function deleteKeyword($id) {
        $keyword = $this->keywordModel->getKeywordById($id);

        if (!$keyword) {
            return ['error' => 'Keyword not found'];
        }

        $result = $this->keywordModel->deleteKeyword($id);

        if ($result > 0) {
            return ['message' => 'Keyword deleted successfully'];
        } else {
            return ['error' => 'Keyword deletion failed'];
        }
    }
}
?>