<?php


class  QueryExecutor {
	private SQL $sql;
	
	public function __construct (SQL $sql) {
		$this->sql = $sql;
	}
	
	public function execute (): QueryFetcher|int {
		
		$query = Application::get_db_connection()
		                    ->prepare($this->sql->get_sql());
		//				echo '<br><br>'.$this->sql.'<br><br>';
		//				echo '<pre>';
		//				print_r($this->sql->get_bind_value());
		//				echo '</pre>';
		
		//              die();
		
		//		var_dump($query->execute($this->sql->get_bind_value()));
		$query->execute($this->sql->get_bind_value());
		if ($this->sql->get_type() == QueryType::SELECT) {
			return new QueryFetcher($query);
		}
		if ($this->sql->get_type() == QueryType::COUNT) {
			return new QueryFetcher($query);
		}
		if ($this->sql->get_type() == QueryType::SUM) {
			return new QueryFetcher($query);
		}
		
		
		if ($this->sql->get_type() == QueryType::INSERT) {
			return Application::get_db_connection()
			                  ->lastInsertId();
		}
		return 0;
	}
	
	
}
