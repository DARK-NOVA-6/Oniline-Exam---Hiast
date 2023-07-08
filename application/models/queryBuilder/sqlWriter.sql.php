<?php


abstract class SqlWriter {
	protected ?array     $tables       = null;
	protected ?array     $columns      = null;
	protected ?Condition $condition    = null;
	protected ?string    $sort_by      = null;
	protected bool       $sort_ASC     = true;
	protected bool       $distinct     = false;
	protected ?int       $type         = null;
	protected ?array     $limit        = null;
	protected bool       $is_sub_query = false;
	protected ?string    $search_for   = null;
	protected ?array     $search_from  = null;
	protected bool       $random       = false;
	
	/**
	 * @throws Exception
	 */
	protected function sql_delete (): SQL {
		if (count($this->tables) != 1) {
			throw new Exception();
		}
		
		$sql = 'DELETE ';
		$sql .= $this->sql_get_tables();
		$sql .= $this->sql_get_conditions();
		$sql .= ';';
		
		return new SQL($sql, $this->type, $this->condition);
	}
	
	/**
	 * @throws Exception
	 */
	protected function sql_update (): SQL {
		if (count($this->tables) != 1) {
			throw new Exception();
		}
		$this->columns = $this->columns ?? [];
		if (count($this->columns) == 0) {
			throw new Exception();
		}
		$table = $this->tables[0];
		$sql   = "UPDATE ".$table;
		$sql   .= $this->sql_get_attr_update();
		$sql   .= $this->sql_get_conditions();
		$sql   .= ';';
		return new SQL($sql, $this->type, ...$this->columns);
	}
	
	
	/**
	 * @throws Exception
	 */
	protected function sql_select (): SQL {
		if ($this->tables == null || count($this->tables) == 0) {
			throw new Exception();
		}
		
		$this->columns = $this->columns ?? [];
		
		$sql = 'SELECT ';
		
		if ($this->distinct) {
			$sql .= 'DISTINCT ';
		}
		
		$sql .= $this->sql_get_columns();
		$sql .= $this->sql_get_tables();
		$sql .= $this->sql_get_conditions();
		$sql .= $this->sql_get_order();
		$sql .= $this->sql_get_limit();
		
		if (!$this->is_sub_query) {
			$sql .= ' ;';
		}
		return new SQL($sql, $this->type, $this->condition);
	}
	
	/**
	 * @throws Exception
	 */
	protected function sql_insert (): SQL {
		if (count($this->tables) != 1) {
			throw new Exception();
		}
		$table = $this->tables[0];
		$sql   = "INSERT INTO $table ";
		$sql   .= $this->sql_get_attr_insert_col();
		$sql   .= $this->sql_get_attr_insert_val();
		$sql   .= ';';
		return new SQL($sql, $this->type, ...$this->columns);
	}
	
	/**
	 * @throws Exception
	 */
	protected function sql_count (): SQL {
		$sql = "SELECT COUNT(*) ";
		$sql .= $this->sql_get_tables();
		$sql .= $this->sql_get_conditions();
		
		if (!$this->is_sub_query) {
			$sql .= ' ;';
		}
		return new SQL($sql, $this->type, $this->condition);
	}
	
	/**
	 * @throws Exception
	 */
	protected function sql_sum (): SQL {
		$sql = "SELECT SUM({$this->columns[0]}) ";
		$sql .= $this->sql_get_tables();
		$sql .= $this->sql_get_conditions();
		
		if (!$this->is_sub_query) {
			$sql .= ' ;';
		}
		return new SQL($sql, $this->type, $this->condition);
	}
	
	
	private function sql_get_attr_insert_col (): string {
		$sql = '';
		foreach ($this->columns as $column) {
			$sql .= $column->get_column().", ";
		}
		if (strlen($sql) > 0) {
			return " ( ".rtrim($sql, ", ")." ) ";
		} else {
			return "";
		}
	}
	
	private function sql_get_attr_insert_val (): string {
		$sql = '';
		foreach ($this->columns as $column) {
			if ($column->is_sql()) {
				$sql .= "( ".$column->get_sql()." ) , ";
			} else {
				$sql .= $column->get_holder().", ";
			}
		}
		if (strlen($sql) > 0) {
			return " VALUES ( ".rtrim($sql, ", ")." )";
		} else {
			return " VALUES () ";
		}
	}
	
	/**
	 * @throws Exception
	 */
	private function sql_get_attr_update (): string {
		if ($this->columns == null) {
			throw new Exception();
		}
		$sql = ' SET ';
		foreach ($this->columns as $column) {
			$sql .= "$column , ";
		}
		return rtrim($sql, ', ')." ";
	}
	
	
	/**
	 * @throws Exception
	 */
	private function sql_get_columns (): string {
		if (count($this->columns) == 0) {
			return '* ';
		}
		$result = '';
		foreach ($this->columns as $column) {
			$result .= $this->get_parameter_flag($column)." , ";
		}
		return rtrim($result, ', ');
	}
	
	private function sql_get_tables (): string {
		$result = '';
		foreach ($this->tables as $table) {
			$result .= $this->get_parameter_flag($table)." , ";
		}
		return ' FROM '.rtrim($result, ', ');
	}
	
	private function get_parameter_flag (string $param): string {
		if (count(explode('#', $param)) > 1) {
			return explode('#', $param)[0]." AS `".explode('#', $param)[1]."`";
		} else {
			if (str_contains($param, '.')) {
				return $this->get_parameter_flag("$param#$param");
			}
			return $param;
		}
	}
	
	
	private function sql_get_conditions (): string {
		$join = $this->sql_get_join_conditions();
		if (strlen($join) == 0 && $this->condition == null) {
			return '';
		}
		
		if ($this->condition == null) {
			return " WHERE $join";
		}
		
		if (strlen($join) > 0) {
			return " WHERE ($this->condition) AND $join";
		} else {
			return " WHERE $this->condition";
		}
	}
	
	private function sql_get_join_conditions (): string {
		$sql  = '';
		$base = new BaseModel();
		foreach ($this->tables as $table1) {
			foreach ($this->tables as $table2) {
				$table_name1 = explode('#', $table1)[0];
				$table_name2 = explode('#', $table2)[0];
				if ($base->column_exists($table_name1, $table_name2."_id")) {
					$sql .= "$table_name2.id = $table_name1.$table_name2"."_id AND ";
				}
			}
		}
		
		if (strlen($sql) > 0) {
			return ' ( '.rtrim($sql, ' AND').' ) ';
		}
		return '';
	}
	
	private function sql_get_order (): string {
		if ($this->random) {
			return " ORDER BY rand() ";
		}
		
		if ($this->sort_by != null) {
			return ' ORDER BY '.$this->sort_by.($this->sort_ASC ? ' ASC ' : ' DESC ');
		} else {
			return '';
		}
	}
	
	private function sql_get_limit (): string {
		if ($this->limit != null) {
			return ' LIMIT '.$this->limit[0].' , '.$this->limit[1].' ';
		} else {
			return '';
		}
	}
	
}