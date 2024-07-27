<?php
class Model_bonuses extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getBonusById($bonus_id = null)
	{
		if ($bonus_id != null) {
			$query = $this->db->get_where('bonus_activities', array('id' => $bonus_id));
			$result = $query->row_array();
			return $result;
		}

		return array();
	}

	public function getAllBonuses()
	{
		$query = $this->db->get('bonus_activities');
		$result = $query->result_array();
		return $result;
	}

	public function getAvailableBonuses() {
		$query = $this->db->get_where('bonus_activities', array('status' => 'available'));
		$result = $query->result_array();
		return $result;
	}

	public function create($data = array())
	{
		if (!empty($data)) {
			$insert = $this->db->insert('bonus_activities', $data);
			return ($insert == true) ? true : false;
		}

		return false;
	}


	public function remove($bonus_id)
	{
		if ($bonus_id != null) {
			$this->db->where('id', $bonus_id);
			$delete = $this->db->delete('bonus_activities');
			return $delete == true;
		}

		return false;
	}
}
