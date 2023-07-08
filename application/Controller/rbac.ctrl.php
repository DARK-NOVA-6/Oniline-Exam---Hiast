<?php


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

require_once 'application/Controller/dashboardable.int.php';


class RbacCtrl
	extends Controller
	implements Dashboardable {
	
	protected RbacModel $model;
	
	#[Pure]
	public function __construct () {
		$this->model = new RbacModel();
	}
	
	
	public function get_table (array $options): array {
		return [
			'columns' => $this->model->get_all_roles_column_name(),
			'data'    => $this->model->get_all_roles($options),
			'count'   => (new RbacCtrl())->get_all_roles_count($options),
			'page'    => $options['page'] ?? 1,
			'size'    => $options['size'] ?? 20,
		];
	}
	
	#[Pure]
	public function get_table_view (): RolesTableView {
		return new RolesTableView();
	}
	
	
	/**
	 * @throws Exception
	 */
	public function get_all_roles_count (array $options): int {
		return $this->model->get_all_roles_count($options);
	}
	
	/**
	 * @throws Exception
	 */
	public function add_role (string $role_title): void {
		$role_title = $_POST['role_title'] ?? null;
		if ($role_title == null) {
			return;
		}
		try {
			$this->model->add_role($role_title, true);
			$this->model->add_role($role_title, false);
		} catch (Exception) {
		
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function add_permissions_to_role (): void {
		$role_id        = $_POST['role_id'] ?? null;
		$permissions_id = $_POST['permissions'] ?? [];
		if ($role_id != null) {
			$this->model->add_permissions_to_role($role_id, ...$permissions_id);
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_permissions_from_role (): void {
		$role_id        = $_POST['role_id'] ?? null;
		$permissions_id = $_POST['permissions'] ?? [];
		if ($role_id != null) {
			$this->model->delete_permissions_from_role($role_id, ...$permissions_id);
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_role (): void {
		$role_title = $_POST['role_title'] ?? null;
		if ($role_title != null) {
			$this->model->delete_role($role_title);
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function get_permission_of_role (): array {
		$role_id = $_POST['role_id'] ?? 6;
		if ($role_id == null) {
			return [];
		}
		return $this->model->get_permission_by_role($role_id);
	}
	
	public static function role_has_permission (string $permission_label): bool {
		$role_id = $_SESSION['user_id'] ?? -1;
		return RbacModel::role_has_permission($role_id, $permission_label);
	}
	
	public function delete_row (int $id): bool {
		try {
			$this->model->delete_role($id);
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
	
	
	public function get_more_details (int $id): array {
		return $this->model->get_full_role_by_id($id);
	}
	
}