<?php


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;


class CourseCtrl
	extends Controller
	implements Dashboardable {
	
	
	protected CourseModel $model;
	
	#[Pure]
	public function __construct () {
		$this->model = new CourseModel();
	}
	
	public function get_table (array $options): array {
		return [
			'columns' => $this->model->get_all_courses_column_name(),
			'data'    => $this->model->get_all_courses($options),
			'count'   => $this->model->get_all_courses_count($options),
			'page'    => $options['page'] ?? 1,
			'size'    => $options['size'] ?? 20,
		];
	}
	
	#[Pure]
	public function get_table_view (): CoursesTableView {
		return new CoursesTableView();
	}
	
	public function get_more_details (int $course_id): array {
		//		echo '<pre>';
		//		print_r($this->model->get_more_details($course_id));
		//		echo '</pre>';
		return $this->model->get_more_details($course_id);
	}
	
	/**
	 * @throws Exception
	 */
	public function add_course (): void {
		
		$course_title = $_POST['title'] ?? null;
		$full_mark    = $_POST['full_mark'] ?? null;
		$num_tests    = $_POST['num_test'] ?? null;
		
		//		$course_title = 'math4';
		//		$full_mark      = 100;
		//		$num_tests      = 1;
		
		if ($course_title != null && $full_mark != null && $num_tests > 0) {
			$_POST['id'] = $this->model->add_course($course_title, $full_mark, $num_tests);
			$this->add_dependency_to_course();
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function add_dependency_to_course (): void {
		$course_id      = $_POST['course.id'] ?? null;
		$count          = $_POST['count'] ?? null;
		$dependency_ids = [];
		
		//		$count                = 2;
		//		$course_id            = 15;
		//		$_POST['course.id1'] = 14;
		//		$_POST['course.id2'] = 13;
		
		if ($course_id != null && $count !== null) {
			for ($i = 1 ; $i <= $count ; $i++) {
				$id = $_POST["course.id$i"] ?? null;
				if ($id !== null) {
					$dependency_ids [] = $id;
				}
			}
			if (!$this->check_add_dependency_possibility($course_id, ...$dependency_ids)) {
				throw new Exception();
			}
			if (count($dependency_ids) == $count) {
				$this->model->add_dependency_to_course($course_id, ...$dependency_ids);
			}
		}
	}
	
	#[Pure]
	public function check_add_dependency_possibility (int $course_id, int...$dependency_ids): bool {
		return (new MatchingCtrl())->check_add_dependency_possibility($course_id, ...$dependency_ids);
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_course (): void {
		$id = $_POST['course.id'] ?? null;
		if ($id != null) {
			$this->model->delete_course($id);
		}
	}
	
	public function delete_row (int $id): bool {
		try {
			$this->model->delete_course($id);
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
	
	/**
	 * @throws Exception
	 */
	public function get_student_course (): array {
		$student_id = $_POST['user.id'] ?? null;
		$student_id = 2;
		return $this->model->get_student_course($student_id);
	}
	
	/**
	 * @throws Exception
	 */
	public function get_random_questions (int $course_id, int $number_question, int...$forbidden): array {
		//		var_dump($forbidden);
		return $this->model->get_random_questions($course_id, $number_question, ...$forbidden);
	}
	
	/**
	 * @throws Exception
	 */
	public function get_random_question_per_topic (int $course_id, int $number_question): array {
		$random_topics   = $this->model->get_random_topics($course_id, $number_question);
		$random_question = [];
		foreach ($random_topics as $topic_id) {
			$question_id = (new TopicCtrl())->get_random_question($topic_id[0]);
			if ($question_id != null) {
				$random_question[] = $question_id;
			}
		}
		return $random_question;
	}
	
	
	public function check_student_registration_possibility (int $user_id, int $course_id): bool {
		return $this->model->check_student_registration_possibility($user_id, $course_id);
	}
	
	public function get_available_courses_student (): void {
		$user_id = $_POST['user.id'] ?? null;
		$user_id = 2;
		$arr     = $this->model->get_available_courses_student($user_id);
		
		
		echo '<pre>';
		foreach ($this->model->get_all_courses([]) as $course) {
			$s = "";
			foreach ($this->model->get_dependency($course[1]) as $item) {
				$s .= $item[0].",";
			}
			echo "COURSE:: {$course[1]} depends on ($s)<br>";
		}
		echo 'TAKEN : <br>';
		print_r($this->get_student_course());
		print_r($arr);
		echo '</pre>';
		
		
	}
	
	
}