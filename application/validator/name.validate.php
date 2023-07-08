<?php

use JetBrains\PhpStorm\Pure;

require_once 'application/libs/validator.php';


class NameValidator
	extends Validator {
	public string $error;
	
	#[Pure]
	public function __construct (string $pattern, int $error) {
		parent::__construct($pattern);
		$this->error = $error;
	}
	
	public function get_error (): string {
		return $this->error;
	}
	
	
}