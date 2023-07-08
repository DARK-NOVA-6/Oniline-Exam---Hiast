<?php

class MatchingModel
	extends BaseModel {
	
	public function assign_student_to_test_greedily (int $test_description_id): int {
		$path = ['test_center', 'test_description', 'test', 'test_question', 'question', 'topic', 'course',];
		$data = (new QueryBuilder())->select('capacity', 'start_time', 'duration_min', 'course.id')
		                            ->from(...$path)
		                            ->where(QueryBuilder::EQUAL('test_description.id', $test_description_id))
		                            ->limit(1, 1)
		                            ->execute()
		                            ->fetch_one_ASSOC();
//		echo '<pre>';
//		var_dump($data);
//		echo '</pre>';
		$maximum    = $data['capacity'];
		$start_time = $data['start_time'];
		$duration   = $data['duration_min'];
		$course_id  = $data['course.id'];
		//		echo '<pre>';
		//		var_dump($maximum);
		//		var_dump($start_time);
		//		var_dump($duration);
		//		var_dump($course_id);
		$path     = ['course', 'topic', 'question', 'test_question', 'test', 'test_description',];
		$path[]   = 'student_test_schedule';
		$students = (new QueryBuilder())->select('user_id')
		                                ->from('student_course')
		                                ->where(
			                                QueryBuilder::AND(
				                                QueryBuilder::EQUAL('course_id', $course_id),
				                                QueryBuilder::NOT_MULTI_OPT(
					                                'user_id',
					                                (new QueryBuilder())->select('user_id')
					                                                    ->from(
						                                                    'student_test_schedule',
						                                                    'test_description'
					                                                    )
					                                                    ->where(
						                                                    QueryBuilder::INTERSECT_TIME(
							                                                    $start_time,
							                                                    $duration,
							                                                    'start_time',
							                                                    'duration_min'
						                                                    )
					                                                    )
					                                                    ->sub_query()
					                                                    ->get_statement()
				                                )
			                                )
		                                )
		                                ->limit(1, $maximum)
		                                ->execute()
		                                ->fetch_all_num();
		$count    = 0;
		foreach ($students as $student) {
			try {
				(new QueryBuilder())->insert(
					QueryBuilder::INSERT_ATTR('test_description_id', $test_description_id),
					QueryBuilder::INSERT_ATTR('user_id', $student[0])
				)
				                    ->into('student_test_schedule')
				                    ->execute();
				$count++;
			} catch (Exception) {
			
			}
		}
		return $count;
	}
	
}