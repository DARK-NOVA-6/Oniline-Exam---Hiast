<?php


class DashboardCtrl
	extends Controller {
	public function __construct () {
	
	}
	
	/**
	 * @throws Exception
	 */
	private function render_table (Dashboardable $ctrl, array $options) {
		$options = $this->init_options_cookies($options);
		$table   = $this->get_table_fn($ctrl, $options);
		$from    = ($table['page'] - 1) * $table['size'] + 1;
		$to      = ($table['page'] - 1) * $table['size'] + count($table['data']);
		if ($from > $to) {
			if ($options ['page'] != 1) {
				$options['page'] = 1;
				$this->render_table($ctrl, $options);
			} else {
				$from = $to = 0;
			}
		}
		$ctrl->get_table_view()
		     ->render(
			     $table['columns'],
			     $table['data'],
			     $table['size'],
			     $table['count'],
			     $from,
			     $to,
		     );
	}
	
	/**
	 * @throws Exception
	 */
	private function get_table_fn (Dashboardable $ctrl, array $options): array {
		return $ctrl->get_table($options);
	}
	
	private function get_more_details (Dashboardable $ctrl, int $id): array {
		return $ctrl->get_more_details($id);
	}
	
	public function user_data (array $options): array {
		return $this->get_table_fn(new UserCtrl(), $options);
	}
	
	public function role_data (array $options): array {
		return $this->get_table_fn(new RbacCtrl(), $options);
	}
	
	public function course_data (array $options): array {
		return $this->get_table_fn(new CourseCtrl(), $options);
	}
	
	public function topic_data (array $options): array {
		return $this->get_table_fn(new TopicCtrl(), $options);
	}
	
	public function question_data (array $options): array {
		return $this->get_table_fn(new QuestionCtrl(), $options);
	}
	
	public function test_center_data (array $options): array {
		return $this->get_table_fn(new TestCenterCtrl(), $options);
	}
	
	public function user (array $options): void {
		$this->render_table(new UserCtrl(), $options);
	}
	
	public function role (array $options): void {
		$this->render_table(new RbacCtrl(), $options);
	}
	
	public function course (array $options): void {
		$this->render_table(new CourseCtrl(), $options);
	}
	
	public function topic (array $options): void {
		$this->render_table(new TopicCtrl(), $options);
	}
	
	public function question (array $options): void {
		$this->render_table(new QuestionCtrl(), $options);
	}
	
	public function test_center (array $options): void {
		$this->render_table(new TestCenterCtrl(), $options);
	}
	
	public function user_more_details (int $id): int {
		$this->get_more_details(new UserCtrl(), $id);
	}
	
	public function role_more_details (int $id): int {
		var_dump($id);
		$this->get_more_details(new RbacCtrl(), $id);
	}
	
	public function course_more_details (int $id): array {
		return $this->get_more_details(new CourseCtrl(), $id);
	}
	
	public function topic_more_details (int $id): int {
		$this->get_more_details(new TopicCtrl(), $id);
	}
	
	public function question_more_details (int $id): array {
		return $this->get_more_details(new QuestionCtrl(), $id);
	}
	
	public function test_center_more_details (int $id): int {
		$this->get_more_details(new TestCenterCtrl(), $id);
	}
	
	public function update_options_cookies (array $options): array {
		foreach (['page', 'size', 'sort_asc', 'sort_by', 'search_for', 'search_from'] as $key) {
			if (isset($_COOKIE[ $key ]) && !isset($options[ $key ])) {
				$options[ $key ] = $_COOKIE[ $key ];
			}
		}
		$options['page']     ??= 1;
		$options['page']     = (int)$options['page'];
		$options['size']     ??= 20;
		$options['size']     = (int)$options['size'];
		$options['sort_acs'] ??= true;
		$options['sort_acs'] = (bool)$options['sort_acs'];
		foreach (['page', 'size', 'sort_asc', 'sort_by', 'search_for', 'search_from'] as $key) {
			if (isset($options[ $key ])) {
				//				$_SESSION[ $key ] = $options[ $key ];
				setcookie($key, $options[ $key ], time() + (60 * 60), '/');
			}
		}
		return $options;
	}
	
	public function init_options_cookies (array $options): array {
		
		foreach (['page', 'sort_asc', 'sort_by', 'search_for', 'search_from'] as $key) {
			if (isset($_COOKIE[ $key ])) {
				unset($_COOKIE[ $key ]);
				setcookie($key, null, -1, '/');
			}
		}
		
		return $this->update_options_cookies($options);
	}
	
}