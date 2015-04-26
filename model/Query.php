<?php defined('EXT') OR die('No direct script access.');

class Model_Query
{
	private $db;
	private $clients;

	function __construct()
	{
		$this->db = Database::get_instance();
		$this->clients = Lib_Config::get_config('clients');
	}

	// Получаем дату последнего отчёта клиента если есть иначе false
	public function get_date_otchet($client)
	{
		$query = "SELECT date FROM date_otchet WHERE client='".$client."'";

		$result = $this->db->query($query);

		if($result)
		{
			return $result->fetch_assoc();
		}

		return false;
	}

	// Обновляем дату отчёта клиента
	public function update_date_otchet($client)
	{
		$query = "UPDATE date_otchet SET date=".time()." WHERE client='".$client."'";
		//$this->db->query($query);
	}

	// Добавление нового клиента и дату последнего отчёта
	public function add_client_in_date_otchet($client)
	{
		$query = "INSERT INTO date_otchet (client, date) VALUES ( '".$client."',".time()." )";
		$this->db->query($query);
	}

	public function add_new_data($data)
	{
		$query  = "INSERT INTO data_otchet";

		$query .= " (`".implode("`, `", array_keys($data))."`)";

		$query .= " VALUES ('".implode("', '", $data)."') ";

		$this->db->query($query);
	}

	public function get_data_on_period($client, $period = 21)
	{
		$mounthes = array(
			1 => 'янв',
			2 => 'Фев',
			3 => 'мар',
			4 => 'апр',
			5 => 'мая',
			6 => 'июн',
			7 => 'июл',
			8 => 'авг',
			9 => 'сен',
			10 => 'окт',
			11 => 'ноя',
			12 => 'дек'
		);

		$back_date = mktime(9, 0, 0, date("m"), date("d")-$period, date("Y"));

		$query = "SELECT * FROM data_otchet as do WHERE do.client = '".$client."' AND do.date >".$back_date;

		$result = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);

		$data = array();
		foreach($result as $val)
		{
			$data[date('d ', $val['date']).$mounthes[(date('n', $val['date']))]] = $val;
		}

		return $data;
	}

	public function all($client)
	{
		if(
			!isset($this->clients[$client]['projects']) ||
			!isset($this->clients[$client]['date'])
		)
		{
			return null;
		}

		$query = "SELECT COUNT(*) as count FROM issues i
					JOIN issue_statuses s ON i.status_id=s.id
					WHERE i.project_id IN(".$this->clients[$client]['projects'].")
					AND (s.is_closed = 0 OR	i.created_on >= STR_TO_DATE('".date($this->clients[$client]['date'])."','%d,%m,%Y'))";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	public function all_in_work($client)
	{
		if(!isset($this->clients[$client]['projects']))
		{
			return null;
		}

		$query = "SELECT
					COUNT(*) as count
					FROM issues i
					JOIN issue_statuses s ON i.status_id=s.id
					WHERE i.project_id IN(".$this->clients[$client]['projects'].")
					AND s.id=2";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Доработок fix
	public function dorabotok_fix($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			      COUNT(*) as count
			FROM issues i
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=14
			    AND cv.custom_field_id=cf.id) pay ON i.id=pay.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND pay.value = 'Fix'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Доработок not fix
	public function dorabotok_not_fix($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			      COUNT(*) as count
			FROM issues i
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=14
			    AND cv.custom_field_id=cf.id) pay ON i.id=pay.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND pay.value = 'Not Fix'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Доработок без оценки
	public function dorabotok_bez_ocenki($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			      COUNT(*) as count
			FROM issues i
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=14
			    AND cv.custom_field_id=cf.id) pay ON i.id=pay.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND (pay.value IS NULL || pay.value = '')
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Ошибок
	public function errors($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) AS count
			FROM issues i
			JOIN trackers t ON i.tracker_id=t.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND t.name='Ошибки'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Я-аналитик
	public function my($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['analitic'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) AS count
			FROM issues i
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=37
			    AND cv.custom_field_id=cf.id) an ON i.id=an.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND an.value = '".$this->clients[$client]['analitic']."'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// В статусе, отличном от решена и закрыта
	public function all_bez_rechena_y_zakrita($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses s ON i.status_id=s.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND s.id<>3
			AND s.is_closed=0
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Всего в релизе
	public function all_in_reliz($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// В статусе, отличном от решена и закрыта мои
	public function all_bez_rechena_y_zakrita_my($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['analitic'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses s ON i.status_id=s.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=37
			    AND cv.custom_field_id=cf.id) an ON i.id=an.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND s.id<>3
			AND s.is_closed=0
			AND an.value = '".$this->clients[$client]['analitic']."'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Всего в релизе мои
	public function all_in_reliz_my($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['analitic'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=37
			    AND cv.custom_field_id=cf.id) an ON i.id=an.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND an.value = '".$this->clients[$client]['analitic']."'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// В статусе Анализ (без оценки, notFix, Fix)
	public function analiz($client, $ocenka)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses st ON i.status_id=st.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=14
			    AND cv.custom_field_id=cf.id) pay ON i.id=pay.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND pay.value ".$ocenka."
			AND st.name=21
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Всего в анализе более 1 дня(Наивысший), более 2 дней(остальные) (без оценки, notFix, Fix)
	public function all_in_analiz_more_day_naivishiy($client, $ocenka)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses st ON i.status_id=st.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=14
			    AND cv.custom_field_id=cf.id) pay ON i.id=pay.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND st.id=21
			AND pay.value ".$ocenka."
			AND
			(
			   (TO_DAYS('".date('Y,m,d')."')-TO_DAYS(i.updated_on) > 1 AND i.priority_id=7)
			   OR
			   (TO_DAYS('".date('Y,m,d')."')-TO_DAYS(i.updated_on) > 2 AND i.priority_id<>7)
			)
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Мои в статусе Анализ (без оценки, notFix, Fix)
	public function analiz_my($client, $ocenka)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['analitic'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses st ON i.status_id=st.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=37
			    AND cv.custom_field_id=cf.id) an ON i.id=an.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=14
			    AND cv.custom_field_id=cf.id) pay ON i.id=pay.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND pay.value ".$ocenka."
			AND st.name=21
			AND an.value = '".$this->clients[$client]['analitic']."'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Мои всего в анализе более 1 дня(Наивысший), более 2 дней(остальные)(без оценки, notFix, Fix)
	public function all_in_analiz_more_day_naivishiy_my($client, $ocenka)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['analitic'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses st ON i.status_id=st.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=37
			    AND cv.custom_field_id=cf.id) an ON i.id=an.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=14
			    AND cv.custom_field_id=cf.id) pay ON i.id=pay.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND st.id=21
			AND pay.value ".$ocenka."
			AND
			(
			   (TO_DAYS('".date('Y,m,d')."')-TO_DAYS(i.updated_on) > 1 AND i.priority_id=7)
			   OR
			   (TO_DAYS('".date('Y,m,d')."')-TO_DAYS(i.updated_on) > 2 AND i.priority_id<>7)
			)
			AND an.value = '".$this->clients[$client]['analitic']."'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// В статусе Анализ (ошибки)
	public function analiz_error($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses st ON i.status_id=st.id
			JOIN trackers t ON i.tracker_id=t.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND st.name=21
			AND (t.id=1 || t.id=5)
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Всего в анализе более 1 дня(Наивысший), более 2 дней(остальные) (ошибки)
	public function all_in_analiz_more_day_naivishiy_error($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses st ON i.status_id=st.id
			JOIN trackers t ON i.tracker_id=t.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND st.id=21
			AND (t.id=1 || t.id=5)
			AND
			(
			   (TO_DAYS('".date('Y,m,d')."')-TO_DAYS(i.updated_on) > 1 AND i.priority_id=7)
			   OR
			   (TO_DAYS('".date('Y,m,d')."')-TO_DAYS(i.updated_on) > 2 AND i.priority_id<>7)
			)
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Мои в статусе Анализ (ошибки)
	public function analiz_error_my($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['analitic'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses st ON i.status_id=st.id
			JOIN trackers t ON i.tracker_id=t.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=37
			    AND cv.custom_field_id=cf.id) an ON i.id=an.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND st.name=21
			AND (t.id=1 || t.id=5)
			AND an.value = '".$this->clients[$client]['analitic']."'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}

	// Мои всего в анализе более 1 дня(Наивысший), более 2 дней(остальные)(ошибки)
	public function all_in_analiz_more_day_naivishiy_error_my($client)
	{
		if(
			!isset($this->clients[$client]['projects'])||
			!isset($this->clients[$client]['analitic'])||
			!isset($this->clients[$client]['reliz'])
		)
		{
			return null;
		}

		$query = "SELECT
			COUNT(*) as count
			FROM issues i
			JOIN issue_statuses st ON i.status_id=st.id
			JOIN trackers t ON i.tracker_id=t.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=37
			    AND cv.custom_field_id=cf.id) an ON i.id=an.customized_id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			    FROM custom_values cv,
			    custom_fields cf
			    WHERE cf.id=17
			    AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			WHERE i.project_id IN(".$this->clients[$client]['projects'].")
			AND st.id=21
			AND (t.id=1 || t.id=5)
			AND
			(
			   (TO_DAYS('".date('Y,m,d')."')-TO_DAYS(i.updated_on) > 1 AND i.priority_id=7)
			   OR
			   (TO_DAYS('".date('Y,m,d')."')-TO_DAYS(i.updated_on) > 2 AND i.priority_id<>7)
			)
			AND an.value = '".$this->clients[$client]['analitic']."'
			AND r.value='".$this->clients[$client]['reliz']."'";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];
	}
}