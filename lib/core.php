<?php defined('EXT') OR die('No direct script access.');

class Lib_Core
{
	public static function find_file($dir, $file, $ext = NULL)
	{
		if ($ext === NULL)
		{
			$ext = EXT;
		}
		elseif ($ext)
		{
			$ext = ".{$ext}";
		}
		else
		{
			$ext = '';
		}

		$path = $dir.DIRECTORY_SEPARATOR.$file.$ext;

		$found = FALSE;

		if (is_file($path))
		{
			$found = $path;
		}

		return $found;
	}

	/**
	 * @param   string  $file
	 * @return  mixed
	 */
	public static function load($file)
	{
		return include $file;
	}
}