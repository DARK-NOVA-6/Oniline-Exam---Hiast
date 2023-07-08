<?php

use JetBrains\PhpStorm\Pure;

class BaseModel {
	private PDO $db;
	
	#[Pure]
	public function __construct () {
		$this->db = Application::get_db_connection();
	}
	
	public function column_exists (string $table, string $column): bool {
		$query = $this->db->prepare("SHOW COLUMNS FROM $table LIKE :column;");
		$query->execute([":column" => $column]);
		return $query->rowCount() > 0;
	}
	
	
	protected function get_all (array $tables, array $columns, array $options): array {
		try {
			return $this->get_query_builder($options)
			            ->select(...$columns)
			            ->from(...$tables)
			            ->execute()
			            ->fetch_all_num();
			
		} catch (Exception $e) {
			return [];
		}
	}
	
	/**
	 * @throws Exception
	 */
	protected function get_count (array|string $tables, array|string $columns, array $options): int {
		if (!is_array($tables)) {
			$tables = [$tables];
		}
		if (!is_array($columns)) {
			$columns = [$columns];
		}
		if (isset($options['search_for'])) {
			$options['search_from'] = join(',', $columns);
		}
		return $this->get_query_builder($options)
		            ->count()
		            ->from(...$tables)
		            ->execute()
		            ->fetch_one()[0];
	}
	
	
	/**
	 * @throws Exception
	 */
	protected function delete_by_id (string $table, $id): void {
		(new QueryBuilder())->delete()
		                    ->from($table)
		                    ->where(QueryBuilder::EQUAL('id', $id))
		                    ->execute();
	}
	
	protected function get_query_builder (array $options = array()): QueryBuilder {
		$query = new QueryBuilder();
		$query = $this->set_limit($options, $query);
		$query = $this->set_sorting($options, $query);
		$query = $this->set_distinct($options, $query);
		return $this->set_search($options, $query);
	}
	
	private function set_search (array $options, QueryBuilder $builder): QueryBuilder {
		if (!isset($options['search_for'])) {
			return $builder;
		}
		
		if (isset($options['search_from'])) {
			return $builder->set_search_from(...explode(',', $options['search_from']))
			               ->set_search_for($options['search_for']);
		} else {
			return $builder->set_search_for($options['search_for']);
		}
	}
	
	private function set_distinct (array $options, QueryBuilder $builder): QueryBuilder {
		return $builder->only_distinct($options['distinct'] ?? false);
	}
	
	private function set_sorting (array $options, QueryBuilder $builder): QueryBuilder {
		$options['sort_asc'] ??= true;
		if (!isset($options['sort_by'])) {
			return $builder;
		}
		
		return $builder->sort_by($options['sort_by'])
		               ->sort_asc($options['sort_asc']);
	}
	
	private function set_limit (array $options, QueryBuilder $builder): QueryBuilder {
		$options['no_limit'] ??= false;
		if ($options['no_limit']) {
			return $builder;
		}
		
		$page = 1;
		if (isset($options['page'])) {
			$page = $options['page'];
		}
		$size = 20;
		if (isset($options['size'])) {
			$size = $options['size'];
		}
		return $builder->limit($size * ($page - 1) + 1, $size * $page);
	}
	
}