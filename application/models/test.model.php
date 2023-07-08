<?php


class TestModel
	extends BaseModel {
	
	/**
	 * @throws Exception
	 */
	public function add_test (): int {
		return (new QueryBuilder())->insert()
		                           ->into('test')
		                           ->execute();
	}
	
	public function confirm_random_test (array $questions): void {
		try {
			$id = (new QueryBuilder())->insert()
			                          ->into('test')
			                          ->execute();
			foreach ($questions as $question) {
				(new QueryBuilder())->insert(
					QueryBuilder::INSERT_ATTR('test_id', $id),
					QueryBuilder::INSERT_ATTR('question_id', $question['question_id']),
					QueryBuilder::INSERT_ATTR('full_mark', $question['full_mark']),
					QueryBuilder::INSERT_ATTR('question_order', $question['question_order'])
				)
				                    ->into('test_question')
				                    ->execute();
			}
		} catch (Exception) {
		
		}
	}
	
	
	/**
	 * @throws Exception
	 */
	public function get_course_id (int $test_id) {
		return (new QueryBuilder())->select('material.id')
		                           ->from('material', 'topic', 'question', 'test_question', 'test')
		                           ->only_distinct(true)
		                           ->where(QueryBuilder::EQUAL('test.id', $test_id))
		                           ->execute()
		                           ->fetch_one()[0];
	}
	
	/**
	 * @throws Exception
	 */
	public function set_test (int    $test_id,
	                          int    $test_center_id,
	                          string $start_time,
	                          string $duration,
	                          string $course_title
	): int {
		return (new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('test_id', $test_id),
			QueryBuilder::INSERT_ATTR('test_center_id', $test_center_id),
			QueryBuilder::INSERT_ATTR('start_time', $start_time),
			QueryBuilder::INSERT_ATTR('duration_min', $duration),
			QueryBuilder::INSERT_ATTR('material_title', $course_title)
		)
		                           ->into('test_description')
		                           ->execute();
	}
	
	/**
	 * @throws Exception
	 */
	public function check_filled_test (int $test_id): bool {
		return !(new QueryBuilder())->select()
		                            ->from('test_question')
		                            ->where(
			                            QueryBuilder::AND(
				                            QueryBuilder::EQUAL('test_id', $test_id),
				                            QueryBuilder::OR(
					                            QueryBuilder::NULL('full_mark'),
					                            QueryBuilder::EQUAL('full_mark', 0)
				                            )
			                            )
		                            )
		                            ->execute()
		                            ->has_result();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_solved_test (int $user_id, int $test_description_id): ?int {
		return (new QueryBuilder())->select('solved_test.id')
		                           ->from('solved_test')
		                           ->where(
			                           QueryBuilder::AND(
				                           QueryBuilder::EQUAL('user_id', $user_id),
				                           QueryBuilder::EQUAL('test_description_id', $test_description_id),
			                           )
		                           )
		                           ->execute()
		                           ->fetch_one_cell();
	}
	
	/**
	 * @throws Exception
	 */
	public function add_solved_test (int $user_id, int $test_description_id): int {
		return (new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('user_id', $user_id),
			QueryBuilder::INSERT_ATTR('total_mark', 0),
			QueryBuilder::INSERT_ATTR('test_description_id', $test_description_id)
		)
		                           ->into('solved_test')
		                           ->execute();
	}
	
	public function get_test_description (int $user_id, int $test_center_id): array {
		return (new QueryBuilder())->select('test_description.id', 'test_id', 'start_time', 'duration_min')
		                           ->from('test_description', 'student_test_schedule')
		                           ->where(
			                           QueryBuilder::AND(
				                           QueryBuilder::EQUAL('user_id', $user_id),
				                           QueryBuilder::EQUAL('test_center_id', $test_center_id),
				                           QueryBuilder::GREATER(
					                           'DATE_ADD(start_time , INTERVAL duration_min MINUTE)',
					                           new SQL('select now()', QueryType::DUAL)
				                           )
			                           )
		                           )
		                           ->sort_by('start_time')
		                           ->execute()
		                           ->fetch_one_ASSOC();
	}
	
	public function get_test_questions_id (int $test_id): array {
		return (new QueryBuilder())->select('test_question.id')
		                           ->from('test_question', 'test')
		                           ->where(QueryBuilder::EQUAL('test.id', $test_id))
		                           ->execute()
		                           ->fetch_one_column_num();
	}
	
	
	public function create_test_description (int    $test_id,
	                                         int    $test_center_id,
	                                         string $start_time,
	                                         int    $duration
	): int {
		return (new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('test_id', $test_id),
			QueryBuilder::INSERT_ATTR('test_center_id', $test_center_id),
			QueryBuilder::INSERT_ATTR('start_time', $start_time),
			QueryBuilder::INSERT_ATTR('duration_min', $duration)
		)
		                           ->into('test_description')
		                           ->execute();
	}
	
	public function get_full_mark_test (int $test_id): int {
		return (new QueryBuilder())->sum('full_mark')
		                           ->from('test_question', 'test')
		                           ->where(QueryBuilder::EQUAL('test.id', $test_id))
		                           ->execute()
		                           ->fetch_one_cell();
	}
	
}