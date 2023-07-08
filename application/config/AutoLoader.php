<?php

class AutoLoader {
	private static array $class_location     = [];
	private static array $spc_class_location = [];
	
	public static function register_class_location (string $suffix, string $path): void {
		AutoLoader::$class_location[ $suffix ] [] = $path;
	}
	
	public static function register_class (string $class_name, string $path): void {
		AutoLoader::$spc_class_location[ $class_name ] = $path;
	}
	
	public static function get_path (string $class_name): ?string {
		if (isset(self::$spc_class_location[ $class_name ])) {
			if (file_exists(AutoLoader::$spc_class_location[ $class_name ])) {
				return AutoLoader::$spc_class_location[ $class_name ];
			}
		}
		
		foreach (AutoLoader::$class_location as $suffix => $locations) {
			$name = basename(strtolower($class_name[0]).substr($class_name, 1), $suffix);
			foreach ($locations as $location) {
				$path = str_replace("#", $name, $location);
				if (file_exists($path)) {
					return $path;
				}
			}
		}
		
		return null;
	}
	
}