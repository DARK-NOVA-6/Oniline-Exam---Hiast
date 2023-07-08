<?php

declare(strict_types = 1);

use JetBrains\PhpStorm\Pure;

class QueryBuilder
	extends SqlWriter {
	
	/**
	 * @throws Exception
	 */
	private function queryType (int $type): QueryBuilder {
		$this->type = $type;
		return $this;
	}
	
	/**
	 * @throws Exception
	 */
	public function select (string...$col): QueryBuilder {
		$this->columns = $col;
		return $this->queryType(QueryType::SELECT);
	}
	
	/**
	 * @throws Exception
	 */
	public function update (UpdateAttribute...$col): QueryBuilder {
		$this->columns = $col;
		return $this->queryType(QueryType::UPDATE);
	}
	
	/**
	 * @throws Exception
	 */
	public function delete (): QueryBuilder {
		return $this->queryType(QueryType::DELETE);
	}
	
	/**
	 * @throws Exception
	 */
	public function insert (InsertAttribute...$columns): QueryBuilder {
		$this->columns = $columns;
		return $this->queryType(QueryType::INSERT);
	}
	
	/**
	 * @throws Exception
	 */
	public function count (): QueryBuilder {
		return $this->queryType(QueryType::COUNT);
	}
	
	/**
	 * @throws Exception
	 */
	public function sum (string $column): QueryBuilder {
		$this->columns = [$column];
		return $this->queryType(QueryType::SUM);
	}
	
	
	public function clone_query (): QueryBuilder {
		return clone $this;
	}
	
	public function __clone (): void {
		if ($this->condition !== null) {
			$this->condition = $this->condition->clone_condition();
		}
		
		if (isset($this->columns) && count($this->columns) > 0 && $this->columns[0] instanceof BindableAttribute) {
			$new_columns = [];
			foreach ($this->columns as $column) {
				$new_columns[] = clone $column;
			}
			$this->columns = $new_columns;
		}
		
	}
	
	public function from (string ...$tables): QueryBuilder {
		$this->tables = $tables;
		return $this;
	}
	
	public function into (string ...$tables): QueryBuilder {
		$this->tables = $tables;
		return $this;
	}
	
	public function sort_by (string $col_name): QueryBuilder {
		$this->sort_by = $col_name;
		return $this;
	}
	
	public function sort_asc (bool|string $val = true): QueryBuilder {
		if ($val === 'false') {
			$val = false;
		} else {
			$val = true;
		}
		$this->sort_ASC = (bool)$val;
		return $this;
	}
	
	public function only_distinct (bool $val): QueryBuilder {
		$this->distinct = $val;
		return $this;
	}
	
	public function set_search_for (string $word): QueryBuilder {
		$this->search_for = $word;
		return $this;
	}
	
	
	public function set_search_from (string...$columns): QueryBuilder {
		$this->search_from = $columns;
		return $this;
	}
	
	
	public function where (Condition $condition): QueryBuilder {
		if ($this->condition !== null) {
			$this->condition = QueryBuilder::AND(
				$this->condition,
				$condition
			);
		} else {
			$this->condition = $condition;
		}
		return $this;
	}
	
	public function limit (int $left, int $right): QueryBuilder {
		$this->limit = [$left - 1, $right - $left + 1];
		return $this;
	}
	
	public function sub_query (): QueryBuilder {
		$this->is_sub_query = true;
		return $this;
	}
	
	public function random (): QueryBuilder {
		$this->random = true;
		return $this;
	}
	
	/**
	 * @throws Exception
	 */
	public function get_statement (): SQL {
		$this->fix_conditions();
		
		if ($this->type == QueryType::SELECT) {
			return $this->sql_select();
		}
		
		if ($this->type == QueryType::DELETE) {
			return $this->sql_delete();
		}
		
		if ($this->type == QueryType::UPDATE) {
			return $this->sql_update();
		}
		
		if ($this->type == QueryType::INSERT) {
			return $this->sql_insert();
		}
		
		if ($this->type == QueryType::COUNT) {
			return $this->sql_count();
		}
		
		if ($this->type == QueryType::SUM) {
			return $this->sql_sum();
		}
		
		
		throw new Exception();
	}
	
	/**
	 * @throws Exception
	 */
	public function execute (): int|QueryFetcher {
		return $this->get_statement()
		            ->execute();
	}
	
	#[Pure]
	public static function AND (Condition...$condition): CAnd {
		return new CAnd(...$condition);
	}
	
	#[Pure]
	public static function OR (Condition...$condition): COr {
		return new COr(...$condition);
	}
	
	
	public static function EQUAL (string     $key,
	                              string|SQL $val,
	                              bool       $insensitive = true,
	                              bool       $sub_query_value = false
	): CEqual {
		return new CEqual($key, $val, $insensitive, $sub_query_value);
	}
	
	
	/**
	 * @throws Exception
	 */
	public static function MULTI_OPT (string $key, SQL|string...$val): CMultiEqual {
		return new CMultiEqual($key, ...$val);
	}
	
	/**
	 * @throws Exception
	 */
	#[Pure]
	public static function NOT_MULTI_OPT (string $key, SQL|string...$val): CNotMultiEqual {
		return new CNotMultiEqual($key, ...$val);
	}
	
	
	public static function NULL ($key): CNULL {
		return new CNULL($key);
	}
	
	/**
	 * @throws Exception
	 */
	public static function GREATER (string $key, string|SQL $val): CGreater {
		return new CGreater($key, $val);
	}
	
	
	/**
	 * @throws Exception
	 */
	public static function LIKE (string $key, string|SQL $val, bool $insensitive = true): CSubStr {
		return new CSubStr($key, $val, $insensitive);
	}
	
	/**
	 * @throws Exception
	 */
	public static function INSERT_ATTR (string $column, string|SQL|QueryBuilder $val): InsertAttribute {
		return new InsertAttribute($column, $val);
	}
	
	/**
	 * @throws Exception
	 */
	public static function UPDATE_ATTR (string $column, string|SQL|QueryBuilder $val): UpdateAttribute {
		return new UpdateAttribute($column, $val);
	}
	
	#[Pure]
	public static function INTERSECT_TIME (string $start_time,
	                                       string $duration,
	                                       string $key_start_time,
	                                       string $key_duration
	): CInteractTime {
		return new CInteractTime($start_time, $duration, $key_start_time, $key_duration);
	}
	
	public static function SUPERSET_QUERY (QueryBuilder $query1, QueryBuilder $query2): CSupersetQuery {
		return new CSupersetQuery($query1, $query2);
	}
	
	/**
	 * @throws Exception
	 */
	private function fix_conditions (): void {
		if ($this->search_for == null) {
			return;
		}
		
		if ($this->condition == null) {
			$this->condition = $this->get_search_condition();
		} else {
			$this->condition = QueryBuilder::AND($this->condition, $this->get_search_condition());
		}
	}
	
	/**
	 * @throws Exception
	 */
	private function get_search_condition (): Condition {
		if ($this->type != QueryType::SELECT && $this->type != QueryType::COUNT) {
			throw new Exception();
		}
		$this->search_from ??= $this->columns;
		//				echo '<pre>';
		//				var_dump($this->columns);
		//				echo '</pre>';
		$conditions = [];
		foreach ($this->search_from as $column) {
			$conditions[] = QueryBuilder::LIKE($column, $this->search_for);
		}
		return QueryBuilder::OR(...$conditions);
	}
	
	public function get_columns (): array {
		return $this->columns;
	}
	
	
}
