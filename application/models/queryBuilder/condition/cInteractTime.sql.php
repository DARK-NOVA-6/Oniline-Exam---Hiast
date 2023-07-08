<?php


class CInteractTime
	extends Condition {
	private string $val_start_time;
	private string $val_end_time;
	private string $key_start_time;
	private string $key_end_time;
	
	public function __construct (string $start_time, string $duration, string $key_start_time, string $key_duration) {
		$start_time           = str_replace('-', '-', $start_time);
		$start_time           = str_replace(':', '-', $start_time);
		$this->val_start_time = " STR_TO_DATE('$start_time' , '%Y-%m-%d %H-%i-%s') ";
		$this->val_end_time   = " DATE_ADD( {$this->val_start_time} , INTERVAL $duration MINUTE) ";
		$this->key_start_time = $key_start_time;
		$this->key_end_time   = " DATE_ADD( {$key_start_time} , INTERVAL $key_duration MINUTE) ";
	}
	
	protected function get_holder (): string {
		return '';
	}
	
	public function bind_value (): array {
		return [];
	}
	
	private function get_one_intersect (string $x): string {
		//		return  " (s <= x && x <= e) ";
		return " ({$this->key_start_time} <= $x AND $x <= {$this->key_end_time}) ";
	}
	
	public function __toString (): string {
		//		return "OR";
		return " ({$this->get_one_intersect($this->val_start_time)} OR {$this->get_one_intersect($this->val_end_time)}) ";
	}
	
	public function __clone (): void {
		// TODO: Implement __clone() method.
	}
	
}