<?php


class TopicModel
	extends BaseModel {
	public function get_all_topics_column_name (): array {
		return ['topic.id', 'course_title', 'topic_title'];
	}
	
	public function get_all_topics (array $options): array {
		return $this->get_all(['course', 'topic'], $this->get_all_topics_column_name(), $options);
	}
	
	/**
	 * @throws Exception
	 */
	public function add_topic (string $title, int $course_id): int {
		return (new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('course_id', $course_id),
			QueryBuilder::INSERT_ATTR('topic_title', $title)
		)
		                           ->into('topic')
		                           ->execute();
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_topic (int $id): void {
		$this->delete_by_id('topic', $id);
	}
	
	/**
	 * @throws Exception
	 */
	public function get_topics_of_course (int $course_id): array {
		return (new QueryBuilder())->select('topic.id')
		                           ->from('topic', 'course')
		                           ->where(QueryBuilder::EQUAL('course_id', $course_id))
		                           ->random()
		                           ->execute()
		                           ->fetch_all_num();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_random_question (int $topic_id): ?int {
		try {
			$id = (new QueryBuilder())->select('question.id')
			                          ->from('question')
			                          ->where(QueryBuilder::EQUAL('topic_id', $topic_id))
			                          ->random()
			                          ->execute()
			                          ->fetch_one();
			return isset($id) ? $id[0] : null;
		} catch (Exception) {
			return null;
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function get_all_topics_count (array $options): int {
		return $this->get_count('topic',$this->get_all_topics_column_name(), $options);
	}
	
	
}