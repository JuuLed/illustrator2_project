<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/SymbolKeyword.php';

class SymbolKeywordController
{
	protected $symbolKeywordModel;

	public function __construct(SymbolKeyword $symbolKeywordModel = null)
	{
		global $pdo;
		$this->symbolKeywordModel = $symbolKeywordModel ? $symbolKeywordModel : new SymbolKeyword($pdo);
	}

	public function getAllKeywordsBySymbolId($symbolId)
	{
		$keywords = $this->symbolKeywordModel->getAllKeywordsBySymbolId($symbolId);
		return ['keywords' => $keywords];
	}

	public function addKeywordToSymbol($symbolId, $keywordId)
	{
		$this->symbolKeywordModel->addKeywordToSymbol($symbolId, $keywordId);
		return ['message' => 'Keyword added to symbol successfully'];
	}

	public function removeKeywordFromSymbol($symbolId, $keywordId)
	{
		$this->symbolKeywordModel->removeKeywordFromSymbol($symbolId, $keywordId);
		return ['message' => 'Keyword removed from symbol successfully'];
	}
}