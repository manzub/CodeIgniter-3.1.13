<?php

class Model_users extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getUserById($user_id)
	{
		if ($user_id) {
			$sql = "SELECT * FROM users INNER JOIN users_meta ON users.id = users_meta.user_id WHERE users.id = ?";
			$query = $this->db->query($sql, array($user_id));
			$result = $query->row_array();

			return $result;
		}
	}

	public function getUserByRefCode($ref_code = null)
	{
		if ($ref_code) {
			$query = $this->db->get_where('users', array('ref_code' => $ref_code));
			$result = $query->row_array();

			return $result;
		}
	}

	public function create($data = array())
	{
		if ($data) {
			$create = $this->db->insert('users', $data);

			$user_id = $this->db->insert_id();

			$users_meta = $this->db->insert('users_meta', array('user_id' => $user_id));

			return ($create == true && $users_meta) ? $user_id : false;
		}
	}

	public function update($id = null, $data = array())
	{
		$this->db->where('id', $id);
		$update = $this->db->update('users', $data);

		return ($update == true) ? true : false;
	}

	// misc functions
	public function logClaimedReward($user_id = null, $data = array())
	{
		if ($user_id != null) {
			$this->db->insert('claims_rewards', $data);
		}
	}

	public function getUserRewardByCond($user_id = null, $cond = array())
	{
		if ($user_id != null) {
			$this->db->limit(5);
			$this->db->order_by('id', 'DESC');
			$query = $this->db->get_where('claims_rewards', array_merge(array('user_id' => $user_id), $cond));
			$result = $query->result_array();
			return $result;
		}

		return false;
	}

	public function getUserTotalRewardsToday($user_id)
	{
		$sql = "SELECT SUM(reward_earned) AS total_earned FROM `claims_rewards` WHERE user_id = " . $user_id . " AND last_modified >= CURRENT_DATE
		AND last_modified < CURRENT_DATE + INTERVAL 1 DAY";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function getUserMetaById($user_id = null)
	{
		if ($user_id != null) {
			$query = $this->db->get_where('users_meta', array('user_id' => $user_id));
			return $query->row_array();
		}
	}

	public function getUserAccountsById($user_id = null)
	{
		if ($user_id != null) {
			$query = $this->db->get_where('users_account', array('user_id' => $user_id));
			return $query->result_array();
		}
	}

	public function getUserRewardsBalance($user_id)
	{
		if ($user_id != null) {
			$sql = "SELECT SUM(reward_earned) AS total_rewards FROM claims_rewards WHERE user_id = ?";
			$query = $this->db->query($sql, array($user_id));
			return $query->row_array();
		}
	}

	public function updateMetaById($user_id = null, $data = array())
	{
		$this->db->where('user_id', $user_id);
		$update = $this->db->update('users_meta', $data);

		return ($update == true) ? true : false;
	}

	public function requestCoins($user_id = null, $data = array())
	{
		if ($user_id != null && !empty($data)) {
			$insert = $this->db->insert('transactions', array_merge(array('created_by' => $user_id), $data));
			return $insert == true ? true : false;
		}
		return false;
	}

	public function insertUserAccount($data = array())
	{
		if (!empty($data)) {
			$inserted = $this->db->insert('users_account', $data);
			return $inserted == true ? true : false;
		}
	}

	public function unlinkAccountByUserId($user_id = null, $account_type = null)
	{
		if ($user_id && $account_type) {
			$cond = array('user_id' => $user_id, 'type' => $account_type);
			$this->db->where($cond);
			$deleted = $this->db->delete('users_account');
			return ($deleted == true) ? true : false;
		}
	}
}
