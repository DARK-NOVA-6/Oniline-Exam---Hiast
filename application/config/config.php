<?php

require_once 'application/config/autoload.php';

error_reporting(E_ALL);

ini_set("display_errors", 1);

const URL         = 'http://127.0.0.1:8081/hiast/';
const STORAGE     = "C:\\xampp\\htdocs\\hiast\\storage";
const IMG_STORAGE = STORAGE."\\img";

$config = new Config();
try {
	$config->set_db([
		                'dsn'      => 'mysql',
		                'host'     => '127.0.0.1',
		                'name'     => 'university',
		                'username' => 'root',
		                'password' => '',
		                'options'  => [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		                               PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		                               PDO::ATTR_CASE               => PDO::CASE_LOWER,
		                               PDO::ATTR_ORACLE_NULLS       => PDO::NULL_TO_STRING,
		                               // PDO::ATTR_AUTOCOMMIT => 0,
		                ],
	                ]);
	$config->register_email_validator(
		"/^[a-z]([a-z0-9]|([.][a-z0-9]))*@[a-z][a-z0-9]*[.][a-z0-9]([a-z0-9]|([.][a-z0-9]))*$/",
		"INVALID_EMAIL"
	)
	       ->register_email_validator("/^.{6,45}$/", "LEN_EMAIL")
	       ->register_name_validator("/^[a-zA-Z](([ ][a-zA-Z]|[a-zA-Z]))*$/", "INVALID_NAME")
	       ->register_name_validator("/^[a-zA-Z ]{3,45}$/", "LEN_NAME")
	       ->register_phone_validator("/^09[345689][0-9]{7}$/", "INVALID_PHONE")
	       ->register_password_validator("/^.{6,45}$/", "WEAK_PAS");
	
} catch (Exception $e) {
}