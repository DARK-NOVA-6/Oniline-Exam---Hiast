<?php

abstract class NameErrorEnum {
	const         EMPTY = 1;
	const         SHORT = 2;
	private const MSG   = [
		NameErrorEnum::EMPTY => 'empty name',
		NameErrorEnum::SHORT => 'short name',
	];
	
	public static function get_message (int $magick_number): ?string {
		return NameErrorEnum::MSG[ $magick_number ] ?? null;
	}
	
}

