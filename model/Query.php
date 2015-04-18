<?php defined('EXT') OR die('No direct script access.');

class Model_Query
{
	private $_db;
	private $_client;
	private $_config;

	/**
	 * @param string $client
	 */
	function __construct($client = 'avilon')
	{
		$this->_db = Database::get_instance();
		$this->_client = $client;
		$this->_config = Lib_Config::get_instance();
	}

	function otladka_reliza($client = 'avilon')
	{
		$clients = Lib_Config::get_config('clients');
		$reliz = $clients[$client]['reliz'];

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

		$result = $this->_db->query($query);

		return $result->fetch_assoc();
	}
}