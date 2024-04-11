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
			$query = $this->db->get_where('reviews_completed', array('completed_by' => $user_id, 'review_id' => $review_id));
			$result = $query->result_array();
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

	public function getMyAvailableActivities($group_name = null, $user_id = null, $is_page = false, $page = 0, $per_page = 5)
	{
		$available_items = array();
		if ($user_id != null) {
			// TODO: test pagination later
			if ($is_page) {
				$this->db->limit($per_page, ($per_page * $page));
			}

			$this->db->order_by('id', 'RANDOM');
			$sql = "SELECT * FROM review_vd INNER JOIN review_vd_meta ON review_vd.id = review_vd_meta.review_id WHERE `status` = ?";
			$query = $this->db->query($sql, array('available'));
			$result = $query->result_array();

			// loop each review item
			foreach ($result as $key => $item) {
				// check global limit was set or expired
				if (($item['global_limit'] != NULL) && intval($item['global_limit']) > 0) {
					// check if user not guest or mod
					if (!in_array($group_name, array('guest'))) {
						// get completed items and check if user already completed
						$completed_items = $this->getCompletedByReviewId($user_id, $item['id']);
						if (!empty($completed_items)) { // if user has completed item already
							// compare completed_items.length with limits_per_user
							if (count($completed_items) >= $item['limits_per_user']) { // if already > limit_per_user => next item
								continue;
							}
						}
					}

					$available_items[$key] = $item;
				}
			}
		}

		return $available_items;
	}

	public function getAvailableBonusActivities() {
		// TODO: get all activities and cond bonus
	}

	public function completeItem($data = array())
	{
		if (!empty($data)) {
			$created = $this->db->insert('reviews_completed', $data);
			return ($created == true) ? true : false;
		}

		return false;
	}

	public function updateCompletedItem($review_id, $data = array())
	{
		if ($data && $id) {
			$this->db->where('id', $review_id);
			$update = $this->db->update('reviews_completed', $data);
			return ($update == true) ? true : false;
		}
	}
}
