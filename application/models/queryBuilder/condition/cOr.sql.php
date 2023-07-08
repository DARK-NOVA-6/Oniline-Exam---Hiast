<?php

use JetBrains\PhpStorm\Pure;

class COr
	extends MultipleCondition {
	
	#[Pure]
	public function __construct (Condition ...$conditions) {
		parent::__construct(...$conditions);
		$this->keyword = 'OR';
	}
	
}