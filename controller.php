<?php defined('EXT') OR die('No direct script access.');

class Controller
{
	function report($client)
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

		$data = array(
			'client' => $client,
			'date' => time(),
			'all' => $model->all($client),
			'all_in_work' => $model->all_in_work($client),
			'dorobotok_fix' => $model->dorobotok_fix($client),
			'dorobotok_not_fix' => $model->dorobotok_not_fix($client),
			'dorobotok_bez_ocenki' => $model->dorobotok_bez_ocenki($client),
			'errors' => $model->errors($client),
			'my' => $model->my($client),
			'all_bez_rechena_y_zakrita' => $model->all_bez_rechena_y_zakrita($client),
			'all_in_reliz' => $model->all_in_reliz($client),
			'all_bez_rechena_y_zakrita_my' => $model->all_bez_rechena_y_zakrita_my($client),
			'all_in_reliz_my' => $model->all_in_reliz_my($client),
			'not_fix_analiz_bez_ocenki_my' => $model->not_fix_analiz_bez_ocenki_my($client),
			'all_in_analiz_more_day_naivishiy_my' => $model->all_in_analiz_more_day_naivishiy_my($client),
		);

		return $data;
	}
}