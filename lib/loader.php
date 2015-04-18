<?php defined('EXT') OR die('No direct script access.');

class Autoloader
{
	/**
	 * @param $className
	 */
	public static function auto_load($className)
	{
		$pathParts = explode('_', $className);
		$fileName = implode(DIRECTORY_SEPARATOR, $pathParts) . '.php';
		require_once($fileName);
	}
}