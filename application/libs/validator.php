<?php

class Validator {
	protected string $pattern;
	protected string $error;
	
	public function __construct (string $pattern, string $error) {
		$this->pattern = $pattern;
		$this->error   = $error;
	}
	
	public function get_error (): string {
		return $this->error;
	}
	
	public function is_valid (string $str): bool {
		return preg_match($this->pattern, $str);
	}
	
	
}