<?php


class TestCenterModel
	extends BaseModel {
	public function get_all_test_centers_column_name (): array {
		return ['test_center.id', 'test_center_location', 'capacity'];
	}
	
	public function get_all_test_centers (array $options): array {
		return $this->get_all(['test_center'], $this->get_all_test_centers_column_name(), $options);
	}
	
	/**
	 * @throws Exception
	 */
	public function add_test_center (string $location, int $capacity) {
		(new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('test_center_location', $location),
			QueryBuilder::INSERT_ATTR('capacity', $capacity)
		)
		                    ->into('test_center')
		                    ->execute();
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_test_center (int $id): void {
		$this->delete_by_id('test_center', $id);
	}
	
	/**
	 * @throws Exception
	 */
	public function check_availability (string $start_time, string $duration, int $test_center_id): bool {
		return !(new QueryBuilder())->select()
		                            ->from('test_description', 'test_center')
		                            ->where(
			                            QueryBuilder::AND(
				                            QueryBuilder::EQUAL('test_center_id', $test_center_id),
				                            QueryBuilder::OR(
					                            QueryBuilder::INTERSECT_TIME(
						                            $start_time,
						                            $duration,
						                            'start_time',
						                            'duration_min'
					                            ),
					                            QueryBuilder::EQUAL('capacity', 0)
				                            )
			                            )
		                            )
		                            ->execute()
		                            ->has_result();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_capacity (int $test_center_id): int {
		return (new QueryBuilder())->select('capacity')
		                           ->from('test_center')
		                           ->where(QueryBuilder::EQUAL('id', $test_center_id))
		                           ->execute()
		                           ->fetch_one_cell();
	}
	
	public function get_all_test_centers_count (array $options): int {
		return $this->get_count('test_center', $this->get_all_test_centers_column_name(), $options);
	}
	
	
}