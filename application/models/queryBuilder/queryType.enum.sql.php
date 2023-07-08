<?php

abstract class QueryType {
	const         SELECT = 1;
	const         UPDATE = 2;
	const         DELETE = 3;
	const         INSERT = 4;
	const         COUNT  = 5;
	const         SUM    = 6;
	const         DUAL   = 7;
	private const MSG    = [
	];
	
	public static function get_message (int $magick_number): ?string {
		return QueryType::MSG[ $magick_number ] ?? null;
	}
	
}
