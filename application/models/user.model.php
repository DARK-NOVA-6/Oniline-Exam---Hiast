<?php

class UserModel
	extends Dbh {
	public function __construct () {
		parent::__construct();
	}
	
	public function get_all_users_column_name (): array {
		return ["user.id", "first_name", "last_name", "email", "role_title"];
	}
	
	public function get_all_users (array $options): array {
		return $this->get_all(['user', 'role'], $this->get_all_users_column_name(), $options);
	}
	
	/**
	 * @throws Exception
	 */
	public function get_all_users_count (array $options): int {
		$options['search_from'] ??= join(',', $this->get_all_users_column_name());
		return $this->get_count(['user', 'role'], $this->get_all_users_column_name(), $options);
	}
	
	/**
	 * @throws Exception
	 */
	public function add_user (string  $first_name,
	                          string  $last_name,
	                          string  $email,
	                          string  $pass,
	                          string  $role_title,
	                          ?string $test_center
	): int {
		return (new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('first_name', $first_name),
			QueryBuilder::INSERT_ATTR('last_name', $last_name),
			QueryBuilder::INSERT_ATTR('email', $email),
			QueryBuilder::INSERT_ATTR('password', password_hash($pass, PASSWORD_DEFAULT)),
			QueryBuilder::INSERT_ATTR(
				'role_id',
				(new QueryBuilder())->select('id')
				                    ->from('role')
				                    ->where(
					                    QueryBuilder::AND(
						                    QueryBuilder::EQUAL('role_title', $role_title),
						                    QueryBuilder::EQUAL('is_active', 'N')
					                    )
				                    )
				                    ->sub_query()
			),
			QueryBuilder::INSERT_ATTR(
				'test_center_id',
				(new QueryBuilder())->select('id')
				                    ->from('test_center')
				                    ->where(QueryBuilder::EQUAL('test_center_location', $test_center))
				                    ->sub_query()
			),
		)
		                           ->into('user')
		                           ->execute();
	}
	
	/**
	 * @throws Exception
	 */
	public function check_availability_email (string $email): bool {
		return !(new QueryBuilder())->select()
		                            ->from('user')
		                            ->where(QueryBuilder::EQUAL('email', $email))
		                            ->execute()
		                            ->has_result();
	}
	
	/**
	 * @throws Exception
	 */
	public function get_user (string $email, string $pass): ?array {
		$user = (new QueryBuilder())->select('user.id', 'first_name', 'last_name', 'password', 'role_title')
		                            ->from('user', 'role')
		                            ->where(QueryBuilder::EQUAL('email', $email))
		                            ->execute()
		                            ->fetch_one_ASSOC();
		if ($user !== null && password_verify($pass, $user['password'])) {
			return $user;
		}
		return null;
	}
	
	public function get_full_user (int $id): array {
		$res  = (new QueryBuilder())->select('info_key', 'required', 'editable', 'value', 'type_label')
		                            ->from('pre_def_info_type', 'additional_info', 'user_additional_info')
		                            ->where(QueryBuilder::EQUAL('user_id', $id))
		                            ->execute()
		                            ->fetch_all_ASSOC();
		$res2 = (new QueryBuilder())->select('first_name', 'last_name', 'email', 'role_title')
		                            ->from('user', 'role')
		                            ->where(QueryBuilder::EQUAL('user.id', $id))
		                            ->execute()
		                            ->fetch_one_ASSOC();
		if ($res2['role_title'] == 'TC_ADMIN') {
			$res2 ['test_center'] = (new QueryBuilder())->select('test_center_location')
			                                            ->from('test_center', 'user')
			                                            ->where(QueryBuilder::EQUAL('user.id', $id))
			                                            ->execute()
			                                            ->fetch_one();
		}
		return $res2;
	}
	
	
	/*
	delete an additional info field, with all assigned values by users (user additional info)
	 */
	public function deleteAdditionalInfo ($roleTitle, $infoKey) {
		$additionalId = $this->getIdFirstCell(
			'additional_info',
			[
				self::$CONDITION => new self::$C_AND(
					new self::$C_EQUAL('info_key', $infoKey),
					new self::$C_EQUAL('role_id', $this->getRoleId($roleTitle, true)),
				),
			]
		);
		
		$this->deleteUserAdditionalInfo('additional_info_id', $additionalId);
		$this->deleteRowById('additional_info', $additionalId);
	}
	
	/*
	add an additional info.
	function will automatically do the following:
	1- instantiate an empty (value = null) row in user additional info
	2- turn each account with a specific role to inactive mode, in case of required info
	 */
	public function addAdditionalInfo ($roleTitle, $typeLabel, $infoKey, $required, $editable, $erasible) {
		$roleIdN = $this->getRoleId($roleTitle, false);
		$roleIdY = $this->getRoleId($roleTitle, true);
		$typeId  = $this->getIdFirstCell(
			'pre_def_info_type',
			[
				self::$CONDITION => new self::$C_EQUAL('type_label', $typeLabel),
			]
		);
		
		$this->insertRow('additional_info',
		                 [
			                 'role_id'              => $roleIdY,
			                 'pre_def_info_type_id' => $typeId,
			                 'info_key'             => $infoKey,
			                 'required'             => $required ? 'Y' : 'N',
			                 'editable'             => $editable ? 'Y' : 'N',
			                 'erasible'             => $erasible ? 'Y' : 'N',
		                 ]
		);
		
		$additionalId = $this->getIdFirstCell(
			'additional_info',
			[
				self::$CONDITION => new self::$C_AND(
					new self::$C_EQUAL('info_key', $infoKey),
					new self::$C_EQUAL('role_id', $roleIdY),
				),
			]
		);
		
		foreach ([$roleIdN, $roleIdY] as $roleId) {
			$this->insertQuery(
				'user_additional_info',
				['additional_info_id', 'user_id'],
				$this->selectQuery(
					'user',
					[
						self::$CONDITION => new self::$C_EQUAL('role_id', $roleId),
						self::$COLUMNS   => [$additionalId, 'id'],
						self::$SQL       => true,
						self::$LIMIT     => false,
					]
				)
			);
		}
		
	}
	
	/*
	delete all rows for a specific account from user additional info
	 */
	// rewrite
	private function deleteUserAdditionalInfo ($key, $val) {
		if (strtoupper($key) != 'ADDITIONAL_INFO_ID' && strtoupper($key) != 'USER_ID') {
			throw new Exception("Error Processing Request", 1);
		}
		
		$this->deleteQuery(
			'user_additional_info',
			[
				self::$CONDITION => new self::$C_EQUAL($key, $val),
			]
		);
	}
	
	
	/*
	returns all available additional info with option to the required only
	 */
	public function register_course (int $user_id, int $course_id) {
		(new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('course_id', $course_id),
			QueryBuilder::INSERT_ATTR('user_id', $user_id),
			QueryBuilder::INSERT_ATTR('state', 'R'),
			QueryBuilder::INSERT_ATTR('mark', 0)
		)
		                    ->into('student_course')
		                    ->execute();
		
	}
	
	/**
	 * @throws Exception
	 */
	public function delete_user (int $id): void {
		$this->delete_by_id('user', $id);
	}
	
	//	public function __construct(String $type,String $name,?int $posX,?int $posY) {
	
	public function get_profile_design (string $role_title): array {
		return (new QueryBuilder())->select('info_key', 'info_type', 'row_order', 'column_order')
		                           ->from('pre_def_info_type', 'view_design_info', 'additional_info', 'role')
		                           ->where(QueryBuilder::EQUAL('role_title', $role_title))
		                           ->execute()
		                           ->fetch_all_ASSOC();
	}
	
	public function add_additional_info (string $role_title,
	                                     string $type,
	                                     string $info_key,
	                                     bool   $required,
	                                     bool   $editable,
	                                     bool   $erasable,
	                                     bool   $fake
	) {
		(new QueryBuilder())->insert(
			QueryBuilder::INSERT_ATTR('info_key', $info_key),
			QueryBuilder::INSERT_ATTR('required', $required ? 'Y' : 'N'),
			QueryBuilder::INSERT_ATTR('editable', $editable ? 'Y' : 'N'),
			QueryBuilder::INSERT_ATTR('erasable', $erasable ? 'Y' : 'N'),
			QueryBuilder::INSERT_ATTR('fake', $fake ? 'Y' : 'N'),
			QueryBuilder::INSERT_ATTR(
				'role_id',
				(new QueryBuilder())->select('id')
				                    ->from('role')
				                    ->where(
					                    QueryBuilder::AND(
						                    QueryBuilder::EQUAL('role_title', $role_title),
						                    QueryBuilder::EQUAL('is_active', 'Y')
					                    )
				                    )
				                    ->sub_query()
				                    ->get_statement()
			),
			QueryBuilder::INSERT_ATTR(
				'pre_def_info_type_id',
				(new QueryBuilder())->select('id')
				                    ->from('pre_def_info_type')
				                    ->where(QueryBuilder::EQUAL('info_type', $type))
			)
		)
		                    ->into('additional_info')
		                    ->execute();
	}
	
	public function clear_profile_design (string $role_title) {
		(new QueryBuilder())->delete()
		                    ->from('view_design_info')
		                    ->where(
			                    QueryBuilder::MULTI_OPT(
				                    'additional_info_id',
				                    (new QueryBuilder())->select('additional_info.id')
				                                        ->from('additional_info', 'role')
				                                        ->where(QueryBuilder::EQUAL('role_title', $role_title))
				                                        ->sub_query()
				                                        ->get_statement()
			                    )
		                    )
		                    ->execute();
	}
	
	public function save_profile_design (array $design, $role_title) {
		try {
			$this->clear_profile_design($role_title);
			foreach ($design as $item) {
				(new QueryBuilder())->insert(
					QueryBuilder::INSERT_ATTR('row_order', $item['row_order']),
					QueryBuilder::INSERT_ATTR('column_order', $item['column_order']),
					QueryBuilder::INSERT_ATTR(
						'additional_info_id',
						(new QueryBuilder())->select('id')
						                    ->from('additional_info')
						                    ->where(QueryBuilder::EQUAL('info_key', $item['info_key']))
						                    ->sub_query()
						                    ->get_statement()
					)
				)
				                    ->into('view_design_info')
				                    ->execute();
			}
		} catch (Exception) {
		
		}
	}
	
	
	private function getAdditionalInfoId ($roleId, $requiredOnly = false) {
		if (!$requiredOnly) {
			return $this->selectQuery(
				'additional_info',
			
			);
			return $this->getOneColumnByEqualCondition('additional_info', 'id', ['role_id' => $roleId]);
		} else {
			return $this->getOneColumnByEqualCondition(
				'additional_info',
				'id',
				['role_id' => $roleId, 'required' => 'Y']
			);
		}
	}
	
	/*
	initialize an empty rows (value = null) for the user additional info for this account,
	according to the role id (it should be in active mode)
	 */
	private function instantiateUserAdditionalInfo ($userId, $roleTitle) {
		$this->insertQuery(
			'user_additional_info',
			['additional_info_id', 'user_id'],
			$this->selectQuery(
				'additional_info',
				[
					self::$CONDITION => new self::$C_EQUAL('role_id', $this->getRoleId($roleTitle, true)),
					self::$COLUMNS   => ['id', $userId],
					self::$SQL       => true,
				]
			),
		);
	}
	
	/*
	some information will be given and returns the additional info from it(associative array)
	 */
	private function filterAdditionalInfo ($info) {
		$extData = array();
		foreach ($info as $key => $val) {
			if (strtoupper($key) != 'ROLE_TITLE' && !$this->checkExistColumn('user', $key)) {
				$extData[ $key ] = $val;
			}
		}
		return $extData;
	}
	
	/*
	update the value of additional information, by an associative array (key => value).
	if the exception mode is on, then an exception will be thrown if some key doesn't exist for this account.
	or no action will be taken
	 */
	private function updateUserAdditionalInfo ($userId, $info, $exceptionMode = false) {
		foreach ($info as $key => $val) {
			$this->updateQuery(
				'user_additional_info',
				['value' => $val],
				[
					self::$CONDITION => new self::$C_EQUAL(
						'additional_info_id',
						$this->getIdFirstCell(
							'additional_info',
							[
								self::$CONDITION => new self::$C_EQUAL('info_key', $key),
								self::$SQL       => true,
							]
						),
						true,
						true,
					),
				]
			);
		}
	}
	
	private function updateUserStdInfo ($userId, $info, $exceptionMode = false) {
		$this->updateQuery(
			'user',
			$info,
			[
				self::$CONDITION => new self::$C_EQUAL('id', $userId),
			]
		);
	}
	
	/*
	check if the account has a lack of required additional information.
	if so, it's role will turn to inactive
	 */
	public function fixRole ($userId) {
		$roleTitle = $this->getFirstCell(
			['user', 'role'],
			[
				self::$COLUMNS   => 'role_title',
				self::$CONDITION => new self::$C_EQUAL('user.id', $userId),
			]
		);
		$lackInfo  = $this->GOODcheckExistRow(
			['user', 'user_additional_info', 'additional_info'],
			[
				self::$CONDITION => new self::$C_AND(
					new self::$C_NULL('value'),
					new self::$C_EQUAL('user.id', $userId),
					new self::$C_EQUAL('required', 'Y'),
				),
			]
		);
		if ($lackInfo) {
			$this->updateRowById('user', $userId, ['role_id' => $this->getRoleId($roleTitle, false)]);
		} else {
			$this->updateRowById('user', $userId, ['role_id' => $this->getRoleId($roleTitle, true)]);
		}
	}
	
	/*
	.
	.
	.
	.
	.
	.
	.
	.
	 */
	/*
	Change the user's role,
	all additional information will be either removed or kept according to the title of it (info_key)
	 */
	public function changeRole ($userId, $val) {
		// $this->deleteQuery(
		//     'user_additional_info',
		//     [
		//         self::$CONDITION=>
		//     ]
		// )
		$additionalInfo = $this->filterAdditionalInfo($this->getFullInfoUser($userId));
		$this->deleteUserAdditionalInfo('user_id', $userId);
		$roleIdN = $this->getRoleId($val, false);
		$roleIdY = $this->getRoleId($val, true);
		$this->updateRowById('user', $userId, ['role_id' => $roleIdN]);
		$this->instantiateUserAdditionalInfo($userId, $val);
		$this->updateUserAdditionalInfo($userId, $additionalInfo);
		$this->fixRole($userId);
	}
	
	/*
	edit some information of account (additional or not, even role).
	according to an associative array (field title => new value)
	in case of some exception, no edit will be applied
	 */
	public function updateUser ($userId, $newInfo) {
		try {
			$this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
			$this->db->beginTransaction();
			$stdData   = array();
			$roleTitle = null;
			foreach ($newInfo as $key => $val) {
				if (strtoupper($key) == 'ROLE_TITLE') {
					$roleTitle = $val;
				} else if ($this->checkExistColumn('user', $key)) {
					$stdData[ $key ] = $val;
				}
			}
			
			if (count($stdData)) {
				if (isset($stdData['password'])) {
					$stdData['password'] = password_hash($stdData['password'], PASSWORD_DEFAULT);
				}
				$this->updateUserStdInfo($userId, $stdData);
			}
			
			$this->updateUserAdditionalInfo($userId, $this->filterAdditionalInfo($newInfo));
			
			if ($roleTitle) {
				$this->changeRole($userId, $roleTitle);
			}
			$this->fixRole($userId);
			$this->db->commit();
		} catch (Exception $e) {
			$this->db->rollBack();
			echo $e->getMessage();
		} finally {
			$this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
		}
	}
	
	/*
	delete an account, if some exception happened no action will be taken
	 */
	
	public function deleteUser ($userId) {
		
		try {
			$this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
			$this->db->beginTransaction();
			$this->deleteUserAdditionalInfo($userId);
			$this->deleteRowById('user', $userId);
			$this->db->commit();
		} catch (Exception $e) {
			$this->db->rollBack();
			echo $e->getMessage();
		} finally {
			$this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
		}
	}
	
	/*
	returns true if an additional information is required for this role
	 */
	public function checkRequiredInfo ($roleTitle, $info_key) {
		return $this->GOODcheckExistRow(
			'additional_info',
			[
				self::$CONDITION => new self::$C_AND(
					new self::$C_EQUAL('role_id', $this->getRoleId($roleTitle, true)),
					new self::$C_EQUAL('info_key', $info_key),
					new self::$C_EQUAL('required', 'Y'),
				),
			]
		);
	}
	
	public function checkEditableInfo ($roleTitle, $info_key) {
		return $this->GOODcheckExistRow(
			'additional_info',
			[
				self::$CONDITION => new self::$C_AND(
					new self::$C_EQUAL('role_id', $this->getRoleId($roleTitle, true)),
					new self::$C_EQUAL('info_key', $info_key),
					new self::$C_EQUAL('editable', 'Y'),
				),
			]
		);
	}
	
	public function getAllUserAdditionalInfo ($userId) {
		$this->_allInfo = array();
		$this->selectQuery(
			['user', 'user_additional_info', 'additional_info'],
			[
				self::$COLUMNS   => ['info_key', 'value'],
				self::$CONDITION => new self::$C_EQUAL('user.id', $userId),
				self::$FETCH     => false,
			]
		)
		     ->fetchAll(PDO::FETCH_FUNC, fn($key, $val) => $this->_allInfo[ $key ] = $val);
		return $this->_allInfo;
	}
	
	public function getUser ($userId) {
		$temp1 = $this->selectQuery(
			['role', 'user'],
			[
				self::$COLUMNS   => ['first_name', 'last_name', 'email', 'role_title'],
				self::$CONDITION => new self::$C_EQUAL('user.id', $userId),
				self::$FETCH     => false,
			]
		)
		              ->fetch();
		
		if (!$temp1) {
			return null;
		}
		
		$temp2 = $this->selectQuery(
			['test_center', 'user'],
			[
				self::$COLUMNS   => 'test_center_location',
				self::$CONDITION => new self::$C_EQUAL('user.id', $userId),
				self::$FETCH     => false,
			]
		)
		              ->fetch();
		
		if (!$temp2) {
			return $temp1;
		} else {
			return array_merge($temp1, $temp2);
		}
	}
	
	/*
	returns an associative array in the format:
	title of the field => value of it
	for a specific user, defined by id
	or null if there is no such user
	 */
	public function getFullInfoUser ($userId) {
		$temp1 = $this->getUser($userId);
		
		if (!$temp1) {
			return null;
		}
		
		return array_merge($temp1, $this->getAllUserAdditionalInfo($userId));
	}
	
	/*
	insert a new user to the database with by:
	1-his role
	2-standard information (first and last name, email and password)
	3-some (possibly zero) additional information (field name => value)
	in case of some error, no information will be added
	 */
	public function setUser ($roleTitle, $stdData, $extData) {
		
		try {
			$this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
			$this->db->beginTransaction();
			$stdData['role_id']  = $this->getRoleId($roleTitle, false);
			$stdData['password'] = password_hash($stdData['password'], PASSWORD_DEFAULT);
			$this->insertQuery('user', $stdData);
			$userId = $this->getIdFirstCell(
				'user',
				[
					self::$CONDITION => new self::$C_EQUAL('email', $stdData['email']),
				]
			);
			$this->instantiateUserAdditionalInfo($userId, $roleTitle);
			$this->updateUserAdditionalInfo($userId, $extData, true);
			$this->fixRole($userId);
			$this->db->commit();
		} catch (Exception $e) {
			$this->db->rollBack();
			echo $e->getMessage();
		} finally {
			$this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
		}
	}
	
}