<?php


class Config {
	private array $config = [];
	
	
	/**
	 * @throws Exception
	 */
	public function set_db (array $config): Config {
		$config['dsn']     ??= 'mysql';
		$config['options'] ??= [
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];
		foreach (['dsn', 'host', 'name', 'username', 'password', 'options'] as $key) {
			if (!isset($config[ $key ])) {
				throw new Exception();
			}
			$this->config['db'][ $key ] = $config[ $key ];
		}
		return $this;
	}
	
	public function get_db_config (): array {
		return $this->config['db'];
	}
	
	public function get_validator (): array {
		return $this->config['validator'] ?? [];
	}
	
	public function register_name_validator (string $pattern, string $error = ''): Config {
		return $this->register_validator('name', $pattern, $error);
	}
	
	public function register_email_validator (string $pattern, string $error = ''): Config {
		return $this->register_validator('email', $pattern, $error);
	}
	
	public function register_phone_validator (string $pattern, string $error = ''): Config {
		return $this->register_validator('phone', $pattern, $error);
	}
	
	public function register_password_validator (string $pattern, string $error = ''): Config {
		return $this->register_validator('password', $pattern, $error);
	}
	
	private function register_validator (string $type, string $pattern, string $error = ''): Config {
		$this->config['validator'][ $type ][ $error ] = $pattern;
		return $this;
	}
	
}