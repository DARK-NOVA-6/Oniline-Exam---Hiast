<?php

abstract class MultipleCondition
	extends Condition {
	protected array  $conditions;
	protected string $keyword;
	
	public function __construct (Condition ...$conditions) {
		$this->conditions = $conditions;
	}
	
	public function get_holder (): string {
		return '';
	}
	
	public function bind_value (): array {
		$result = [];
		foreach ($this->conditions as $condition) {
			$result = array_merge($result, $condition->bind_value());
		}
		return $result;
	}
	
	public function __toString (): string {
		$result = '';
		foreach ($this->conditions as $condition) {
			if (strlen($condition) != 0) {
				$result .= "($condition) $this->keyword ";
			}
		}
		
		return rtrim($result, " $this->keyword ");
	}
	
	public function __clone (): void {
		$temp = [];
		foreach ($this->conditions as $condition) {
			$temp[] = clone $condition;
		}
		$this->conditions = $temp;
	}
	
	
}
