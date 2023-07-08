<?php
declare(strict_types = 1);


use JetBrains\PhpStorm\Pure;

abstract class SingleCondition
	extends Condition {
	protected string     $key;
	protected string|SQL $val;
	protected bool       $insensitive;
	protected string     $keyword;
	protected int        $curr_id;
	protected bool       $sub_query_value;
	
	/**
	 * @throws Exception
	 */
	public function __construct (string                  $key,
	                             string|QueryBuilder|SQL $val,
	                             bool                    $insensitive = true,
	                             bool                    $sub_query_value = false
	) {
		if ($val instanceof QueryBuilder) {
			$val = $val->sub_query()
			           ->get_statement();
		}
		if ($val instanceof SQL) {
			$this->val = $val;
		} else {
			$this->val = "$val";
		}
		
		$this->curr_id         = Bindable::get_id();
		$this->key             = $key;
		$this->insensitive     = $insensitive;
		$this->sub_query_value = $sub_query_value;
	}
	
	public function get_holder (): string {
		if ($this->sub_query_value) {
			return $this->val;
		}
		return ":".str_replace('.', '_', $this->key).$this->curr_id;
	}
	
	#[Pure]
	public function __toString (): string {
		if ($this->val instanceof SQL) {
			if (!$this->insensitive) {
				return $this->key." $this->keyword "." ( $this->val ) ";
			} else {
				return "UPPER($this->key) $this->keyword ".strtoupper(" ( $this->val ) ");
			}
		}
		
		if (!$this->insensitive) {
			return $this->key." $this->keyword ".$this->get_holder();
		}
		return "UPPER($this->key) $this->keyword ".$this->get_holder();
	}
	
	public function bind_value (): array {
		if ($this->val instanceof SQL) {
			return $this->val->get_bind_value();
		}
		
		if ($this->sub_query_value)
			return [];
		
		if (!$this->insensitive) {
			return [$this->get_holder() => $this->val];
		} else {
			return [$this->get_holder() => strtoupper($this->val)];
		}
	}
	
	public function __clone (): void {
		$this->curr_id = Bindable::get_id();
	}
	
}
