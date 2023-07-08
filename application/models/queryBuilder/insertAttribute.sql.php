<?php

use JetBrains\PhpStorm\Pure;

class InsertAttribute
	extends BindableAttribute {
	
	/**
	 * @throws Exception
	 */
	public function __construct (string $key, string|SQL|QueryBuilder $val) {
		parent::__construct($key, $val);
	}
	
	public function get_column (): string {
		return $this->key;
	}
	
	public function is_sql (): bool {
		return $this->val instanceof SQL;
	}
	
	#[Pure]
	public function get_sql (): string {
		return $this->val->get_sql();
	}
	
	
}