<?php
/*
$this->checkExistColumn($table, $column)
$this->getJoinStatement($tables)
$this->specifyColumnsStatement($columns, $sql)
$this->getColumnsByEqualCondition($tables, $columns = array(), $conditions = array(), $FETCH = true)
$this->checkExistRow($tables, $conditions = array())
$this->getOneColumnByEqualCondition($tables, $column, $conditions = array(), $FETCH = true)
$this->getFirstCellByColumnAndEqualCondition($tables, $column, $conditions)
$this->deleteRowById($table, $id)
$this->insertRow($table, $newRow)
$this->updateRowById($table, $id, $newRow)

 */

/*

abstract class Column { // fix!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    public abstract function getStr();
}

class ColCount extends Column {

    public function __construct() {
        $this->columns = func_get_args();
    }

    public function getStr() {
        return ' COUNT(' . join(', ', $this->columns) . ') ';
    }
}

class ColSum extends Column {

    public function __construct() {
        $this->columns = func_get_args();
    }

    public function getStr() {
        return ' SUM(' . join(', ', $this->columns) . ') ';
    }
}

class ColAvg extends Column {

    public function __construct() {
        $this->columns = func_get_args();
    }

    public function getStr() {
        return ' AVG(' . join(', ', $this->columns) . ') ';
    }
}


 */

class Dbh
	extends BaseModel {
	
	protected $db = null;
	
	protected function __construct () {
		parent::__construct();
//		try {
//			$this->openDatabaseConnection();
//		} catch (PDOException $e) {
//			var_dump($e->getMessage());
//			die();
//		}
		// die();
	}
	
	private function openDatabaseConnection () {
		$options  = array(
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_CASE               => PDO::CASE_UPPER,
			PDO::ATTR_ORACLE_NULLS       => PDO::NULL_TO_STRING,
			// PDO::ATTR_AUTOCOMMIT => 0,
		);
		$this->db = new PDO('mysql'.':host='.'127.0.0.1'.';dbname='.'university', 'root', '', $options);
	}
	
	/*
	.
	.
	.
	.
	.
	.
	.
	.
	.
	.
	.
	.
	.
	 */
	public function SQL_getColumns ($columns = array(), $rename = true) {
		if (!is_array($columns)) {
			$columns = array($columns);
		}
		
		if (!count($columns)) {
			return ' * ';
		}
		
		if (!$rename) {
			return join(', ', $columns);
		}
		
		$resule = '';
		foreach ($columns as $col) {
			$resule = $resule.$col.' AS "'.strtoupper($col).'" , ';
		}
		return rtrim($resule, ', ');
	}
	
	public function SQL_getTables ($tables) {
		if (!is_array($tables)) {
			return $tables;
		} else {
			return join(', ', $tables);
		}
	}
	
	public function SQL_getLimit ($range) {
		
		if (!is_array($range)) {
			if (!$range) {
				return '';
			} else {
				$range = [1, $range];
			}
		}
		
		$start = $range[0];
		$end   = $range[1];
		return ' LIMIT '.($start - 1).' , '.($end - $start + 1).' ';
	}
	
	public function SQL_getOrder ($column, $asc = true) {
		return ' ORDER BY '.$column.($asc ? ' ASC ' : ' DESC ');
	}
	
	public function SQL_getJoinConditions ($tables) {
		if (!is_array($tables)) {
			return '';
		}
		
		$resule = '';
		foreach ($tables as $table) {
			foreach ($tables as $otherTable) {
				if ($this->checkExistColumn($table, $otherTable.'_id')) {
					$resule = $resule.$otherTable.'.id = '.$table.'.'.$otherTable.'_id AND ';
				}
			}
		}
		if (strlen($resule) > 0) {
			return ' ( '.rtrim($resule, ' AND ').' ) ';
		} else {
			return '';
		}
	}
	
	public static function addOptions ($options, $key, $val) {
		$key = strtoupper($key);
		if ($key == 'SORT') {
			$options[ self::$SORT_BY ] = $val;
		}
		if ($key == 'ASC' && (strtolower($val) === true || strtolower($val) === false)) {
			$options[ self::$SORT_ASC ] = strtolower($val);
		}
		if ($key == 'DISTINCT' && (strtolower($val) === true || strtolower($val) === false)) {
			$options[ self::$DISTINCT ] = strtolower($val);
		}
		if ($key == 'LIMIT' && is_array($val) && count($val) == 2) {
			$options[ self::$LIMIT ] = $val;
		}
		if ($key == 'SEARCH') {
			$likeCondition = array();
			$columns       = array_values($val)[0];
			$word          = array_keys($val)[0];
			foreach ($columns as $column) {
				array_push($likeCondition, new self::$C_SUBSTR($column, $word));
			}
			if (count($likeCondition) > 1) {
				$likeCondition = new self::$C_OR($likeCondition);
			}
			if (isset($options[ self::$CONDITION ])) {
				$likeCondition = new self::$C_AND($likeCondition, $options[ self::$CONDITION ]);
			}
			if (is_array($likeCondition)) {
				$options[ self::$CONDITION ] = $likeCondition[0];
			} else {
				$options[ self::$CONDITION ] = $likeCondition;
			}
		}
		
		return $options;
	}
	
	public static $COLUMNS   = 'columns';//T
	public static $COL_COUNT = 'ColCount';
	public static $COL_SUM   = 'ColSum';
	public static $COL_AVG   = 'ColAvg';
	
	public static $CONDITION = 'condition';//T
	public static $C_AND     = 'CAnd';
	public static $C_OR      = 'COr';
	public static $C_EQUAL   = 'CEqual';
	public static $C_NULL    = 'CNULL';
	public static $C_SUBSTR  = 'CSubStr';
	public static $C_IN      = 'CMultiOption';
	
	public static $SORT_BY    = 'sortCol'; //T
	public static $SORT_ASC   = 'sortType';//T
	public static $LIMIT      = 'limit';
	public static $FETCH      = 'fetch';
	public static $SQL        = 'sql';
	public static $DISTINCT   = 'distinct';//T
	public static $INSRT_QUER = 'isnertQuery';
	
	private function SQL_getCondition ($tables, $condition = null) {
		$join = $this->SQL_getJoinConditions($tables);
		if ($condition == null && strlen($join) == 0) {
			return '';
		}
		
		$sql = ' WHERE ';
		
		if ($condition != null) {
			$sql = $sql.'( ';
			if (gettype($condition) == 'object') {
				$sql = $sql.$condition->getStr();
			} else {
				$sql = $sql.' ( '.$condition.' ) ';
			}
			$sql = $sql.' ) ';
		}
		
		if ($condition != null && strlen($join) > 0) {
			$sql = $sql.' AND ';
		}
		
		return $sql.$join;
	}
	
	public function SQL_selectQuery ($tables, $options = array()) {
		if (!is_array($options)) {
			throw new Exception("Error Processing Request", 1);
		}
		
		if (isset($options[ self::$CONDITION ]) &&
		    gettype($options[ self::$CONDITION ]) == 'object' &&
		    !is_subclass_of($options[ self::$CONDITION ], 'Condition')
		) {
			throw new Exception("Error Processing Request", 1);
		}
		
		if (!isset($options[ self::$COLUMNS ])) {
			$options[ self::$COLUMNS ] = array();
		}
		if (isset($options[ self::$DISTINCT ]) && $options[ self::$DISTINCT ]) {
			$sql = 'SELECT DISTINCT '.$this->SQL_getColumns($options[ self::$COLUMNS ]);
		} else {
			$sql = 'SELECT '.$this->SQL_getColumns($options[ self::$COLUMNS ]);
		}
		
		$sql = $sql.' FROM '.$this->SQL_getTables($tables);
		
		if (!isset($options[ self::$CONDITION ])) {
			$options[ self::$CONDITION ] = null;
		}
		$sql = $sql.$this->SQL_getCondition($tables, $options[ self::$CONDITION ]);
		
		if (isset($options[ self::$SQL ]) && $options[ self::$SQL ]) {
			if (!isset($options[ self::$LIMIT ])) {
				$options[ self::$LIMIT ] = [1, 1];
			}
		}
		
		if (isset($options[ self::$SORT_BY ])) {
			if (!isset($options[ self::$SORT_ASC ])) {
				$options[ self::$SORT_ASC ] = true;
			}
			$sql = $sql.$this->SQL_getOrder($options[ self::$SORT_BY ], $options[ self::$SORT_ASC ]);
		}
		
		if (isset($options[ self::$LIMIT ])) {
			$sql = $sql.$this->SQL_getLimit($options[ self::$LIMIT ]);
		}
		
		if (isset($options[ self::$SQL ]) && $options[ self::$SQL ]) {
			return $sql;
		} else {
			return $sql.' ;';
		}
		
	}
	
	public function selectQuery ($tables, $options = array()) {
		
		if (isset($options[ self::$SQL ]) && $options[ self::$SQL ]) {
			return $this->SQL_selectQuery($tables, $options);
		}
		
		echo '<br>';
		echo '<br>';
		
		echo $this->SQL_selectQuery($tables, $options);
		echo '<br>';
		echo '<br>';
		echo '<br>';
		$query = $this->db->prepare($this->SQL_selectQuery($tables, $options));
		$query->execute();
		if (isset($options[ self::$FETCH ]) && !$options[ self::$FETCH ]) {
			return $query;
		} else {
			return $query->fetchAll();
		}
	}
	
	public function getAll ($tables, $options = array()) {
		return $this->selectQuery(
			$tables,
			$options
		);
	}
	
	public function getFirstCell ($tables, $options = array()) {
		if (!isset($options[ self::$COLUMNS ])) {
			throw new Exception("Error Processing Request", 1);
		}
		if (is_array($options[ self::$COLUMNS ])) {
			if (count($options[ self::$COLUMNS ]) != 1) {
				throw new Exception("Error Processing Request", 1);
			}
		}
		$options[ self::$LIMIT ] = [1, 1];
		
		if (isset($options[ self::$SQL ]) && $options[ self::$SQL ]) {
			return $this->SQL_selectQuery($tables, $options);
		}
		
		$options[ self::$FETCH ] = false;
		$query                   = $this->selectQuery($tables, $options)
		                                ->fetchAll(PDO::FETCH_FUNC, fn($val) => $val);
		if (!count($query)) {
			$query[0] = null;
		}
		return $query[0];
	}
	
	public function getIdFirstCell ($tables, $options = array()) {
		if (!is_array($tables)) {
			$options[ self::$COLUMNS ] = 'ID';
		} else {
			$options[ self::$COLUMNS ] = $tables[0].'.ID';
		}
		return $this->getFirstCell($tables, $options);
	}
	
	protected function GOODcheckExistRow ($tables, $options) {
		return $this->getIdFirstCell($tables, $options) != null;
	}
	
	/*
	returns true if there are some row with the specific
	conditions exists
	 */
	protected function checkExistRow ($tables, $conditions = array()) {
		return $this->getOneColumnByEqualCondition($tables, [], $conditions, false)
		            ->rowCount() > 0;
	}
	
	public function SQL_deleteQuery ($tables, $options = array()) {
		if (!is_array($options) || is_array($tables)) {
			throw new Exception("Error Processing Request", 1);
		}
		
		if (isset($options[ self::$CONDITION ]) &&
		    gettype($options[ self::$CONDITION ]) == 'object' &&
		    !is_subclass_of($options[ self::$CONDITION ], 'Condition')
		) {
			throw new Exception("Error Processing Request", 1);
		}
		
		$sql = 'DELETE FROM '.$tables;
		
		if (!isset($options[ self::$CONDITION ])) {
			$options[ self::$CONDITION ] = null;
		}
		$sql = $sql.$this->SQL_getCondition($tables, $options[ self::$CONDITION ]);
		
		return $sql.' ;';
	}
	
	public function deleteQuery ($tables, $options) {
		$query = $this->db->prepare($this->SQL_deleteQuery($tables, $options));
		$query->execute();
	}
	
	/*
	delete a specific row from table by it's id
	 */
	protected function deleteRowById ($table, $id) {
		$this->deleteQuery(
			$table,
			[
				self::$CONDITION => new self::$C_EQUAL('id', $id),
			]
		);
	}
	
	public function SQL_insertQuery (string $table, $columns, $selectQuery) {
		
		if (!is_array($columns)) {
			$columns = [$columns];
		}
		
		if ($selectQuery == null) {
			$ColTitles = array_keys($columns);
		} else {
			$ColTitles = $columns;
		}
		
		if (count($ColTitles) == 0) {
			throw new Exception("Error Processing Request", 1);
		}
		
		$sql = 'INSERT INTO '.$table.' ( '.join(', ', $ColTitles).' ) ';
		
		if ($selectQuery == null) {
			$sql = $sql.' VALUES ( "'.join('", "', array_values($columns)).'" ) ';
		} else {
			$sql = $sql.$selectQuery;
		}
		
		return $sql.' ;';
	}
	
	public function insertQuery ($table, $columns, $selectQuery = null) {
		if (!is_array($columns)) {
			$columns = [$columns];
		}
		
		$query = $this->db->prepare($this->SQL_insertQuery($table, $columns, $selectQuery));
		$query->execute();
	}
	
	public function SQL_getAttrUpdate ($newRow) {
		$sql = '';
		foreach ($newRow as $column => $value) {
			if (isset($value[ self::$SQL ])) {
				// this sql should be limited to one cell !!
				$sql = $sql.$column.' = ('.$value[ self::$SQL ].'), ';
			} else {
				$sql = $sql.$column.' = "'.$value.'", ';
			}
			
		}
		
		return rtrim($sql, ', ');
	}
	
	protected function SQL_updateQuery ($table, $newRow, $options) {
		if (is_array($table)) {
			if (count($table) != 1) {
				throw new Exception("Error Processing Request", 1);
			}
			
			$table = $table[0];
		}
		
		$sql = "UPDATE ".$table.' SET '.$this->SQL_getAttrUpdate($newRow);
		
		if (!isset($options[ self::$CONDITION ])) {
			$options[ self::$CONDITION ] = null;
		}
		$sql = $sql.$this->SQL_getCondition($table, $options[ self::$CONDITION ]);
		
		return $sql.' ;';
	}
	
	public function updateQuery ($table, $columns, $options) {
		$query = $this->db->prepare($this->SQL_updateQuery($table, $columns, $options));
		$query->execute();
	}
	
	/*
	.
	.
	.
	.
	.
	.
	.
	.
	.
	.
	.
	.
	.
	 */
	/*
	returns a SQL statement of the format:
	SELECT * FROM table1, table2, ... WHERE (condition for joining, according to the ids)
	PK of every table should be named as 'id', FK references to tableX should be named as 'tableX_id'
	 */
	private function getJoinStatement ($tables) {
		$sql       = 'SELECT * FROM ';
		$condition = ' WHERE ( ';
		foreach ($tables as $table) {
			$sql = $sql.$table.', ';
			foreach ($tables as $otherTable) {
				if ($this->checkExistColumn($table, $otherTable.'_id')) {
					$condition = $condition.$otherTable.'.id = '.$table.'.'.$otherTable.'_id AND ';
				}
			}
		}
		if ($condition == ' WHERE ( ') {
			$condition = $condition.' TRUE ';
		}
		$sql = rtrim($sql, ', ').rtrim($condition, ' AND ').' )';
		return $sql;
	}
	
	/*
	takes a SQL statement (no matter the syntax), replace each* by 'col1, col2, ...'
	and returns the new statement
	 */
	private function specifyColumnsStatement ($columns, $sql) {
		if (count($columns)) {
			$col = '';
			foreach ($columns as $column) {
				$col = $col.$column.' AS "'.$column.'", ';
			}
			$sql = str_replace('*', rtrim($col, ', ').' ', $sql);
		}
		return $sql;
	}
	
	/*
	takes many table names, some column (* if empty column) and an associative array for conditions
	where col => val means column 'col' should be equal to 'val' (everything if empty conditions)
	standard fetch mode in case FETCH = true
	 */
	protected function getColumnsByEqualCondition ($tables, $columns = array(), $conditions = array(), $FETCH = true) {
		if (gettype($tables) != 'array') {
			$tables = array($tables);
		}
		if (gettype($columns) != 'array') {
			$columns = array($columns);
		}
		$sql = $this->specifyColumnsStatement($columns, $this->getJoinStatement($tables));
		if (count($conditions)) {
			$sql = $sql.' AND ( ';
			foreach ($conditions as $key => $val) {
				$sql = $sql.$key.' = "'.$val.'" AND ';
			}
			$sql = rtrim($sql, ' AND ').' ) ;';
		} else {
			$sql = $sql.' ;';
		}
		$query = $this->db->prepare($sql);
		$query->execute();
		if ($FETCH) {
			if ($query->rowCount() > 0) {
				return $query->fetchAll();
			} else {
				return [];
			}
		} else {
			return $query;
		}
	}
	
	/*
	takes many table names, one column and an associative array for conditions
	where col => val means column 'col' should be equal to 'val' (everything if empty conditions)
	fetch mode: indexed array in case FETCH = true
	 */
	protected function getOneColumnByEqualCondition ($tables, $column, $conditions = array(), $FETCH = true) {
		$query = $this->getColumnsByEqualCondition($tables, $column, $conditions, false);
		if ($FETCH) {
			return $query->fetchAll(PDO::FETCH_FUNC, fn($val) => $val);
		} else {
			return $query;
		}
	}
	
	/*
	returns true if name of the column exists in the table
	 */
	protected function checkExistColumn ($table, $column) {
		$query = $this->db->prepare('SHOW COLUMNS FROM '.$table.' LIKE "'.$column.'" ;');
		$query->execute();
		return $query->rowCount() > 0;
	}
	
	/*
	returns a first cell by one column and some conditions (equal ...)
	 */
	protected function getFirstCellByColumnAndEqualCondition ($tables, $column, $conditions) {
		if ($this->checkExistRow($tables, $conditions)) {
			$column = strtoupper($column);
			return $this->getOneColumnByEqualCondition($tables, $column, $conditions, false)
			            ->fetch()[ $column ];
		} else {
			return null;
		}
	}
	
	/*
	update a specific row by id and an associative array were col => newValue
	 */
	protected function updateRowById ($table, $id, $newRow) {
		$sql = "UPDATE ".$table.' SET ';
		foreach ($newRow as $column => $value) {
			$sql = $sql.$column." = '".$value."', ";
		}
		$sql   = rtrim($sql, ', ').' WHERE ID = '.$id.';';
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	
	/*
	insert a new row in table by an associative array where col=>value
	 */
	protected function insertRow ($table, $newRow) {
		$sql = "INSERT INTO ".$table.' (';
		foreach (array_keys($newRow) as $column) {
			$sql = $sql.$column.', ';
		}
		$sql = rtrim($sql, ', ').') VALUES (';
		foreach (array_values($newRow) as $value) {
			$sql = $sql."'".$value."', ";
		}
		$sql   = rtrim($sql, ', ').') ;';
		$query = $this->db->prepare($sql);
		$query->execute();
	}
	
}

/*

temp function to test a transaction
 */

//     public function tt($v) {
//         /* Begin a transaction, turning off autocommit */
//         $stmt = $this->db->prepare('INSERT INTO my_table(my_id, my_value) VALUES(?, ?)');
//         $waiting = true; // Set a loop condition to test for
//         echo 'START';
// //        return;
//         while ($waiting) {
//             try {
//                 $this->db->beginTransaction();

//                 for ($i = $v; $i < 10000; $i++) {
//                     // if ($this->checkExistRow('my_table', 'my_id', array('my_id' => $i))) {
//                     //     // echo 'OP' . $i . ' <br>';
//                     //     continue;
//                     // }

//                     // echo 'I WILL ' . $i . '<br>';
//                     // $this->db->beginTransaction();
//                     // sleep(1);

//                     $stmt->bindValue(1, $i, PDO::PARAM_INT);
//                     $stmt->bindValue(2, $v, PDO::PARAM_STR);
//                     $stmt->execute();
//                     if ($i == 210) {
//                         $this->db->commit();
//                     }

//                     // sleep(1);
//                 }
//                 // $this->db->commit();

//                 $waiting = false;
//             } catch (PDOException $e) {
//                 echo 'WTF <br>';
//                 echo $e->getMessage() . ' FROM ' . $v . '<br>';
//                 if (stripos($e->getMessage(), 'DATABASE IS LOCKED') !== false) {
//                     // This should be specific to SQLite, sleep for 0.25 seconds
//                     // and try again.  We do have to commit the open transaction first though
//                     $this->db->commit();
//                     usleep(250000);
//                 } else {
//                     $this->db->rollBack();
//                     throw $e;
//                 }
//             }
//         }
//         echo 'DONE<br><br><br>';

//     }