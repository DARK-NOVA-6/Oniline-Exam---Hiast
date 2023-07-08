<?php

use JetBrains\PhpStorm\Pure;

class UpdateAttribute
	extends BindableAttribute {
	
	/**
	 * @throws Exception
	 */
	public function __construct (string $key, string|SQL|QueryBuilder $val) {
		parent::__construct($key, $val);
	}
	
	#[Pure]
	public function __toString (): string {
		if ($this->val instanceof SQL) {
			return $this->key." = ".$this->val;
		} else {
			return $this->key." = ".$this->get_holder();
		}
	}
	
}