<?php

require_once './config/database.php';
require_once './models/SymbolKeyword.php';

class SymbolKeywordController
{
	protected $symbolKeywordModel;

	public function __construct()
	{
		global $pdo;
		$this->symbolKeywordModel = new SymbolKeyword($pdo);
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