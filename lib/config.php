<?php defined('EXT') OR die('No direct script access.');

class Lib_Config
{
	private static $config;
	private static $_instance;

	public static function get_instance()
	{
		if(!self::$_instance)
		{ // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		self::$config = array();
		$this->init();
	}

	public function init()
	{
		$files = new DirectoryIterator(HOME_DIR.DIRECTORY_SEPARATOR.'config');

		foreach ($files as $file)
		{
			if($file->isFile())
			{
				$this->load_config($file->getBasename('.php'));
			}
		}
	}

	public function load_config($file)
	{
		if(($conf = Lib_Core::find_file('config', $file)) != FALSE)
		{
			self::$config[$file] = Lib_Core::load($conf);
		}

		return self::$config;
	}

	public static function get_config($name)
	{
		return self::$config[$name];
	}
}