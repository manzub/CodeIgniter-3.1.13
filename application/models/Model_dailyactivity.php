<?php

class Model_dailyactivity extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_last_activity_list($user_id = null) {
		if ($user_id != null) {
			$this->db->order_by('id', 'DESC');
			$query = $this->db->get_where('daily_activity', array('user_id' => $user_id));
			$result = $query->row_array();
			return $result;
		}
		
		return array();
	}

	public function save_current_activity_list($data = array()) {
		if (!empty($data)) {
			$create = $this->db->insert('daily_activity', $data);
			return ($create == true) ? true : false;
		}

		return false;
	}
}
