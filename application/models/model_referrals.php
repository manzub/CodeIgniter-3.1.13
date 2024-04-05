<?php

class Model_referrals extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getReferrals($ref_code = null) {
		if ($ref_code != null) {
			$query = $this->db->get_where('referrals', array('ref_code' => $ref_code));
			$result = $query->result_array();
			return $result;
		}
	}

	public function referUser($data = array()) {
		if (!empty($data)) {
			$insert = $this->db->insert('referrals', $data);

			$ref_id = $this->db->insert_id();

			return ($insert == true) ? $ref_id : false;
		}
	}

	public function updateReferral($ref_id = null, $data = array()) {
		if (!empty($data)) {
			$updated = $this->db->update('referrals', $data);

			return ($updated == true) ? true : false;
		}
	}
}
