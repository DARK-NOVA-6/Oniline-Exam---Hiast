<?php

use JetBrains\PhpStorm\ArrayShape;

require_once 'entry_point.php';


function _get_option (string $str): array {
	$options = [];
	foreach (explode('&', $str) as $option) {
		$options [ explode('=', $option)[0] ] = explode('=', $option)[1];
	}
	return $options;
}

function _fix_attr_table (array &$table): void {
	$table['from'] = ($table['page'] - 1) * $table['size'] + 1;
	$table['to']   = ($table['page'] - 1) * $table['size'] + count($table['data']);
}


if (isset($_POST['emailAvailability'])) {
	try {
		echo json_encode((new UserCtrl())->check_availability_email($_POST['emailAvailability']));
	} catch (Exception $e) {
		echo json_encode(false);
	}
}


$mapping = [
	'Users_table'        => 'user_data',
	'Roles_table'        => 'role_data',
	'Courses_table'      => 'course_data',
	'Questions_table'    => 'question_data',
	'Test Centers_table' => 'test_center_data',
	'Topics_table'       => 'topic_data',
];

foreach ($mapping as $key => $action) {
	if (isset($_POST[ $key ])) {
		$ctrl    = new DashboardCtrl();
		$options = $ctrl->update_options_cookies(_get_option($_POST[ $key ]));
		$table   = $ctrl->$action($options);
		_fix_attr_table($table);
		if ($table['from'] > $table['to'] && $options ['page'] != 1) {
			$options['page'] = 1;
			$table           = $ctrl->$action($ctrl->update_options_cookies($options));
			_fix_attr_table($table);
		}
		if ($table['from'] > $table['to']) {
			$table['from'] = $table['to'] = 0;
		}
		echo json_encode($table);
	}
}


class DataFormatter {
	private array         $mapping_ctrl_fields = [];
	private array         $mapping_fields_ctrl = [];
	private array         $mapping_ctrl_arrays = [];
	private array         $mapping_arrays_ctrl = [];
	private Dashboardable $controller;
	
	public function get_encoded_more_details (int $id): array {
		return $this->encod_data($this->controller->get_more_details($id));
	}
	
	#[ArrayShape(['result' => "bool"])]
	public function delete_row (int $id): array {
		return ['result' => $this->controller->delete_row($id)];
	}
	
	public function update ($data) {
		return $this->controller->update_more_details($data['id'], $data['data']);
	}
	
	public function decode_data (array $data): array {
		$id             = $data['id'];
		$encoded_arrays = $data['data']['arx'];
		$encoded_fields = $data['data']['tx'];
		return ['id'   => $id,
		        'data' => array_merge(
			        $this->decode_array($encoded_arrays),
			        $this->decode_field($encoded_fields)
		        ),
		];
	}
	
	
	public function __construct (Dashboardable $controller) {
		$this->controller = $controller;
	}
	
	//	public function get_data
	
	public function register_field (string $key,
	                                string $name,
	                                string $type = 'NORMAL',
	                                bool   $is_editable = true,
		//	                                       string $ajax_gate = ''
	): DataFormatter {
		$this->mapping_ctrl_fields[ $key ]   = [
			'name'        => $name,
			'type'        => $type,
			'is_editable' => $is_editable,
			//			'ajax_gate'   => $ajax_gate,
		];
		$this->mapping_fields_ctrl [ $name ] = $key;
		return $this;
	}
	
	public function register_array (
		string  $key,
		string  $name,
		string  $val_index,
		string  $id_index,
		?string $selected_index = null,
		bool    $multiple_option = false,
		bool    $is_editable = true,
		bool    $is_addable = true,
		bool    $is_deletable = true,
		//	                                       string $ajax_gate = ''
	): DataFormatter {
		$this->mapping_ctrl_arrays [ $key ]  = [
			'name'           => $name,
			'is_editable'    => $is_editable,
			'is_addable'     => $is_addable,
			'is_deletable'   => $is_deletable,
			'isYesNo'        => $multiple_option,
			'val_index'      => $val_index,
			'id_index'       => $id_index,
			'selected_index' => $selected_index
			//			                        'ajax_gate'    => $ajax_gate,
		];
		$this->mapping_arrays_ctrl [ $name ] = [
			'sub_table_name' => $key,
		];
		return $this;
		
	}
	
	
	public function encod_data (array $data): array {
		$fields = [];
		$arrays = [];
		foreach ($this->mapping_ctrl_arrays as $array_key => $array_val) {
			if (isset($data[ $array_key ]) && $this->is_accessible($array_key)) {
				$arrays[] = $this->encode_array($array_val, $data[ $array_key ]);
			}
		}
		
		foreach ($this->mapping_ctrl_fields as $field_key => $field_val) {
			if (isset($data[ $field_key ]) && $this->is_accessible($field_key)) {
				$fields[] = $this->encode_field($field_val, $data[ $field_key ]);
			}
		}
		return ['tx' => $fields, 'arx' => $arrays];
	}
	
	private function encode_field (array $info, mixed $val): array {
		return array_merge($info, ['value' => $val]);
	}
	
	private function decode_field (array $data): array {
		$param = [];
		foreach ($data as $field) {
			if (isset($field['value']) &&
			    isset($field['name']) &&
			    isset($this->mapping_fields_ctrl[ $field['name'] ])
			) {
				$name           = $field['name'];
				$temp           = $this->mapping_fields_ctrl["$name"];
				$param["$temp"] = $field['value'];
			}
		}
		return $param;
	}
	
	private function encode_array (array $info, array $arr): array {
		$result = [];
		foreach ($arr as $val) {
			$result[] = [
				'value'     => $val[ $info['val_index'] ],
				'id'        => $val[ $info['id_index'] ],
				'isCorrect' => $val[ $info['selected_index'] ] ?? false,
			];
		}
		return array_merge($info, ['arrx' => $result],);
	}
	
	private function decode_array (array $data): array {
		$arrays = [];
		foreach ($data as $sub_table) {
			if (isset($sub_table['name']) && isset($sub_table['options'])) {
				$rows = $sub_table['options'];
				foreach ($rows as $row) {
					if (isset($row['id']) && isset($row['value'])) {
						if (!isset($row['isSelected'])) {
							
							$arrays[ $sub_table['name'] ][] = [
								'id'    => $row['id'],
								'value' => $row['value'],
							];
						} else {
							$arrays[ $sub_table['name'] ][] = [
								'id'         => $row['id'],
								'value'      => $row['value'],
								'is_correct' => $row['isSelected'],
							];
						}
						
					}
				}
			}
		}
		return $arrays;
	}
	
	private function is_accessible (string $key): bool {
		return true;
		//		if (isset($this->controllers[ $controller_name ][ false ][ $key ])) {
		//			return true;
		//		}
		//		if (isset($this->controllers[ $controller_name ][ true ][ $key ])) {
		//			return true;
		//		}
		//		return false;
	}
	
}


$course_formatter   = (new DataFormatter(new CourseCtrl()))
	->register_field(
		'course_title',
		'course title'
	)
	->register_field(
		'course_full_mark',
		'full mark',
		'NORMAL',
		true
	)
	->register_array(
		'topics',
		'relatedtopics',
		'topic_title',
		'topic.id'
	);
$question_formatter = (new DataFormatter(new QuestionCtrl()))
	->register_field(
		'course_title',
		'course'
	)
	->register_field(
		'topic_title',
		'topic'
	)
	->register_field(
		'question_title',
		'question title'
	)
	->register_field(
		'question_text',
		'question text'
	)
	->register_field(
		'default_mark',
		'default mark'
	)
	->register_array(
		'options',
		'options',
		'option_text',
		'option_order',
		'',
		true,
		true,
		true
	);

$mapping = [
	'Users_row_query'        => [
		'more_details' => 'user_more_details',
		'delete'       => '',
	],
	'Roles_row_query'        => [
		'more_details' => 'role_more_details',
		'delete'       => '',
	],
	'Courses_row_query'      => $course_formatter,
	'Questions_row_query'    => $question_formatter,
	'Test_centers_row_query' => [
		'more_details' => 'test_center_more_details',
		'delete'       => '',
	],
	'Topics_row_query'       => [
		'more_details' => 'topic_more_details',
		'delete'       => '',
	],
];

foreach ($mapping as $key => $linker) {
	if (isset($_POST[ $key ])) {
		$flag   = explode('=', $_POST[ $key ])[0];
		$row_id = explode('=', $_POST[ $key ])[1];
		if ($flag == 'more_details') {
			echo json_encode($linker->get_encoded_more_details($row_id));
		}
		if ($flag == 'delete') {
//			echo json_encode('result=undefined');
			echo json_encode($linker->delete_row($row_id));
		}
		
	}
}


$mapping = [
	'Users_row_query_update'        => [],
	'Roles_row_query_update'        => [],
	'Courses_row_query_update'      => $course_formatter,
	'Questions_row_query_update'    => $question_formatter,
	'Test_centers_row_query_update' => [],
	'Topics_row_query_update'       => [],
];

foreach ($mapping as $key => $linker) {
	if (isset($_POST[ $key ])) {
		$data = $linker->decode_data($_POST[ $key ]);
		echo json_encode(['result=' => (bool)$linker->update($data)]);
	}
}


