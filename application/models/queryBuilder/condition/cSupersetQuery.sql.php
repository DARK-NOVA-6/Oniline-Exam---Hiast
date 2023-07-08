<?php

class CSupersetQuery
	extends Condition {
	protected QueryBuilder $query1;
	protected QueryBuilder $query2;
	protected string       $sql;
	
	public function __construct (QueryBuilder $query1, QueryBuilder $query2) {
		$this->query1 = $query1->clone_query()
		                       ->only_distinct(true)
		                       ->sub_query()
		                       ->count();
		$this->query2 = $query2->clone_query()
		                       ->only_distinct(true)
		                       ->sub_query()
		                       ->count();
		$temp         = $query2->clone_query()
		                       ->only_distinct(true)
		                       ->sub_query();
		foreach ($this->query1->get_columns() as $column) {
			$this->query1 = $this->query1->where(QueryBuilder::MULTI_OPT($column, $temp->get_statement()));
		}
		$this->sql = " (SELECT 1=1 FROM DUAL WHERE ( ";
		$this->sql .= $this->query1->get_statement()
		                           ->get_sql();
		$this->sql .= " ) = ( ";
		$this->sql .= $this->query2->get_statement()
		                           ->get_sql();
		$this->sql .= " ) ) IS NOT NULL ";
	}
	
	
	protected function get_holder (): string {
		return '';
	}
	
	public function bind_value (): array {
		return array_merge(
			$this->query1->get_statement()
			             ->get_bind_value(),
			$this->query2->get_statement()
			             ->get_bind_value()
		);
	}
	
	/**
	 * @throws Exception
	 */
	public function __clone (): void {
		throw new Exception();
		// TODO: Implement __clone() method.
	}
	
	public function __toString (): string {
		return $this->sql;
	}
	
}