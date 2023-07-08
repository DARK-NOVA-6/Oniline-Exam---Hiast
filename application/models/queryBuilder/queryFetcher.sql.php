<?php

class QueryFetcher {
	private PDOStatement $query;
	
	public function __construct (PDOStatement $query) {
		$this->query = $query;
	}
	
	public function fetch_all_ASSOC (): array {
		return $this->query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function fetch_all_num (): array {
		return $this->query->fetchAll(PDO::FETCH_NUM);
	}
	
	public function has_result (): bool {
		return $this->query->rowCount() > 0;
	}
	
	public function fetch_one () {
		$temp = $this->query->fetch(PDO::FETCH_NUM);
		if ($temp === false) {
			return null;
		} else {
			return $temp;
		}
	}
	
	public function fetch_one_cell () {
		$temp = $this->fetch_one();
		if ($temp === null) {
			return null;
		} else {
			return $temp[0];
		}
	}
	
	public function fetch_one_ASSOC () {
		$temp = $this->query->fetch();
		if (!$temp) {
			return null;
		} else {
			return $temp;
		}
		//		return $this->query->fetch() ?? [];
	}
	
	public function fetch_one_column_num (): array {
		$temp = $this->query->fetchAll(PDO::FETCH_FUNC, fn($x) => $x);
		if ($temp === false) {
			return [];
		} else {
			return $temp;
		}
	}
	
	
}