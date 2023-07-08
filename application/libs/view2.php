<?php

class View2 {
	private array $table;
	private array $columns;
	
	public function __construct ($columns, $table) {
		$this->table   = $table;
		$this->columns = $columns;
	}
	
	public function render (array $arr): void {
		echo '<br> RENDER::: <br>';
		
		echo '<pre>';
		print_r($this->columns);
		echo '</pre>';
		echo '<pre>';
		print_r($this->table);
		echo '</pre>';
		
	}
	
}