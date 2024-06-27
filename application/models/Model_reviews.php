<?php

class Model_reviews extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCompletedByReviewId($user_id = null, $review_id = null)
	{
		if ($review_id != null) {
			$arr1 = array();
			if ($user_id != null) {
				$arr1 = array('completed_by' => $user_id);
			}
			$query = $this->db->get_where('reviews_completed', array_merge($arr1, array('review_id' => $review_id)));
			$result = $query->result_array();
			return $result;
		}

		return array();
	}

	public function getCompletedItemById($comp_id = null) {
		if ($comp_id != null) {
			$query = $this->db->get_where('reviews_completed', array('id' => $comp_id));
			$result = $query->row_array();
			return $result;
		}

		return array();
	}

	public function getCompletedAll() {
		$query = $this->db->get('reviews_completed');
		$result = $query->result_array();
		return $result;
	}

	public function getCompletedAvailable() {
		$query = $this->db->get_where('reviews_completed', array('status' => 'pending'));
		$result = $query->result_array();
		return $result;
	}

	public function getReviewItemById($review_id = null) {
		if ($review_id != null) {
			$sql = "SELECT * FROM review_vd INNER JOIN review_vd_meta ON review_vd.id = review_vd_meta.review_id WHERE review_vd.id = ?";
			$query = $this->db->query($sql, array($review_id));
			$result = $query->row_array();
			return $result;
		}

		return array();
	}

	public function getReviewItemBySlug($slug = null)
	{
		if ($slug != null) {
			$sql = "SELECT * FROM review_vd INNER JOIN review_vd_meta ON review_vd.id = review_vd_meta.review_id WHERE `slug` = ?";
			$query = $this->db->query($sql, array($slug));
			return $query->row_array();
		}

		return array();
	}

	public function getAllReviewItems()
	{
		$query = $this->db->get('review_vd');
		$result = $query->result_array();

		return $result;
	}

	public function getReviewItemsCreatedBy($user_id = null)
	{
		if ($user_id != null) {
			$query = $this->db->get_where('review_vd', array('created_by' => $user_id));
			$result = $query->result_array();

			return $result;
		}
	}

	public function getMyAvailableActivities($group_name = null, $user_id = null, $is_page = false, $page = 0, $per_page = 5)
	{
		$available_items = array();
		if ($user_id != null) {
			// TODO: test pagination later
			$sql = "SELECT * FROM review_vd INNER JOIN review_vd_meta ON review_vd.id = review_vd_meta.review_id WHERE `status` = ? ORDER BY rand()";
			$sql .= $is_page ? " LIMIT " . $per_page . " OFFSET " . ($per_page * $page) . "" : "";
			$query = $this->db->query($sql, array('available'));
			$result = $query->result_array();

			// loop each review item
			foreach ($result as $key => $item) {
				// check global limit was set or expired
				if (($item['global_limit'] == NULL) || intval($item['global_limit']) > 0) {
					// check if user not guest or mod
					// if (!in_array($group_name, array('guest'))) {
					// get completed items and check if user already completed
					$completed_items = $this->getCompletedByReviewId($user_id, $item['id']);
					if (!empty($completed_items)) { // if user has completed item already
						// compare completed_items.length with limits_per_user
						if (count($completed_items) >= $item['limit_per_user']) { // if already > limit_per_user => next item
							continue;
						}
					}

					$available_items[$key] = $item;
					// }
				}
			}
		}

		return $available_items;
	}

	public function updateReviewItem($review_id = null, $data = array())
	{
		if (!empty($data)) {
			$this->db->where('id', $review_id);
			$update = $this->db->update('review_vd', $data);
			return ($update == true) ? true : false;
		}

		return false;
	}

	public function updateReviewItemFiles($review_id = null, $data = array())
	{
		if (!empty($data)) {
			$this->db->where('review_id', $review_id);
			$update = $this->db->update('review_vd_meta', $data);
			return ($update == true) ? true : false;
		}

		return false;
	}

	public function completeItem($data = array())
	{
		if (!empty($data)) {
			$created = $this->db->insert('reviews_completed', $data);
			return ($created == true) ? true : false;
		}

		return false;
	}

	public function updateCompletedItem($comp_id, $data = array())
	{
		if ($data && $comp_id) {
			$this->db->where('id', $comp_id);
			$update = $this->db->update('reviews_completed', $data);
			return ($update == true) ? true : false;
		}
	}

	// admin function

	public function createReviewItem($user_id, $data)
	{
		if (!empty($data) && $user_id != null) {
			// save to db and return created item
			$this->db->set(array_merge(array('created_by' => $user_id), $data));
			$this->db->insert('review_vd');
			$insert_id = $this->db->insert_id();

			return $insert_id;
		}

		return false;
	}

	public function saveReviewItemFiles($data)
	{
		if (!empty($data)) {
			$this->db->set($data);
			$this->db->insert('review_vd_meta');
			$insert_id = $this->db->insert_id();

			return $insert_id;
		}

		return false;
	}

	public function removeReviewItem($id = null)
	{
		if ($id != null) {
			// remove from review_vd_meta then review_vd
			$this->removeReviewItemFiles($id);
			$this->db->wehere('id', $id);
			$delete = $this->db->delete('review_vd');
			return $delete == true;
		}

		return false;
	}

	public function removeReviewItemFiles($review_id = null): bool
	{
		if ($review_id != null) {
			$this->db->where('review_id', $review_id);
			$delete = $this->db->delete('review_vd_meta');
			return $delete == true;
		}

		return false;
	}
}
