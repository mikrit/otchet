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
		$query = "SELECT date FROM date_otchet WHERE client=".$client;

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
		$query = "UPDATE date_otchet SET date=".time()." WHERE client=".$client;
		$this->db->query($query);
	}

	// Добавление нового клиента и дату последнего отчёта
	public function add_client_in_date_otchet($client)
	{
		$query = "INSERT INTO date_otchet (client, date) VALUES ( '".$client."',".time()." )";
		$this->db->query($query);
	}

	public function add_new_data($data)
	{
		$keys = '';
		$values = '';

		foreach($data as $key => $val)
		{
			$keys .= $key;
			$values .= $val;
		}

		var_dump($keys, $values);
		die;

		$query = "INSERT INTO date_otchet (client, date) VALUES ( '".$client."',".time()." )";
		$this->db->query($query);
	}

	public function get_data_on_period($client, $period = 21)
	{
		$back_date = mktime(9, 0, 0, date("m"), date("d")-$period, date("Y"));

		$query = "SELECT * FROM data_otchet as do WHERE do.client = ".$client." AND do.date >".$back_date;

		$result = $this->db->query($query);

		return $result->fetch_assoc();
	}

	public function all($client)
	{
		$query = "SELECT COUNT(*) as count FROM issues i
					JOIN issue_statuses s ON i.status_id=s.id
					WHERE i.project_id IN(".$this->clients[$client]['projects'].")
					AND (s.is_closed = 0 OR i.created_on >= STR_TO_DATE('".date('d,m,Y')."','%d,%m,%Y'))";

		$result = $this->db->query($query)->fetch_assoc();

		return $result['count'];;
	}


	function otladka_reliza($client)
	{
		$reliz = $$this->clients[$client]['reliz'];

		$query = "
			SELECT
			COUNT(*) as count
			FROM issues i
			JOIN projects p ON i.project_id=p.id
			JOIN trackers t ON i.tracker_id=t.id
			JOIN issue_statuses st ON i.status_id=st.id
			LEFT JOIN (SELECT cv.customized_id,cv.value
			FROM custom_values cv,
			custom_fields cf
			WHERE cf.id=17
			AND cv.custom_field_id=cf.id) r ON i.id=r.customized_id
			LEFT JOIN users u ON i.assigned_to_id=u.id
			WHERE i.project_id IN(47,48,52)
			AND r.value='$reliz'";

		$result = $this->db->query($query);

		return $result->fetch_assoc();
	}
}