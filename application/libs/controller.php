<?php


class Controller {
	
	protected function get_all (array $tables, array $columns, array $option): array {
	
	}
	
	protected function validate (string $str, Validator...$validators): ?string {
		foreach ($validators as $validator) {
			if (!$validator->is_valid($str)) {
				return $validator->get_error();
			}
		}
		return null;
	}
	
	
	/**
	 * @param string $model_name
	 *
	 * @return mixed
	 */
	public function loadModel (string $model_name) {
		$model_name_lower = strtolower($model_name);
		$filename         = "application/models/$model_name_lower.model.php";
		$model_name = $model_name.'Model';
		return new $model_name();
	}
	
	/**
	 * @param string $controller_name
	 *
	 * @return mixed
	 */
	public function loadController (string $controller_name) {
		$controller_name_lower = strtolower($controller_name);
		$filename              = "application/controller/$controller_name_lower.ctrl.php";
		require_once $filename;
		
		$controller_name = $controller_name.'Ctrl';
		return new $controller_name();
	}
	
	public function someError ($msg = 'Please try again later!!') {
		die($msg);
	}
	
	/**
	 * @param array $viewOptions
	 * @param array $mapping
	 *
	 * @return array
	 */
	public function getOptions (array $viewOptions, array $mapping = array()): array {
		$options     = array();
		$viewOptions = array_change_key_case($viewOptions, CASE_UPPER);
		foreach (['SORT', 'ASC', 'DISTINCT'] as $key) {
			if (isset($viewOptions[ $key ])) {
				$options = Dbh::addOptions($options, $key, $viewOptions[ $key ]);
			}
		}
		
		{
			
			$page    = $viewOptions['PAGE'] ?? 1;
			$size    = $viewOptions['SIZE'] ?? 20;
			$options = Dbh::addOptions($options, 'LIMIT', [($page - 1) * $size + 1, $page * $size]);
			
		}
		
		if (isset($viewOptions['SEARCH'])) {
			$search = explode(',', rtrim($viewOptions['SEARCH'], ','));
			if (count($search) > 0) {
				$word = trim($search[ count($search) - 1 ]);
				unset($search[ count($search) - 1 ]);
				
				$columns = array();
				foreach ($search as $key) {
					if (isset($mapping[ trim($key) ])) {
						$columns[] = $mapping[ trim($key) ];
					}
				}
				
				if (count($columns) == 0) {
					$columns = array_values($mapping);
				}
				
				$options = Dbh::addOptions($options, 'SEARCH', [$word => $columns]);
				
			}
		}
		
		return $options;
	}
	
}