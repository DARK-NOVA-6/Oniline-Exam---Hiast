<?php

use JetBrains\PhpStorm\Pure;


class CMultiEqual
	extends Condition {
	protected string $key;
	protected ?SQL   $val_sql      = null;
	protected array  $val_array_id = [];
	protected array  $val_array    = [];
	protected bool   $empty        = false;
	protected string $key_word     = "in";
	
	/**
	 * @throws Exception
	 */
	public function __construct (string $key, QueryBuilder|string|SQL ...$val) {
		
		if (count($val) != 0) {
			if ($val[0] instanceof QueryBuilder) {
				$val[0] = $val[0]->sub_query()
				                 ->get_statement();
			}
			if ($val[0] instanceof SQL) {
				$val           = $val[0];
				$this->val_sql = $val;
			} else {
				$this->val_array = $val;
				foreach ($this->val_array as $one_value) {
					$this->val_array_id[ $one_value ] = Bindable::get_id();
				}
			}
			$this->key = $key;
		} else {
			$this->empty = true;
		}
	}
	
	
	protected function get_holder (): string {
		$holder = '';
		foreach ($this->val_array_id as $value => $val_id) {
			$holder .= ":".str_replace('.', '_', $this->key).$val_id." , ";
		}
		return rtrim($holder, ", ");
	}
	
	public
	function bind_value (): array {
		if ($this->val_sql !== null) {
			return $this->val_sql->get_bind_value();
		}
		
		$result = [];
		foreach ($this->val_array_id as $value => $val_id) {
			$result [ ":".str_replace('.', '_', $this->key).$val_id ] = strtoupper($value);
		}
		return $result;
	}
	
	#[Pure]
	public function __toString (): string {
		if ($this->val_sql !== null) {
			return " $this->key {$this->key_word} ( ".$this->val_sql." ) ";
		}
		if ($this->empty) {
			return '';
		} else {
			return " $this->key {$this->key_word} ( ".$this->get_holder()." ) ";
		}
	}
	
	public function __clone (): void {
		if ($this->val_sql !== null) {
			$this->val_sql = clone $this->val_sql;
		}
		
		$temp = [];
		foreach ($this->val_array_id as $key => $value) {
			$temp[ $key ] = BindableAttribute::get_id();
		}
		$this->val_array_id = $temp;
	}
	
}