<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;


class QuestionCtrl
	extends Controller
	implements Dashboardable {
	
	private QuestionModel $model;
	
	#[Pure]
	public function __construct () {
		$this->model = new QuestionModel();
		
	}
	
	public function get_table (array $options): array {
		return [
			'columns' => $this->model->get_all_questions_column_name(),
			'data'    => $this->model->get_all_questions($options),
			'count'   => $this->model->get_all_questions_count($options),
			'page'    => $options['page'] ?? 1,
			'size'    => $options['size'] ?? 20,
		];
	}
	
	#[Pure]
	public function get_table_view (): QuestionsTableView {
		return new QuestionsTableView();
	}
	
	
	/**
	 * @throws Exception
	 */
	public function get_questions_by_id (int...$ids): array {
		return $this->model->get_questions_by_id(...$ids);
	}
	
	public function get_solved_question_by_id (int $ids): array {
		return $this->model->get_full_solved_question_by_id($ids);
	}
	
	
	public function create_solved_question_if_not_exist (int $test_question_id, int $solved_test_id): int {
		$id = $this->model->get_solved_question($test_question_id, $solved_test_id);
		if ($id === null) {
			return $this->model->add_solved_question($test_question_id, $solved_test_id, '', 0);
		} else {
			return $id;
		}
	}
	
	public function add_question (): void {
		$topic_id = $_POST['topic.id'] ?? null;
		$title    = $_POST['title'] ?? null;
		$text     = $_POST['text'] ?? null;
		$mark     = $_POST['mark'] ?? null;
		$options  = [];
		
		//		$topic_id = 2;
		//		$title    = 'title of 2 question';
		//		$text     = 'text  of 2 question';
		//		$mark     = 10;
		
		
		$number_correct = 0;
		foreach ([1, 2, 3, 4, 5] as $i) {
			$opt_text       = $_POST["opt_text$i"] ?? null;
			$opt_is_correct = $_POST["opt_is_correct$i"] ?? null;
			$opt_order      = $_POST["opt_order$i"] ?? null;
			
			//			$opt_text       = 'opt text 2 question'.$i;
			//			$opt_is_correct = $i == 2;
			//			$opt_order      = $i;
			
			if ($opt_text != null && $opt_order != null && $opt_is_correct !== null) {
				$options[] = ['text'       => $opt_text,
				              'is_correct' => $opt_is_correct,
				              'order'      => $opt_order];
				if ($opt_is_correct === true) {
					$number_correct++;
				}
			}
		}
		if ($topic_id != null && $title != null && $text != null && count($options) > 2 && $number_correct > 0) {
			$this->model->add_question($topic_id, $title, $text, $mark, $options);
			
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_question (): void {
		$id = $_POST['question.id'] ?? null;
		if ($id != null) {
			$this->model->delete_question($id);
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function select_solved_question_choices (int $solved_question_id, string $selected_options): void {
		$test_question = $this->model->get_solved_question_by_id($solved_question_id);
		$earned_mark   = $this->validate_question($test_question['test_question_id'], $selected_options);
		$this->model->update_solved_question($solved_question_id, $selected_options, $earned_mark);
		$this->model->update_solved_test($test_question['solved_test_id']);
	}
	
	private function validate_question (int $test_question_id, string $selected_options): int {
		$options        = $this->model->get_test_question_options_state($test_question_id);
		$full_mark      = $this->model->get_test_question_mark($test_question_id);
		$correct_choice = 0;
		for ($i = 0 ; $i < strlen($selected_options) ; $i++) {
			if (str_contains($options['correct'], $selected_options[ $i ])) {
				$correct_choice++;
			} else if (!str_contains($options['incorrect'], $selected_options[ $i ])) {
				$correct_choice++;
			}
		}
		return ceil($correct_choice * $full_mark / (count($options['correct']) + count($options['incorrect'])));
	}
	
	public function get_more_details (int $id): array {
		return $this->model->get_full_question_by_id($id);
	}
	
	public function update_more_details (int $id, array $data): bool {
		$question_title = $data['question_title'] ?? null;
		$question_text  = $data['question_text'] ?? null;
		$default_mark   = $data['default_mark'] ?? null;
		$temp_options   = $data['options'] ?? [];
		$options        = [];
		foreach ($temp_options as $temp_option) {
			$option_order = $temp_option['option_order'] ?? null;
			$is_correct   = $temp_option['is_correct'] ?? null;
			$option_text  = $temp_option ['option_text'] ?? null;
			if ($option_order !== null) {
				$options[] = [
					'option_order' => $option_order,
					'is_correct'   => $is_correct,
					'option_text'  => $option_text,
				];
			}
		}
		try {
			$this->model->update_more_details($id, $question_title, $question_text, $default_mark, $options);
		} catch (Exception) {
			return false;
		}
		return true;
		
	}
	
	public function delete_row (int $id): bool {
		try {
			$this->model->delete_question($id);
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
	
	
}