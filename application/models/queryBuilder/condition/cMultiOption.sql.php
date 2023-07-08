<?php

class CMultiOption
	extends SingleCondition {
	
	public function __construct (string $key, SQL|string...$val) {
		if ($val [0] instanceof SQL) {
			parent::__construct($key, "($val[0])", false);
		} else {
			parent::__construct($key, '("'.join('","', $val).'")');
		}
		
		$this->keyword = 'IN';
	}
	
}