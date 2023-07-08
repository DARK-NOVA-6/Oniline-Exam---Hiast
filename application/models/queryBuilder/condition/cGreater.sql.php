<?php


class CGreater
	extends SingleCondition {
	
	public function __construct (string $key, string|SQL $val) {
		parent::__construct($key, $val, false);
		$this->keyword = ' >= ';
	}
	
}
