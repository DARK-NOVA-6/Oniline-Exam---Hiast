<?php

class RbacModel
	extends BaseModel
{
	
	public function get_all_roles_column_name (): array {
		return ['role.id', 'role_title', 'is_active'];
	}
	
	public function get_all_roles (array $options): array {
		return $this->get_all(['role'], $this->get_all_roles_column_name(), $options);
	}
	
	/**
	 * @throws Exception
	 */
	public function get_all_roles_count (array $options): int {
		return $this->get_count('role',$this->get_all_roles_column_name(), $options);
	}
	
	/**
	 * @throws Exception
	 */
	public function add_role (string $title, bool $is_active): void {
		(new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('role_title', $title),
			QueryBuilder::INSERT_ATTR('is_active', $is_active ? 'Y' : 'N')
		)
		                    ->into('role')
		                    ->execute();
	}
	
	/**
	 * @throws Exception
	 */
	public function add_permissions_to_role (int $role_id, int ...$permissions_id) {
		foreach ($permissions_id as $permission_id) {
			(new QueryBuilder())->insert(
				QueryBuilder::INSERT_ATTR('role_id', $role_id),
				QueryBuilder::INSERT_ATTR('pre_def_permission_id', $permission_id)
			)
			                    ->into('role_permission')
			                    ->execute();
		}
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_permissions_from_role (int $role_id, int ...$permissions_id): void {
		(new QueryBuilder())->delete()
		                    ->from('role_permission')
		                    ->where(
			                    QueryBuilder::AND(
				                    QueryBuilder::MULTI_OPT('pre_def_permission_id', ...$permissions_id),
				                    QueryBuilder::EQUAL('role_id', $role_id)
			                    )
		                    )
		                    ->execute();
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_role (string $title): void {
		(new QueryBuilder())->delete()
		                    ->from('role')
		                    ->where(QueryBuilder::EQUAL('role_title', $title))
		                    ->execute();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_permission_by_role (int $id): array {
		return (new QueryBuilder())->select('permission_label')
		                           ->from('role_permission', 'pre_def_permission')
		                           ->where(QueryBuilder::EQUAL('role_id', $id))
		                           ->execute()
		                           ->fetch_all_num();
	}
	
	public static function role_has_permission (int $role_id, string $permission_label): bool {
		try {
			return (new QueryBuilder())->select()
			                           ->from('role_permission', 'pre_def_permission')
			                           ->where(
				                           QueryBuilder::AND(
					                           QueryBuilder::EQUAL('role_id', $role_id),
					                           QueryBuilder::EQUAL('permission_label', $permission_label)
				                           )
			                           )
			                           ->execute()
			                           ->has_result();
		} catch (Exception) {
			return false;
		}
	}
	
	public function get_full_role_by_id (int $id) {
	
	}
	
}