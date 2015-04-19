<?php defined('EXT') OR die('No direct script access.');

class Database
{
	private $_connection;
	private static $_instance;

	public static function get_instance()
	{
		if (!self::$_instance)
		{ // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		$db = Lib_Config::get_config('database');
		$connect = $db['mysql'];

		$this->_connection = new mysqli($connect['host'], $connect['username'], $connect['password'], $connect['database']);

		if (mysqli_connect_errno()) {
			printf("Подключение к серверу MySQL невозможно. Код ошибки: %s\n", mysqli_connect_error());
			exit;
		}
	}

	private function __clone()
	{
	}


	public function get_connection()
	{
		return $this->_connection;
	}

	public function query($query)
	{
		return $this->_connection->query($query);
	}
}