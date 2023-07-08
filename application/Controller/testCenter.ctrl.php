<?php

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

require_once 'application/Controller/dashboardable.int.php';


class TestCenterCtrl
	extends Controller
	implements Dashboardable {
	
	private TestCenterModel $model;
	
	#[Pure]
	public function __construct () {
		$this->model = new TestCenterModel();
		
	}

	public function get_table (array $options): array {
		return [
			'columns' => $this->model->get_all_test_centers_column_name(),
			'data'    => $this->model->get_all_test_centers($options),
			'count'   => $this->model->get_all_test_centers_count($options),
			'page'    => $options['page'] ?? 1,
			'size'    => $options['size'] ?? 20,
		];
	}
	
	#[Pure]
	public function get_table_view (): TestCenterTableView {
		return new TestCenterTableView();
	}
	
	
	/**
	 * @throws Exception
	 */
	public function add_test_center (): void {
		$location = $_POST['location'] ?? null;
		$capacity = $_POST['capacity'] ?? 0;
		$this->model->add_test_center($location, $capacity);
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_test_center (): void {
		$id = $_POST['test_center.id'] ?? null;
		if ($id != null) {
			$this->model->delete_test_center($id);
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function check_availability (string $start_time, string $duration, int $test_center_id): bool {
		return $this->model->check_availability($start_time, $duration, $test_center_id);
	}
	
	/**
	 * @throws Exception
	 */
	public function get_capacity (int $test_center_id): int {
		return $this->model->get_capacity($test_center_id);
	}
	
	public function delete_row (int $id): bool {
		try {
			$this->model->delete_test_center($id);
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
	
	
}