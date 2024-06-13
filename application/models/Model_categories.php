<?php
class Model_categories extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCategoryById($cat_id = null)
	{
		if ($cat_id != null) {
			$query = $this->db->get_where('categories', array('id' => $cat_id));
			$result = $query->row_array();
			return $result;
		}

		return array();
	}

	public function getAllCategories()
	{
		$query = $this->db->get('categories');
		$result = $query->result_array();
		return $result;
	}

	public function create($data = array())
	{
		if (!empty($data)) {
			$insert = $this->db->insert('categories', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('categories', $data);
			return ($update == true) ? true : false;
		}
	}

	public function removeCategory($cat_id = null)
	{
		if ($cat_id != null) {
			$this->db->where('id', $cat_id);
			$delete = $this->db->delete('categories');
			return $delete == true;
		}

		return false;
	}
}
