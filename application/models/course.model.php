<?php

class CourseModel
	extends BaseModel {
	
	public function get_all_courses_column_name (): array {
		return ['course.id', 'course_title', 'course_full_mark'];
	}
	
	public function get_all_courses (array $options): array {
		return $this->get_all(['course'], $this->get_all_courses_column_name(), $options);
	}
	
	/**
	 * @throws Exception
	 */
	public function add_course (string $title, int $full_mark, $num_tests): int {
		return (new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('course_title', $title),
			QueryBuilder::INSERT_ATTR('course_full_mark', $full_mark),
			QueryBuilder::INSERT_ATTR('num_tests', $num_tests),
			QueryBuilder::INSERT_ATTR(
				'year_season_id',
				(new QueryBuilder())->select('id')
				                    ->from('year_season')
				                    ->sub_query()
				                    ->limit(1, 1)
				                    ->sort_by('date_bound')
				                    ->sort_asc(false)
				                    ->get_statement(),
			)
		)
		                           ->into('course')
		                           ->execute();
	}
	
	/**
	 * @throws Exception
	 */
	public function add_dependency_to_course (int $id, int ...$dependency_id): void {
		foreach ($dependency_id as $depends_on_id) {
			(new QueryBuilder())->insert(
				QueryBuilder::INSERT_ATTR('course_id', $id),
				QueryBuilder::INSERT_ATTR('course_id1', $depends_on_id)
			)
			                    ->into('course_dependency')
			                    ->execute();
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_course (int $id): void {
		$this->delete_by_id('course', $id);
	}
	
	/**
	 * @throws Exception
	 */
	public function get_student_course (int $id): array {
		return (new QueryBuilder())->select('course.id', 'course_title')
		                           ->from('course', 'student_course')
		                           ->where(QueryBuilder::EQUAL('user_id', $id))
		                           ->execute()
		                           ->fetch_all_num();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_random_questions (int $course_id, int $number_question, int...$forbidden): array {
		return (new QueryBuilder())->select('question.id')
		                           ->from('question', 'topic')
		                           ->where(
			                           QueryBuilder::AND(
				                           QueryBuilder::EQUAL('course_id', $course_id),
				                           QueryBuilder::NOT_MULTI_OPT('question.id', ...$forbidden)
			                           )
		                           )
		                           ->random()
		                           ->limit(1, $number_question)
		                           ->execute()
		                           ->fetch_one_column_num();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_random_topics (int $course_id, int $number_question): array {
		return (new QueryBuilder())->select('id')
		                           ->from('topic')
		                           ->where(QueryBuilder::EQUAL('course_id', $course_id))
		                           ->random()
		                           ->limit(1, $number_question)
		                           ->execute()
		                           ->fetch_all_num();
	}
	
	
	/**
	 * @throws Exception
	 */
	public function get_all_courses_count (array $options): int {
		$options['search_from'] ??= $this->get_all_courses_column_name();
		return $this->get_count('course', $this->get_all_courses_column_name(), $options);
	}
	
	public function check_student_registration_possibility (int $user_id, int $course_id): bool {
		return (new QueryBuilder())->select()
		                           ->from('course#T')
		                           ->where(
			                           QueryBuilder::AND(
				                           QueryBuilder::EQUAL('T.id', $course_id),
				                           QueryBuilder::MULTI_OPT(
					                           'T.course_title',
					                           $this->get_available_courses_query($user_id)
					                                ->sub_query()
					                                ->get_statement()
				                           )
			                           )
		                           )
		                           ->execute()
		                           ->has_result();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_available_courses_student (int $user_id): array {
		return $this->get_available_courses_query($user_id)
		            ->select('c.course_title', 'c.id')
		            ->execute()
		            ->fetch_all_num();
	}
	
	
	private function get_available_courses_query (int $user_id): QueryBuilder {
		return (new QueryBuilder())->select('c.course_title')
		                           ->from('course#c')
		                           ->where(
			                           QueryBuilder::AND(
				                           QueryBuilder::SUPERSET_QUERY(
					                           (new QueryBuilder())->select('course_title')
					                                               ->from('course', 'student_course')
					                                               ->where(
						                                               QueryBuilder::AND(
							                                               QueryBuilder::EQUAL('user_id', $user_id),
						                                               //  QueryBuilder::EQUAL('state', 'S'),
						                                               )
					                                               ),
					                           $this->get_dependency_query('c.course_title', true)
				                           ),
				                           QueryBuilder::NOT_MULTI_OPT(
					                           'c.course_title',
					                           (new QueryBuilder())->select('course_title')
					                                               ->from('course', 'student_course')
					                                               ->where(
						                                               QueryBuilder::AND(
							                                               QueryBuilder::EQUAL('user_id', $user_id),
							                                               QueryBuilder::MULTI_OPT('state', 'R', 'S',)
						                                               )
					                                               )
					                                               ->sub_query()
					                                               ->get_statement()
				                           )
			                           )
		                           );
	}
	
	
	public function get_dependency (string $course_title): array {
		return $this->get_dependency_query($course_title)
		            ->execute()
		            ->fetch_all_num();
	}
	
	private function get_dependency_query (string $course_title, bool $sub_query = false): QueryBuilder {
		return (new QueryBuilder())->select('course_title')
		                           ->from('course')
		                           ->where(
			                           QueryBuilder::MULTI_OPT(
				                           'course.id',
				                           (new QueryBuilder())->select('course_id1')
				                                               ->from('course', 'course_dependency')
				                                               ->where(
					                                               QueryBuilder::EQUAL(
						                                               'course_title',
						                                               $course_title,
						                                               true,
						                                               $sub_query
					                                               )
				                                               )
				                                               ->sub_query()
				                                               ->get_statement()
			                           )
		                           )
		                           ->only_distinct(true);
	}
	
	public function get_more_details (int $course_id): array {
		return array_merge(
			(new QueryBuilder())->select('course_title', 'course_full_mark')
			                    ->from('course')
			                    ->where(QueryBuilder::EQUAL('course.id', $course_id))
			                    ->execute()
			                    ->fetch_one_ASSOC(),
			[
				'topics' => (new QueryBuilder())->select('topic.id', 'topic_title')
				                                ->from('topic', 'course')
				                                ->where(QueryBuilder::EQUAL('course_id', $course_id))
				                                ->execute()
				                                ->fetch_all_ASSOC(),
			]
		);
	}
	
}