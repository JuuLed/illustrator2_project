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
        return $this->keywordModel->getKeywordById($id);
    }

    public function createKeyword($data) {
        $keywordId = $this->keywordModel->addKeyword($data['symbol_id'], $data['lang'], $data['keyword']);
        if (is_numeric($keywordId)) {
            return ['keyword_id' => $keywordId];
        } else {
            return ['error' => 'Failed to create keyword'];
        }
    }

    public function updateKeyword($id, $data) {
        $keyword = $this->keywordModel->getKeywordById($id);

        if (!$keyword) {
            return ['error' => 'Keyword not found'];
        }

        $result = $this->keywordModel->updateKeyword($id, $data['lang'], $data['keyword']);

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
	public function deleteKeywordsBySymbol($symbolId) {
		$this->keywordModel->deleteKeywordsBySymbol($symbolId);
	}
	
}
