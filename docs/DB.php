<?php
$__DB_LastQuery = "";
/**
 * Fonctions liées à la base de données Loguy
 */
class DB {
	function __construct() {
		$this->connect();
	}
	
	static public function begin() {
		mysql_query("BEGIN");
	}
	static public function commit() {
		mysql_query("COMMIT");
	}
	static public function rollback() {
		mysql_query("ROLLBACK");
	}
	static public function lock($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		mysql_query($sql);
	}
	static public function unlock() {
		mysql_query("UNLOCK TABLES");
	}
	static function alter($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		mysql_query($sql);
	}
	static function drop($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		mysql_query($sql);
	}
	static function create($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		mysql_query($sql);
	}
	// static public function selectDB($dbName) {
	// 	mysql_query($sql);
	// }
	/**
	 * Connection à la base de données Loguy
	 * @return void
	 */
	static public function connect($dbName="loguy") {
		$DB_USER = 'devt';
		$DB_PASSWORD = trim(file_get_contents("/home/.devt"));
		mysql_connect("127.0.0.1",$DB_USER, $DB_PASSWORD);
		mysql_select_db($dbName);
		mysql_query("SET NAMES UTF8");
	}
	/**
	 * Retourne la dernière erreur mySQL au format texte
	 * @return string
	 */
	static function getLastError() {
		return mysql_error();
	}
	
	/**
	 * Retourne la dernière erreur mySQL au format texte
	 * @return string
	 */
	static function getLastQuery() {
		global $__DB_LastQuery;
		return $__DB_LastQuery;
	}
	
	/**
	 * Retourne l'ID de la la dernière erreur
	 * @return int
	 */
	static function getLastErrorID() {
		return mysql_errno();
	}
	
	/**
	 * Retourne la valeur encodée pour mysql
	 * @param string $value La valeur a échapper
	 * @return string
	 */
	static function escape($value) {
		return mysql_real_escape_string($value);
	}
	
	/**
	 * Execute une requete SELECT ne renvoyant qu'un seul résultat
	 * @param string $sql La requete à exécuter
	 * @return [mixed]. 
	 *	NULL si la requête n'a pas été exécutée
	 *	Si la requête ne retourne qu'un seul champ, renvoi directement la valeur
	 *	Si la requête retourne plusieurs champ, retourne un objet 
	 */
	static function get($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		$SQL = mysql_query($sql);
		if (!$SQL) { return false; }
		if (mysql_num_rows($SQL)) {
			$RowSQL = mysql_fetch_assoc($SQL);
			$keys = array_keys($RowSQL);
			if (count($keys)==1) {
				return $RowSQL[$keys[0]];
			}
			return (object)$RowSQL;
		} else {
			return null;
		}
	}
	
	/**
	 * Execute une requete SELECT renvoyant plusieurs résultats
	 * @param string $sql La requete à exécuter
	 * @return [mixed]. 
	 *	NULL si la requête n'a pas été exécutée
	 *	Si la requête ne retourne qu'un seul champ, renvoi un tableau de valeur
	 *	Si la requête retourne plusieurs champ, retourne un tableau d'objet 
	 */
	static function gets($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		$SQL = mysql_query($sql);
		if (!$SQL) { return false; }
		if (mysql_num_rows($SQL)) {
			$tmpObj = [];
			while ($RowSQL= mysql_fetch_assoc($SQL)) {
				$keys = array_keys($RowSQL);
				if (count($keys)==1) {
					$tmpObj[] = $RowSQL[$keys[0]];
				} else {
					$tmpObj[] = (object)$RowSQL;
				}
			}
			return $tmpObj;
		} else {
			return array();
		}
	}
	
	/**
	 * Execute une requete INSERT
	 * @param string $sql La requete à exécuter
	 * @return mixed
	 *	NULL si la requête n'a pas été exécutée
	 *	Un integer contenant la valeur du champ auto incrément si il existe pour cette table
	 */
	static function insert($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		$SQL = mysql_query($sql);
		if (!$SQL) { return false; }
		// return mysql_insert_id();
		return mysql_insert_id() ? mysql_insert_id() : null;

	}
	
	/**
	 * Execute une requete UPDATE
	 * @param string $sql La requete à exécuter
	 * @return mixed
	 *	NULL si la requête n'a pas été exécutée
	 *	Un integer contenant le nombre de ligne affectée par la requête
	 */
	static function update($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		$SQL = mysql_query($sql);
		if (!$SQL) { return false; }
		return mysql_affected_rows();
	}
	
	/**
	 * Execute une requete DELETE
	 * @param string $sql La requete à exécuter
	 * @return mixed
	 *	NULL si la requête n'a pas été exécutée
	 *	Un integer contenant le nombre de ligne affectée par la requête
	 */
	static function delete($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		$SQL = mysql_query($sql);
		if (!$SQL) { return false; }
		return mysql_affected_rows();
	}
	
	/**
	 * Execute une requete REPLACE
	 * @param string $sql La requete à exécuter
	 * @return mixed
	 *	NULL si la requête n'a pas été exécutée
	 *	Un integer contenant le nombre de ligne affectée par la requête
	 */
	static function replace($sql) {
		global $__DB_LastQuery;
		$__DB_LastQuery = $sql;
		$SQL = mysql_query($sql);
		if (!$SQL) { return false; }
		return mysql_affected_rows();
	}
	
	/**
	 * Prend une liste d'enregistrements d'objects et applique un limit et un orderBy
	 * @param array $records
	 * @param object $params 
	 *	$params->orderBY array ex: ["conID DESC", "date ASC"] 
	 *	$params->limit int Nb d'object à retourner
	 *	$params->page int Numéo de la page à aFFICHER
	 *	$params->addAllRecords boolean Si true, renvoi tous les enregistrements ordonées dans le limit dans ->allRecords
	 * @return type
	 */
	static function applyOrderAndLimit($records, $params) {
		$response = (object)array();
		$response->records = $records;
		if (isset($params->orderBy) && $params->orderBy) {
			if (getType($params->orderBy)=="string") {
				$orderBy = [$params->orderBy];
			} else {
				$orderBy = $params->orderBy;
			}
			usort($response->records, function($a, $b) use ($orderBy) {
				//Boucle sur tous les tris
				foreach ($orderBy as $pathString) {
					// Extrait le sens du tri
					$path = explode(" ",$pathString);
					if (count($path)>1) {
						$direction = strtoupper($path[1]);
					} else {
						$direction = "ASC";
					}
					$pathString = $path[0];

					$path = explode(".",$pathString);

					//Va chercher la valeur à trier dans A
					$aValue = $a;
					foreach($path as $deeper){
						$aValue = $aValue->{$deeper};
					}

					//Va chercher la valeur à trier dans B
					$bValue = $b;
					foreach($path as $deeper){
						$bValue = $bValue->{$deeper};
					}
					
					//echo $direction."->".$aValue."vs".$bValue."\n";
					
					if ($aValue>$bValue) {
						return $direction=="ASC" ? 1 : -1;
					} else if ($aValue<$bValue) {
						return $direction=="ASC" ? -1 : 1;
					}
				}
				return 0;
			});
		}
		
		
		//
		// Si on demande tous enregistrements en plus, on les envoi dans "allRecords" avant d'appliquer la limite 
		//
		if (isset($params->addAllRecords)) {
			$response->allRecords = $response->records;
		}
		
		//
		// J'applique la limite en enlevant les valeurs hors limite
		//
		if (isset($params->limit)) {
			$limit = (int)$params->limit;
			$page = isset($params->page) ? (int)$params->page : 1;
			$count = count($response->records);

			if( $count >0 ) {
				$total_pages = ceil($count/$limit);
			} else {
				$total_pages = 0;
			}
			$start = $limit*$page - $limit; 

			$response->currentPage = $page;
			$response->pagesCount = $total_pages;
			$response->totalRecordsCount = $count;

			$filteredRecords = [];
			foreach ($response->records as $ID => $record) {
				if ($ID>=$start && $ID<$start+$limit) {
					$filteredRecords[] = $record;
				}
			}
			
			$response->records = $filteredRecords;
			$response->recordsCount = count($response->records);
			$response->recordIndexFrom = ($response->currentPage * $limit) - ($limit - 1);
			$response->recordIndexTo =  min($response->currentPage * $limit, $response->totalRecordsCount);
			
			$response->limit = $params->limit;
		} else {
			$response->currentPage = 1;
			$response->pagesCount = 1;
			$response->totalRecordsCount = count($response->records);
			$response->recordsCount = count($response->records);
			$response->recordIndexFrom = 1;
			$response->recordIndexTo = $response->recordsCount;
			
			$response->limit = null;
		}
		return $response;
	}
	/**
	 * Enlève tous les attributs de l'object concerné en ne laissant que les attributs indiqué dans $allowedFields
	 * @param object $object
	 * @param array $allowedFields
	 * @param string $path
	 */
	static function deleteRestrictedKeys($object, $allowedFields, $path="") {
		if (gettype($object)=="object") {
			$keys = get_object_vars($object);
		} else {
			$keys = ($object);
		}

		//DEBUG::printr($keys);
		foreach ($keys as $key=>$value) {
			$xpath = ($path ? $path."." : "").$key;
			//echo $xpath."->".gettype($value)."<br/>";
			if (in_array(gettype($keys[$key]), ["object","array"])) {
				DB::deleteRestrictedKeys($value, $allowedFields, ($path ? $path."." : "").$key);
			} else {
				if (in_array($xpath, $allowedFields)===false) {
					if (gettype($object)=="object") {
						unset($object->{$key});
					} else {
						unset($object[$key]);
					}
				}
			}
		}
	}
	
	/**
	 * Applique les filtres à la méthode de l'ancien Loguy
	 * @param type $datas
	 * @param type $filters
	 * @return type
	 */
	static function applyFiltersOLDMethod($datas, $filters, $POST=null) {
		if ($filters) {
			$filters = json_decode($filters);
			$filters = $filters->rules;
			$results = [];
			foreach ($datas as $record) {
				foreach ($filters as $filter) {
					if (gettype($record)=="object") {
						//$newRowSQL = (object)array();
						//TOOLS::flatObject($record, $newRowSQL);

						$value = $record->{$filter->field};
					} else {
						$value = $record[$filter->field];
					}


					if ($filter->op=="bw") {
						if (!str_starts_with(strtolower($value), strtolower($filter->data))) {
							continue 2;
						}
					} else if ($filter->op=="e" || $filter->op=="eq") {
						if ($value != strtolower($filter->data)) {
							continue 2;
						}
					} else{
						if (!str_contains(strtolower($value), strtolower($filter->data))) {
							continue 2;
						}
					}
				}
				$results[] = $record;
			}

			$datas = $results;
		}
		
		if ($POST) {
			if (!isset($_POST['sidx']) || $_POST['sidx']=="none") { $_POST['sidx'] = null; }
			if (strpos($_POST['sidx'], " ")) {
				$_POST['sidx'] = explode(" ",$_POST['sidx']);
				if (strtoupper($_POST['sidx'][1])=="ASC") {
					$_POST['sidx'] = $_POST['sidx'][0];
					$_POST['sord'] = "asc";
				} else {
					$_POST['sidx'] = $_POST['sidx'][0];
					$_POST['sord'] = "desc";
				}
			}
			if ($_POST['sidx']) {
				
				// Maintenant je fait un tri en fonction de la date du problème par défaut 
				usort($datas, function($a, $b) {
					
					if (gettype($a)=="object") {
						
						$fieldA = $a->{$_POST['sidx']};
						$fieldB = $b->{$_POST['sidx']};
					} else {
						$fieldA = $a[$_POST['sidx']];
						$fieldB = $b[$_POST['sidx']];
					}
					
					
					if (!isset($fieldA)) {
						return 0;
					}
					$a = strtolower($fieldA);
					$b = strtolower($fieldB);

					if ($a<$b) { return $_POST['sord']=="desc" ? 1 : -1; }
					if ($a>$b) { return $_POST['sord']=="desc" ? -1 : 1; }
					return 0;
				});
			}
		}

		
		return $datas;
	}
	
	/**
	 * Pour une ligne de retour d'une fonction sql, renvoi tous les éléments commencant par "$search."
	 * @param object $obj
	 * @param string $search
	 * @return object
	 */
	static function extractFromSQLReturn($obj, $search) {
		$attributs = get_object_vars($obj);
		$object = (object)array();
		foreach ($attributs as $attributName => $attributValue) {
			if (substr($attributName,0,strlen($search)+1)==$search.".") {
				$object->{substr($attributName,strlen($search)+1)} = $attributValue;
			}
		}
		return $object;
	}

}
global $DB;
$DB = new DB();
