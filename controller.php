<?php defined('EXT') OR die('No direct script access.');

class Controller
{
	function report($client)
	{
		//var_dump($client);

		/*if($client == 'indep')
		{
			$data = array(
				0 => array('a' => 1, 'b' => 2),
				1 => array('a' => 3, 'b' => 4),
				2 => array('a' => 5, 'b' => 6),
				3 => array('a' => 7, 'b' => 8)
			);
		}
		else
		{
			$data = array(
				0 => array('a' => 10, 'b' => 11),
				1 => array('a' => 12, 'b' => 14),
				2 => array('a' => 14, 'b' => 15),
				3 => array('a' => 16, 'b' => 17)
			);
		}*/

		$model =  new Model_Query($client);

		$data = $model->otladka_reliza('avilon');

		return $data;
	}
}