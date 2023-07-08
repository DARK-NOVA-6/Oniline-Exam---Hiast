<?php


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;


class UserCtrl
	extends Controller
	implements Dashboardable {
	
	/**
	 * @var mixed
	 */
	private UserModel $userModel;
	private UserModel $model;
	
	public function __construct () {
		$this->model = new UserModel();
	}
	
	#[ArrayShape(['columns' => "string[]",
	              'data'    => "array",
	              'count'   => "int",
	              'page'    => "int|mixed",
	              'size'    => "int|mixed"])]
	public function get_table (array $options): array {
		return [
			'columns' => $this->model->get_all_users_column_name(),
			'data'    => $this->model->get_all_users($options),
			'count'   => $this->model->get_all_users_count($options),
			'page'    => $options['page'] ?? 1,
			'size'    => $options['size'] ?? 20,
		];
	}
	
	#[Pure]
	public function get_table_view (): UsersTableView {
		return new UsersTableView();
	}
	
	public function get_more_details (int $id): array {
		//		return $this->model->get_more_details($id);
		return [];
	}
	
	
	public function login () {
		(new LoginView())->render();
	}
	
	public function logout () {
		unset($_SESSION);
		session_destroy();
		header("Location: ".URL."user/login");
	}
	
	public function signup () {
		(new SignupView())->render();
	}
	
	/**
	 * @throws Exception
	 */
	public function login_process () {
		$email = $_POST['email'] ?? '';
		$email = rtrim($email, '@hiast.edu.sy');
		$email .= '@hiast.edu.sy';
		$pass  = $_POST['password'] ?? '';
		$user  = $this->model->get_user($email, $pass);
		if ($user === null) {
			$error = 'no matching for email and password';
		} else {
			$_SESSION['user_id']    = $user['user.id'];
			$_SESSION['user_name']  = $user['first_name'].' '.$user['last_name'];
			$_SESSION['role_title'] = $user['role_title'];
		}
	}
	
	/**
	 * @throws Exception
	 */
	private function validate_registration_input (
		string &$first_name,
		string &$last_name,
		string &$email,
		string &$phone,
		string $pass,
		string $conf_pass,
		string &$test_center
	): array {
		$first_name  = trim($first_name);
		$last_name   = trim($last_name);
		$email       = trim($email);
		$email       = basename($email, '@hiast.edu.sy');
		$email       .= '@hiast.edu.sy';
		$test_center = trim($test_center);
		$phone       = trim($phone);
		$validator   = Application::get_validator();
		$error       = [];
		if ($this->validate($first_name, ...$validator['name']) !== null) {
			$error['fname'] = $this->validate($first_name, ... $validator['name']);
		}
		if ($this->validate($last_name, ... $validator['name']) !== null) {
			$error['lname'] = $this->validate($last_name, ...$validator['name']);
		}
		if ($this->validate($email, ...$validator['email']) !== null) {
			$error['email'] = $this->validate($email, ... $validator['email']);
		}
		if ($this->validate($pass, ...$validator['password']) !== null) {
			$error['password'] = $this->validate($pass, ... $validator['password']);
		}
		if ($pass != $conf_pass) {
			$error['confPassword'] = 'password and confirmation do not match';
		}
		if ($test_center != '' && !(new TestCtrl())->test_center_exists($test_center)) {
			$error['test_center'] = 'Test Center not exist';
		}
		if ($phone != '' && $this->validate($phone, ...$validator['phone']) !== null) {
			$error['phone'] = $this->validate($phone, ...$validator['phone']);
		}
		return $error;
	}
	
	/**
	 * @throws Exception
	 */
	public function check_availability_email ($email): bool {
		return $this->model->check_availability_email($email);
	}
	
	/**
	 * @throws Exception
	 */
	public function add_user () {
		$role_title = $_POST['roleTitle'] ?? 'TEST';
		
		if ($role_title === null) {
			
			return;
		}
		if ($role_title != 'TC_ADMIN') {
			$_POST['testCenter'] = '';
		}
		$first_name  = $_POST['fname'] ?? '';
		$last_name   = $_POST['lname'] ?? '';
		$email       = $_POST['email'] ?? '';
		$phone       = $_POST['phone'] ?? '';
		$pass        = $_POST['password'] ?? '';
		$test_center = $_POST['testCenter'] ?? '';
		//		$first_name            = "ahmad";
		//		$last_name             = "temp";
		//		$email                 = "test.$first_name$t";
		//		$pass                  = "dsajdk$@#212.saZ";
		//		$_POST['confPassword'] = "dsajdk$@#212.saZ";
		
		$error = $this->validate_registration_input(
			$first_name,
			$last_name,
			$email,
			$phone,
			$pass,
			$_POST['confPassword'] ?? '',
			$test_center,
		);
		
		echo $email;
		
		if (count($error) == 0) {
			try {
				$id = $this->model->add_user($first_name, $last_name, $email, $pass, $role_title, $test_center);
				//				$path = STORAGE."\\user\\$id\\".$_FILES['photo']['name'];
				//				move_uploaded_file($_FILES['photo']['tmp_name'], $path);
				//				if ($t == 25) {
				//					return;
				//				} else {
				//					$this->add_user($t + 1);
				//				}
			} catch (Exception) {
				$error['email'] = 'Email is not available';
			}
		}
		if (count($error) == 0) {
		} else {
			echo '<pre>';
			print_r($error);
			echo '</pre>';
			//			header("Location: ".URL."user/signup");
		}
	}
	
	public function register_course (): void {
		$user_id   = $_POST['user.id'] ?? null;
		$course_id = $_POST['course.id'] ?? null;
		//		$user_id   = 2;
		//		$course_id = 5;
		if ($user_id !== null && $course_id !== null) {
			if (!(new CourseCtrl())->check_student_registration_possibility($user_id, $course_id)) {
				throw new Exception();
			}
			$this->model->register_course($user_id, $course_id);
		}
	}
	
	public function delete_row (int $id): bool {
		try {
			$this->model->delete_user($id);
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
	
	public function profile_design (string $role_title): void {
		$components = $this->model->get_profile_design($role_title);
		$design     = [];
		foreach ($components as $component) {
			$design[] = new ProfileItem(
				$component['info_type'],
				$component['info_key'],
				$component['row_order'],
				$component['column_order']
			);
		}
		(new ProfileDesignView())->render(...$design);
	}
	
	public function add_additional_info (): void {
		$role_title = 'ADMIN';
		$type       = 'PHONE';
		$info_key   = 'phone';
		$required   = false;
		$editable   = false;
		$erasable   = false;
		$fake       = true;
		$this->model->add_additional_info($role_title, $type, $info_key, $required, $editable, $erasable, $fake);
	}
	
	public function get_additional_info (): void {
		$s = (new QueryBuilder())->select(
			'info_type',
			'info_key',
			'role_title',
			'required',
			'editable',
			'erasable',
			'fake'
		)
		                         ->from('additional_info', 'role', 'pre_def_info_type')
		                         ->execute()
		                         ->fetch_all_ASSOC();
		echo '<pre>';
		print_r($s);
		echo '</pre>';
	}
	
	public function save_profile_design (string $role_title) {
		$_POST["count0"]     = 1;
		$_POST["content0,0"] = "first_name";
		$_POST["count1"]     = 2;
		$_POST["content1,0"] = "last_name";
		$_POST["content1,1"] = "phone";
		$_POST["count2"]     = 1;
		$_POST["content2,0"] = "email";
		$design              = [];
		for ($column = 0 ; $column <= 2 ; $column++) {
			for ($i = 0 ; $i < $_POST["count$column"] ; $i++) {
				$design[] = [
					'row_order'    => $i,
					'column_order' => $column,
					'info_key'     => $_POST["content$column,$i"],
				];
			}
		}
		$this->model->save_profile_design($design, $role_title);
	}
	
}