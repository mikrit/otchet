<?php defined('EXT') OR die('No direct script access.');

class View
{
	protected static $_global_data = array();

	public static function factory($file = NULL, array $data = NULL)
	{
		return new View($file, $data);
	}

	/**
	 * @param $view_filename
	 * @param array $view_data
	 * @return string
	 * @throws Exception
	 */
	protected static function capture($view_filename, array $view_data)
	{
		extract($view_data, EXTR_SKIP);

		if (View::$_global_data)
		{
			extract(View::$_global_data, EXTR_SKIP | EXTR_REFS);
		}

		ob_start();

		try
		{
			/** @var TYPE_NAME $view_filename */
			include $view_filename;
		}
		catch (Exception $e)
		{
			ob_end_clean();
			throw $e;
		}

		return ob_get_clean();
	}


	protected $_file;

	protected $_data = array();

	public function __construct($file = NULL, array $data = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if ($data !== NULL)
		{
			$this->_data = $data + $this->_data;
		}
	}

	public function & __get($key)
	{
		if (array_key_exists($key, $this->_data))
		{
			return $this->_data[$key];
		}
		elseif (array_key_exists($key, View::$_global_data))
		{
			return View::$_global_data[$key];
		}
		else
		{
			throw new Exception('View variable is not set: :var');
		}
	}

	public function __set($key, $value)
	{
		$this->set($key, $value);
	}

	public function set_filename($file)
	{
		if (($path = Lib_Core::find_file('views', $file)) === FALSE)
		{
			throw new Exception('The requested view :file could not be found');
		}

		$this->_file = $path;

		return $this;
	}

	public function set($key, $value = NULL)
	{
		if (is_array($key))
		{
			foreach ($key as $name => $value)
			{
				$this->_data[$name] = $value;
			}
		}
		else
		{
			$this->_data[$key] = $value;
		}

		return $this;
	}

	public function render($file = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if (empty($this->_file))
		{
			throw new Exception('You must set the file to use within your view before rendering');
		}

		// Combine local and global data and capture the output
		return View::capture($this->_file, $this->_data);
	}
}