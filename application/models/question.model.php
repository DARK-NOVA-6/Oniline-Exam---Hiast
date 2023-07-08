<?php

use JetBrains\PhpStorm\ArrayShape;

class QuestionModel
	extends BaseModel {
	public function get_all_questions_column_name (): array {
		return ['question.id', 'course_title', 'topic_title', 'question_title', 'default_mark'];
	}
	
	public function get_all_questions (array $options): array {
		return $this->get_all(['course', 'topic', 'question'], $this->get_all_questions_column_name(), $options);
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_question (int $id): void {
		(new QueryBuilder())->delete()
		                    ->from('attachment')
		                    ->where(
			                    QueryBuilder::MULTI_OPT(
				                    'id',
				                    (new QueryBuilder())->select('attachment.id')
				                                        ->from('attachment', 'question', 'option')
				                                        ->where('question.id', $id)
				                                        ->get_statement()
			                    )
		                    )
		                    ->execute();
		
		(new QueryBuilder())->delete()
		                    ->from('option')
		                    ->where(QueryBuilder::EQUAL('question_id', $id));
		
		$this->delete_by_id('question', $id);
	}
	
	public function add_question (int $topic_id, string $title, string $text, int $mark, array $options) {
		try {
			$id = (new QueryBuilder())->insert(
				QueryBuilder::INSERT_ATTR('topic_id', $topic_id),
				QueryBuilder::INSERT_ATTR('question_title', $title),
				QueryBuilder::INSERT_ATTR('question_text', $text),
				QueryBuilder::INSERT_ATTR('default_mark', $mark)
			)
			                          ->into('question')
			                          ->execute();
			foreach ($options as $option) {
				$this->add_option($id, $option['text'], $option['is_correct'], $option['order']);
			}
		} catch (Exception $e) {
		}
	}
	
	/**
	 * @throws Exception
	 */
	private function add_option (int $question_id, string $text, bool $is_correct, int $order): void {
		(new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('question_id', $question_id),
			QueryBuilder::INSERT_ATTR('option_text', $text),
			QueryBuilder::INSERT_ATTR('is_correct', $is_correct ? 'Y' : 'N'),
			QueryBuilder::INSERT_ATTR('option_order', $order)
		)
		                    ->into('option')
		                    ->execute();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_questions_by_id (int...$ids): array {
		$questions = [];
		foreach ($ids as $id) {
			$questions[] = $this->get_full_question_by_id($id);
		}
		return $questions;
	}
	
	/**
	 * @throws Exception
	 */
	public function get_solved_questions_by_id (int...$ids): array {
		$questions = [];
		foreach ($ids as $id) {
			$questions[] = $this->get_full_solved_question_by_id($id);
		}
		return $questions;
	}
	
	
	public function get_full_question_by_id (int $question_id): array {
		return array_merge(
			(new QueryBuilder())->select(
				'question.id',
				'topic_title',
				'question_title',
				'question_text',
				'default_mark',
				'course_title'
			)
			                    ->from('question', 'topic', 'course')
			                    ->where(QueryBuilder::EQUAL('question.id', $question_id))
			                    ->execute()
			                    ->fetch_one_ASSOC(),
			['options' => $this->get_options_question($question_id)]
		);
	}
	
	public function get_full_solved_question_by_id (int $solved_question_id): array {
		return array_merge(
			(new QueryBuilder())->select(
				'solved_question.id',
				'question_title',
				'question_text',
				'question_order',
				'full_mark',
				'selected_options',
				'subtotal_mark'
			)
			                    ->from('question', 'test_question', 'solved_question')
			                    ->where(QueryBuilder::EQUAL('solved_question.id', $solved_question_id))
			                    ->execute()
			                    ->fetch_one_ASSOC(),
			['options' => $this->get_options_solved_question($solved_question_id)]
		);
	}
	
	public function get_options_question (int $question_id): array {
		return (new QueryBuilder())->select('option_text', 'is_correct', 'option_order')
		                           ->from('option', 'question')
		                           ->where(QueryBuilder::EQUAL('question.id', $question_id))
		                           ->execute()
		                           ->fetch_all_ASSOC();
	}
	
	public function get_options_solved_question (int $solved_question_id): array {
		return $this->get_options_question(
			(new QueryBuilder())->select('question.id')
			                    ->from('question', 'solved_question', 'test_question')
			                    ->where(QueryBuilder::EQUAL('solved_question.id', $solved_question_id))
			                    ->execute()
			                    ->fetch_one_cell()
		);
	}
	
	/**
	 * @throws Exception
	 */
	public function get_test_question_mark (int $test_question_id): int {
		return (new QueryBuilder())->select('full_mark')
		                           ->from('question_test')
		                           ->where(QueryBuilder::EQUAL('id', $test_question_id))
		                           ->execute()
		                           ->fetch_one_cell();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_all_questions_count (array $options): int {
		return $this->get_count('question', $this->get_all_questions_column_name(), $options);
	}
	
	public function get_solved_question (int $test_question_id, int $solved_test_id): ?int {
		return (new QueryBuilder())->select('id')
		                           ->from('solved_question')
		                           ->where(
			                           QueryBuilder::AND(
				                           QueryBuilder::EQUAL('test_question_id', $test_question_id),
				                           QueryBuilder::EQUAL('solved_test_id', $solved_test_id)
			                           )
		                           )
		                           ->execute()
		                           ->fetch_one_cell();
	}
	
	public function add_solved_question (int    $test_question_id,
	                                     int    $solved_test_id,
	                                     string $selected_option,
	                                     int    $subtotal_mark
	): int {
		return (new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('test_question_id', $test_question_id),
			QueryBuilder::INSERT_ATTR('solved_test_id', $solved_test_id),
			QueryBuilder::INSERT_ATTR('selected_options', $selected_option),
			QueryBuilder::INSERT_ATTR('subtotal_mark', $subtotal_mark),
		)
		                           ->into('solved_question')
		                           ->execute();
	}
	
	public function get_solved_question_by_id (int $solved_question_id): array {
		return (new QueryBuilder())->select('test_question_id', 'solved_test_id')
		                           ->from('solved_question')
		                           ->where(QueryBuilder::EQUAL('id', $solved_question_id))
		                           ->execute()
		                           ->fetch_one_ASSOC();
	}
	
	public function update_solved_question (int $solved_question_id, string $selected_options, int $earned_mark) {
		(new QueryBuilder())->update(
			QueryBuilder::UPDATE_ATTR('selected_options', $selected_options),
			QueryBuilder::UPDATE_ATTR('subtotal_mark', $earned_mark)
		)
		                    ->from('solved_question')
		                    ->where(QueryBuilder::EQUAL('id', $solved_question_id))
		                    ->execute();
	}
	
	public function update_solved_test (int $solved_test_id) {
		(new QueryBuilder())->update(
			QueryBuilder::UPDATE_ATTR(
				'total_mark',
				(new QueryBuilder())->sum('subtotal_mark')
				                    ->from('solved_question', 'solved_test')
				                    ->where(QueryBuilder::EQUAL('solved_test.id', $solved_test_id))
				                    ->sub_query()
				                    ->get_statement()
			)
		)
		                    ->from('solved_test')
		                    ->where(QueryBuilder::EQUAL('solved_test.id', $solved_test_id))
		                    ->execute();
	}
	
	public function get_test_question_options_state (int $test_question_id): array {
		return [
			'correct'   => $this->get_test_question_options_order($test_question_id, 'Y'),
			'incorrect' => $this->get_test_question_options_order($test_question_id, 'N'),
		];
	}
	
	/**
	 * @throws Exception
	 */
	private function get_test_question_options_order (int $test_question_id, string $state): array {
		return (new QueryBuilder())->select('option_order')
		                           ->from('option', 'question', 'test_question')
		                           ->where(
			                           QueryBuilder::AND(
				                           QueryBuilder::EQUAL('test_question.id', $test_question_id),
				                           QueryBuilder::EQUAL('is_correct', $state)
			                           )
		                           )
		                           ->execute()
		                           ->fetch_all_num();
	}
	
	public function update_more_details (int     $id,
	                                     ?string $question_title,
	                                     ?string $question_text,
	                                     ?int    $default_mark,
	                                     array   $options
	) {
		$update_attr = [];
		if ($question_title !== null) {
			$update_attr[] = QueryBuilder::UPDATE_ATTR('question_title', $question_title);
		}
		if ($question_text !== null) {
			$update_attr[] = QueryBuilder::UPDATE_ATTR('question_text', $question_text);
		}
		if ($default_mark !== null) {
			$update_attr[] = QueryBuilder::UPDATE_ATTR('default_mark', $default_mark);
		}
		if (count($update_attr)) {
			(new QueryBuilder())->update(...$update_attr)
			                    ->from('question')
			                    ->where(QueryBuilder::EQUAL('id', $id))
			                    ->execute();
		}
		foreach ($options as $option) {
			$update_attr   = [];
			$update_attr[] = QueryBuilder::UPDATE_ATTR('option_order', $option ['option_order']);
			$update_attr[] = QueryBuilder::UPDATE_ATTR('option_text', $option ['option_text']);
			$update_attr[] = QueryBuilder::UPDATE_ATTR('is_correct', $option ['is_correct'] ? 'Y' : 'N');
			
			(new QueryBuilder())->update(...$update_attr)
			                    ->from('option')
			                    ->where(
				                    QueryBuilder::AND(
					                    QueryBuilder::EQUAL('option_order', $option['option_order']),
					                    QueryBuilder::EQUAL('question_id', $id)
				                    )
			                    )
			                    ->execute();
		}
		$at_least_one = (new QueryBuilder())->select('question.id')
		                                    ->from('option', 'question')
		                                    ->where(QueryBuilder::EQUAL('is_correct', 'Y'))
		                                    ->execute()
		                                    ->has_result();
		if (!$at_least_one) {
			throw new Exception();
		}
	}
	
	
}