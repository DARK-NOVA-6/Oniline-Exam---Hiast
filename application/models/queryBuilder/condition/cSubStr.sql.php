<?php

class CSubStr
	extends SingleCondition {
	
	/**
	 * @throws Exception
	 */
	public function __construct (string $key, string|SQL $val, bool $insensitive = true) {
		if ($val instanceof SQL) {
			throw new Exception();
		}
		parent::__construct($key, "%$val%", $insensitive);
		$this->keyword = 'LIKE';
	}
	
}
