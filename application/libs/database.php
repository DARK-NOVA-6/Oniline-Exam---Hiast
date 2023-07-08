<?php

class Database {
	
	private PDO $db;
	
	public function __construct (array $config) {
		$this->db = new PDO(
			"{$config['dsn']}:host={$config['host']};dbname={$config['name']}",
			$config['username'],
			$config['password'],
			$config['options'],
		);
	}
	
	
	
	public function get_db (): PDO {
		return $this->db;
	}
	
}