<?php

class BindableAttribute
	extends Bindable {
	protected string     $key;
	protected string|SQL $val;
	protected int        $curr_id;
	
	/**
	 * @throws Exception
	 */
	public function __construct (string $key, string|SQL|QueryBuilder $val) {
		if ($val instanceof QueryBuilder) {
			$val = $val->sub_query()
			           ->get_statement();
		}
		if ($val instanceof SQL) {
			$this->val = $val;
		} else {
			$this->val = "$val";
		}
		$this->curr_id = Bindable::get_id();
		$this->key     = $key;
	}
	
	public function get_holder (): string {
		return ":$this->key$this->curr_id";
	}
	
	public function bind_value (): array {
		if ($this->val instanceof SQL) {
			return $this->val->get_bind_value();
		} else {
			return [$this->get_holder() => $this->val];
		}
	}
	
	public function __clone (): void {
		if ($this->val instanceof SQL) {
			$old       = array_keys($this->val->get_bind_value());
			$this->val = clone $this->val;
			$new       = array_keys($this->val->get_bind_value());
			$map       = [];
			for ($i = 0 ; $i < count($new) ; $i++) {
				$map [ $old[ $i ] ] = $new[ $i ];
			}
			$this->val->update_sql_after_clone($map);
		}
		$this->curr_id = Bindable::get_id();
	}
	
}