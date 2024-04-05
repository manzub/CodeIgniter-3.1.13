<?php

class Model_logs extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function logActivity($data = array())
	{
		if (!empty($data)) {
			// insert to logs db
			$inserted = $this->db->insert('user_logs', $data);
			return ($inserted == true) ? true : false;
		}
	}

	public function getUserLogsById($user_id = null) {
		if ($user_id != null) {
			$this->db->order_by('last_modified', 'DESC');
			$query = $this->db->get_where('user_logs', array('user_id' => $user_id));
			return $query->result_array();
		}
	}
}
