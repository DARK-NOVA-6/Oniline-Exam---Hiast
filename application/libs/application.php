<?php

use JetBrains\PhpStorm\Pure;

session_start();


class Application {
	private ?string $url_controller = null;
	private ?string $url_action     = null;
	private array   $url_parameter  = array();
	private array   $options        = array();
	
	private static ?Database $database  = null;
	private static array     $validator = [];
	
	public function __construct (Config $config) {
		$_SESSION['user_name']        ??= null;
		$_SESSION['test_center_name'] ??= null;
		$_SESSION['role_title']       ??= 'VISITOR';
		$_SESSION['photo_link']       ??= URL.'public/icon/person.svg';
		
		$this->build_static($config);
	}
	
	#[Pure]
	public static function get_db_connection (): PDO {
		return Application::$database->get_db();
	}
	
	
	public static function get_validator (): array {
		return Application::$validator;
	}
	
	public function run () {
		$this->splitUrl();
		$filename = "application/Controller/$this->url_controller.ctrl.php";
		if (file_exists($filename)) {
			$this->url_controller = $this->url_controller.'Ctrl';
			$controller           = new $this->url_controller();
			
			if (method_exists($controller, $this->url_action)) {
				$this->url_parameter[] = $this->options;
				$controller->{$this->url_action}(...$this->url_parameter);
			} else {
				$controller->index($this->options);
			}
		} else {
			
			die('NO HOME');
			//			$home = new HomeCtrl();
			//			$home->index();
		}
	}
	
	private function splitUrl () {
		$this->options = $_GET;
		if (isset($this->options['url'])) {
			unset($this->options['url']);
		}
		
		if (isset($_GET['url'])) {
			$this->url_parameter  = explode('/', rtrim($_GET['url'], '/'));
			$this->url_controller = $this->url_parameter[0] ?? null;
			$this->url_action     = $this->url_parameter[1] ?? null;
			array_shift($this->url_parameter);
			array_shift($this->url_parameter);
		}
		
	}
	
	private function build_static (Config $config): void {
		$this->build_database_connection($config->get_db_config());
		$this->build_validators($config->get_validator());
	}
	
	private function build_database_connection (array $config): void {
		Application::$database = new Database($config);
	}
	
	private function build_validators (array $config): void {
		Application::$validator = [];
		foreach ($config as $type => $arr) {
			foreach ($arr as $msg => $pattern) {
				Application::$validator[ $type ][] = new Validator($pattern, $msg);
			}
		}
		foreach (['email', 'name', 'password', 'phone'] as $type) {
			Application::$validator[ $type ] ??= [];
		}
	}
	
}