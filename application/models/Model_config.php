<?php

class Model_config extends CI_Model {
	public function __construct()
	{
		parent::__construct();
	}

	public function getConfigByName($config_name = null) {
		if($config_name) {
			$query = $this->db->get_where('app_config', array('name' => $config_name));
			$result = $query->row_array();
			return $result;
		}
	}

	public function getSimilarConfig($like = null) {
		if ($like) {
			$this->db->select('*');
			$this->db->like('name', $like);
			$query = $this->db->get('app_config');
			$result = $query->result_array();
			return $result;
		}
	}
}
