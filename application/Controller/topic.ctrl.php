<?php


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

require_once 'application/Controller/dashboardable.int.php';


class TopicCtrl
	extends Controller
	implements Dashboardable {
	
	private TopicModel $model;
	
	#[Pure]
	public function __construct () {
		$this->model = new TopicModel();
		
	}
	
	public function get_table (array $options): array {
		return [
			'columns' => $this->model->get_all_topics_column_name(),
			'data'    => $this->model->get_all_topics($options),
			'count'   => $this->model->get_all_topics_count($options),
			'page'    => $options['page'] ?? 1,
			'size'    => $options['size'] ?? 20,
		];
	}
	
	/**
	 * @throws Exception
	 */
	#[Pure]
	public function get_table_view (): TopicsTableView {
		return new TopicsTableView();
	}
	
	
	/**
	 * @throws Exception
	 */
	public function add_topic (): void {
		$title     = $_POST['title'] ?? null;
		$course_id = $_POST['course_id'] ?? null;
		if ($this != null && $course_id != null) {
			$this->model->add_topic($title, $course_id);
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_topic (): void {
		$topic_id = $_POST['topic.id'] ?? null;
		if ($topic_id != null) {
			$this->model->delete_topic($topic_id);
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function get_topics_of_course ($course_id): array {
		return $this->model->get_topics_of_course($course_id);
	}
	
	/**
	 * @throws Exception
	 */
	public function get_random_question (int $topic_id): ?int {
		return $this->model->get_random_question($topic_id);
	}
	
	public function delete_row (int $id): bool {
		try {
			$this->model->delete_topic($id);
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
	
	
}