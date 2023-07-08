<?php


use JetBrains\PhpStorm\Pure;


class TestCtrl
	extends Controller {
	
	protected TestModel $model;
	
	#[Pure]
	public function __construct () {
		$this->model = new TestModel();
	}
	
	/**
	 * @throws Exception
	 */
	public function create_solved_test_if_not_exist (int $user_id, int $test_description_id): int {
		$id = $this->model->get_solved_test($user_id, $test_description_id);
		if ($id === null) {
			return $this->model->add_solved_test($user_id, $test_description_id);
		} else {
			return $id;
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function generate_random_test (): array {
		$course_id       = $_POST['course.id'] ?? null;
		$number_question = $_POST['count'] ?? null;
		
		$course_id       = 2;
		$number_question = 3;
		
		if ($course_id != null && $number_question != null) {
			$first_random     = (new CourseCtrl())->get_random_question_per_topic($course_id, $number_question);
			$random_questions = array_merge(
				(new CourseCtrl())->get_random_questions(
					   $course_id,
					   $number_question - count($first_random),
					...$first_random
				),
				$first_random
			);
			
			echo '<pre>';
			print_r((new QuestionCtrl())->get_questions_by_id(...$random_questions));
			echo '</pre>';
			$_POST['count'] = $number_question;
			$i              = 1;
			foreach ((new QuestionCtrl())->get_questions_by_id(...$random_questions) as $item) {
				$_POST["question.id$i"]  = $item['question.id'];
				$_POST["default_mark$i"] = $item['default_mark'];
				$i++;
			}
			$this->confirm_random_test();
			return (new QuestionCtrl())->get_questions_by_id(...$random_questions);
		} else {
			return [];
		}
	}
	
	public function confirm_random_test (): void {
		$questions = [];
		$count     = $_POST['count'] ?? null;
		//		$count = 2;
		//		$_POST['question.id$i']= 8;
		//		$_POST['question.id$i']= 8;
		
		if ($count != null) {
			for ($i = 1 ; $i <= $count ; $i++) {
				$question_id    = $_POST["question.id$i"] ?? null;
				$full_mark      = $_POST["default_mark$i"] ?? null;
				$question_order = $_POST["order$i"] ?? $i;
				if ($question_id !== null && $full_mark !== null && $question_order !== null) {
					$questions[] = [
						'question_id'    => $question_id,
						'full_mark'      => $full_mark,
						'question_order' => $question_order,
					];
				}
			}
			if ($count == count($questions)) {
				$this->model->confirm_random_test($questions);
			}
		}
	}
	
	
	/**
	 * @throws Exception
	 */
	public function set_test (): void {
		$test_id        = $_POST['test_id'] ?? null;
		$test_center_id = $_POST['test_center_id'] ?? null;
		$start_time     = $_POST['start_time'] ?? null;
		$duration       = $_POST['duration'] ?? null;
		
		
		$test_id        = 2;
		$test_center_id = 1;
		$start_time     = "2022-04-12 11-15-00";
		$duration       = 5;
		
		
		if ($test_id != null && $test_center_id != null && $start_time != null && $duration != null) {
			if (!(new TestCenterCtrl())->check_availability($start_time, $duration, $test_center_id)) {
				//				throw new Exception();
			}
			if (!$this->check_test_ready($test_id)) {
				throw new Exception();
			}
			//						$id = $this->model->create_test_description($test_id, $test_center_id, $start_time, $duration);
			$id = 5;
			//			echo $id;
			(new MatchingCtrl())->assign_student_to_test($id);
		}
	}
	
	
	/**
	 * @throws Exception
	 */
	private function check_test_ready (int $test_id): bool {
		return $this->model->check_filled_test($test_id);
	}
	
	/**
	 * @throws Exception
	 */
	public function index () {
		$user_id             = $_SESSION['user_id'] ?? null;
		$test_center_id      = $_SESSION['test_center_id'] ?? null;
		$user_id             = 2;
		$test_center_id      = 1;
		$description         = $this->model->get_test_description($user_id, $test_center_id);
		$test_description_id = $description['test_description.id'];
		$duration            = $description['duration_min'];
		$startD              = $description['start_time'];
		$solved_test_id      = $this->create_solved_test_if_not_exist($user_id, $test_description_id);
		$test_questions      = $this->model->get_test_questions_id($description['test_id']);
		$questions           = $this->get_test_questions_item($solved_test_id, ...$test_questions);
		$full_mark           = $this->model->get_full_mark_test($description['test_id']);
		//		echo '<pre>';
		//		print_r($questions);
		//		echo '</pre>';
		//		die();
		(new TestView())->render($startD, $duration, [], $full_mark, ...$questions);
	}
	
	
	private function get_test_questions_item (int $solved_test_id, int ...$test_questions_id): array {
		$questions = [];
		foreach ($test_questions_id as $test_question_id) {
			$questions[] = $this->get_test_question_item($solved_test_id, $test_question_id);
		}
		return $questions;
	}
	
	private function get_test_question_item (int $solved_test_id, int $test_question_id): QuestionItem {
		$solved_question_id = (new QuestionCtrl())->create_solved_question_if_not_exist(
			$test_question_id,
			$solved_test_id
		);
		$solved_question    = (new QuestionCtrl())->get_solved_question_by_id($solved_question_id);
		$options            = [];
		foreach ($solved_question['options'] as $option) {
			$options[] = new OptionItem(
				$option['option_text'],
				$option['option_order'],
				str_contains($solved_question['selected_options'], $option['option_order'])
			);
		}
		return new QuestionItem($solved_question['question_title'], $solved_question['question_text'], ...$options);
	}
	
	
	
	
}