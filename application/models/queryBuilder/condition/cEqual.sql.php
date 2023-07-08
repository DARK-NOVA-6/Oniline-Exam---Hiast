<?php


class CEqual
	extends SingleCondition {
	
	public function __construct (string     $key,
	                             string|SQL $val,
	                             bool       $insensitive = true,
	                             bool       $sub_query_value = false
	) {
		parent::__construct($key, $val, $insensitive, $sub_query_value);
		$this->keyword = ' = ';
	}
	
}
