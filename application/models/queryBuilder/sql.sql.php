<?php

use JetBrains\PhpStorm\Pure;

class SQL {
	private int    $type;
	private string $sql;
	private array  $bindable;
	
	public function __construct (string $sql, int $type, ?Bindable...$bindable) {
		$this->sql      = $sql;
		$this->bindable = $bindable ?? [];
		$this->type     = $type;
	}
	
	public function get_type (): int {
		return $this->type;
	}
	
	public function get_bind_value (): array {
		$result = [];
		foreach ($this->bindable as $item) {
			if ($item) {
				$result = array_merge($result, $item->bind_value());
			}
		}
		return $result;
	}
	
	public
	function get_sql (): string {
		return $this->sql;
	}
	
	#[Pure]
	public function __toString (): string {
		return $this->get_sql();
	}
	
	public function execute (): int|QueryFetcher {
		return (new QueryExecutor($this))->execute();
	}
	
	public function update_sql_after_clone (array $map): void {
		foreach ($map as $old => $new) {
			$this->sql = str_replace($old, $new, $this->sql);
		}
	}
	
}
