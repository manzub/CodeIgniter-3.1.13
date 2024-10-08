<?php

class Model_groups extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getGroupByGroupName($group_name)
	{
		if ($group_name) {
			$query = $this->db->get_where('groups', array('group_name' => $group_name));
			return $query->row_array();
		}
	}

	public function getUserByGroupName($group_name)
	{
		$sql = "SELECT * FROM groups INNER JOIN users ON users.user_group = groups.id WHERE groups.group_name = ?";
		$query = $this->db->query($sql, array($group_name));
		$results = $query->result_array();

		$rand_guest = $results[rand(0, count($results) - 1)];
		return $rand_guest;
	}

	public function getUserGroupByUserId($user_id)
	{
		$sql = "SELECT * FROM users 
		INNER JOIN groups ON groups.id = users.user_group 
		WHERE users.id = ?";
		$query = $this->db->query($sql, array($user_id));
		$result = $query->row_array();

		return $result;
	}

	public function getGroupData($groupId = null)
	{
		if ($groupId) {
			$sql = "SELECT * FROM groups WHERE id = ?";
			$query = $this->db->query($sql, array($groupId));
			return $query->row_array();
		}

		$query = $this->db->get('groups');
		return $query->result_array();
	}

	public function create($data = array())
	{
		if (!empty($data)) {
			$create = $this->db->insert('groups', $data);
			return ($create == true) ? true : false;
		}
	}

	public function edit($data, $id)
	{
		$this->db->where('id', $id);
		$update = $this->db->update('groups', $data);
		return ($update == true) ? true : false;
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$delete = $this->db->delete('groups');
		return ($delete == true) ? true : false;
	}

	public function existInUserGroup($id) {
		$sql = "SELECT * FROM users WHERE user_group = ?";
		$query = $this->db->query($sql, array($id));
		return ($query->num_rows() >= 1) ? true : false;
	}
}
