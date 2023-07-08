<?php

use JetBrains\PhpStorm\Pure;

class CNotMultiEqual
	extends CMultiEqual {
	/**
	 * @throws Exception
	 */
	public function __construct (string $key, QueryBuilder|string|SQL ...$val) {
		parent::__construct($key, ...$val);
		$this->key_word = "NOT IN";
	}
	
}