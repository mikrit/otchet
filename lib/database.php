<?php defined('EXT') OR die('No direct script access.');

class Database
{
	private $_connection;
	private static $_instance;

	private $_host = "192.168.14.220";
	private $_username = "redmineRead";
	private $_password = "hf57slbvbn";
	private $_database = "redmine_0610";

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
		$this->_connection = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);

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