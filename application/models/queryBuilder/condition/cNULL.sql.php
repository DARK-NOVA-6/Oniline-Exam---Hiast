<?php

class CNULL
	extends SingleCondition {
	
	public function __construct (string $key) {
		parent::__construct($key, '', false);
		$this->key = $key;
	}
	
	public function __toString (): string {
		return "$this->key IS NULL";
	}
	
	public function bind_value (): array {
		return [];
	}
	
}
