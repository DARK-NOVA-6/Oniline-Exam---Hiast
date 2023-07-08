<?php

class OptionItem {
	public function __construct (string $text, int $order, bool $isSelected) {
		$this->text       = $text;
		$this->order      = $order;
		$this->isSelected = ($isSelected) ?? false;
	}
	
}