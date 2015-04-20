<?php defined('EXT') OR die('No direct script access.');

class Controller
{
	function report

	($client)
	{
		$model = new Model_Query($client);

		//$client = 'indep';

		// Получаем дату последнего отчёта
		$time = $model->get_date_otchet($client);

		// Если нет клиента дата = false
		if($time == false)
		{
			$model->add_client_in_date_otchet($client);

			// Собераем и сохраняем данные по новому клиенту
			$data = $this->get_new_data($client);

			$model->add_new_data($data);

			$time['date'] = time();
		}

		//var_dump(mktime(8, 0, 0, date("m"), date("d"), date("Y")));

		//$time['date'] = 1429502400;

		// Если дата отчёта меньше текущей даты то берём новые данные, записываем их в БД и меняем дату на текущую
		if (mktime(9, 0, 0, date("m"), date("d"), date("Y")) > $time['date'])
		{
			$data = $this->get_new_data($client);
			$model->add_new_data($data);

			$model->update_date_otchet($client);
		}

		$data = $model->get_data_on_period($client);

		return $data;
	}

	//
	public function get_new_data($client)
	{
		$model = new Model_Query($client);

		$all = $model->all($client);

		$data = array(
			'client' => $client,
			'date' => time(),
			'all' => $all,
		);

		return $data;
	}
}