<?php

interface Dashboardable {
	
	public function get_table (array $options): array;
	
	public function get_table_view (): AdminView|TCAdminView;
	
	public function get_more_details (int $id): array;
	
	public function delete_row (int $id): bool;
	
//	public function update_more_details (int $id, array $data): bool;
	
}