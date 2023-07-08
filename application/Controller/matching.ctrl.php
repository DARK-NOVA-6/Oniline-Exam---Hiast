<?php

use JetBrains\PhpStorm\Pure;

class MatchingCtrl
	extends Controller {
	
	private MatchingModel $model;
	
	#[Pure]
	public function __construct () {
		$this->model = new MatchingModel();
		
	}
	
	public function assign_student_to_test (int $test_description_id): int {
		return $this->model->assign_student_to_test_greedily($test_description_id);
	}
	
	
	public function check_add_dependency_possibility (int $course_id, int...$dependency_ids): bool {
		return true;
	}
	
}