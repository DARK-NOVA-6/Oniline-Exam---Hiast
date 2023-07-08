<?php

use JetBrains\PhpStorm\Pure;

class CAnd
	extends MultipleCondition {
	
	#[Pure]
	public function __construct (Condition ...$conditions) {
		parent::__construct(...$conditions);
		$this->keyword = 'AND';
	}
	
}
