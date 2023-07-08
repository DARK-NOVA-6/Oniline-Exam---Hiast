<?php

abstract class Bindable {
	private static int $id = 0;
	
	
	protected static function get_id (): int {
		return ++Bindable::$id;
	}
	
	protected abstract function get_holder (): string;
	
	public abstract function bind_value (): array;
	
	public abstract function __clone (): void;
	
}

