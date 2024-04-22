<?php
class Model_categories extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCategoryById($cat_id = null) {
		if ($cat_id != null) {
			$query = $this->db->get_where('categories', array('id' => $cat_id));
			$result = $query->row_array();
			return $result;
		}

		return array();
	}
}
