<?php


abstract class Condition
	extends Bindable {
	public abstract function __toString (): string;
	
	public function clone_condition (): Condition {
		return clone $this;
	}
	
}

