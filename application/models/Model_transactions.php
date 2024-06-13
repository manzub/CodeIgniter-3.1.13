<?php
class Model_transactions extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllTransactions()
	{
		$query = $this->db->get('transactions');
		$result = $query->result_array();
		return $result;
	}

	public function getTransactionById($trnx_id = null)
	{
		if ($trnx_id != null) {
			$query = $this->db->get_where('transactions', array('id' => $trnx_id));
			$result = $query->result_array();
			return $result;
		}

		return array();
	}

	public function update($trnx_id = null, $data = array())
	{
		if (!empty($data)) {
			$this->db->where('id', $trnx_id);
			$update = $this->db->update('transctions', $data);
			return ($update == true) ? true : false;
		}

		return false;
	}

	public function remove($trnx_id = null)
	{
		if ($trnx_id != null) {
			$this->db->where('id', $trnx_id);
			$delete = $this->db->delete('transactions');
			return $delete == true;
		}

		return false;
	}
}
