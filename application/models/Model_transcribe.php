<?php

class Model_transcribe extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCompletedItemById($comp_id = null) {
		if ($comp_id != null) {
			$query = $this->db->get_where('transcribe_completed', array('id' => $comp_id));
			$result = $query->row_array();
			return $result;
		}

		return array();
	}

	public function getCompletedByTranscribeId($user_id = null, $transcribe_id = null)
	{
		if ($transcribe_id != null) {
			$arr1 = array();
			if ($user_id != null) {
				$arr1 = array('completed_by' => $user_id);
			}
			$query = $this->db->get_where('transcribe_completed', array_merge($arr1, array('transcribe_id' => $transcribe_id)));
			$result = $query->result_array();
			return $result;
		}

		return array();
	}


	public function getCompletedAll()
	{
		$query = $this->db->get('transcribe_completed');
		$result = $query->result_array();
		return $result;
	}

	public function getCompletedAvailable()
	{
		$query = $this->db->get_where('transcribe_completed', array('mod_status' => 'pending'));
		$result = $query->result_array();
		return $result;
	}

	public function getAllTranscribeItems()
	{
		$query = $this->db->get('transcribe_av');
		$result = $query->result_array();

		return $result;
	}

	public function getTranscribeItemsCreatedBy($user_id = null)
	{
		if ($user_id != null) {
			$query = $this->db->get_where('transcribe_av', array('created_by' => $user_id));
			$result = $query->result_array();

			return $result;
		}
	}

	public function getTranscribeItemById($transcribe_id = null)
	{
		if ($transcribe_id != null) {
			$sql = "SELECT * FROM transcribe_av INNER JOIN transcribe_av_meta ON transcribe_av.id = transcribe_av_meta.transcribe_id WHERE transcribe_av.id = ?";
			$query = $this->db->query($sql, array($transcribe_id));
			return $query->row_array();
		}

		return array();
	}

	public function getTranscribeItemBySlug($slug = null)
	{
		if ($slug != null) {
			$sql = "SELECT * FROM transcribe_av INNER JOIN transcribe_av_meta ON transcribe_av.id = transcribe_av_meta.transcribe_id WHERE `slug` = ?";
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
			$sql = "SELECT * FROM transcribe_av INNER JOIN transcribe_av_meta ON transcribe_av.id = transcribe_av_meta.transcribe_id WHERE `status` = ? ORDER BY rand()";
			$sql .= $is_page ? " LIMIT " . $per_page . " OFFSET " . ($per_page * $page) . "" : "";
			$query = $this->db->query($sql, array('available'));
			$result = $query->result_array();

			// get each transcribe item and compare global limit
			foreach ($result as $key => $item) {
				// check global limit was set or expired
				if (($item['global_limit'] == NULL) || intval($item['global_limit']) > 0) { //item is still valid and hasn't globally expired
					// check if user not guest or mod
					// if (!in_array($group_name, array('guest'))) {
					// get completed items and check if user already completed
					$completed_items = $this->getCompletedByTranscribeId($user_id, $item['id']);
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

	public function completeItem($data = array())
	{
		if (!empty($data)) {
			$created = $this->db->insert('transcribe_completed', $data);
			return ($created == true) ? true : false;
		}

		return false;
	}

	public function updateCompletedItem($comp_id, $data = array())
	{
		if ($data && $comp_id) {
			$this->db->where('id', $comp_id);
			$update = $this->db->update('transcribe_completed', $data);
			return ($update == true) ? true : false;
		}
	}

	public function createTranscribeItem($user_id = null, $data = array())
	{
		if (!empty($data) && $user_id != null) {
			// save to db and return created item
			$this->db->set(array_merge(array('created_by' => $user_id), $data));
			$this->db->insert('transcribe_av');
			$insert_id = $this->db->insert_id();

			return $insert_id;
		}

		return false;
	}

	public function saveTranscribeItemFiles($data = array())
	{
		if (!empty($data)) {
			$this->db->set($data);
			$this->db->insert('transcribe_av_meta');
			$insert_id = $this->db->insert_id();

			return $insert_id;
		}

		return false;
	}

	public function updateTranscribeItem($transcribe_id = null, $data = array())
	{
		if (!empty($data)) {
			$this->db->where('id', $transcribe_id);
			$update = $this->db->update('transcribe_av', $data);
			return ($update == true) ? true : false;
		}

		return false;
	}

	public function updateTranscribeItemFiles($transcribe_id = null, $data = array())
	{
		if (!empty($data)) {
			$this->db->where('transcribe_id', $transcribe_id);
			$update = $this->db->update('transcribe_av_meta', $data);
			return ($update == true) ? true : false;
		}

		return false;
	}

	public function removeTranscribeItem($id = null)
	{
		if ($id != null) {
			// remove files
			$this->removeTranscribeItemFiles($id);
			$this->db->where('id', $id);
			$delete = $this->db->delete('transcribe_av');
			return $delete == true;
		}

		return false;
	}

	public function removeTranscribeItemFiles($transcribe_id = null): bool
	{
		if ($transcribe_id != null) {
			$this->db->where('transcribe_id', $transcribe_id);
			$delete = $this->db->delete('transcribe_av_meta');
			return $delete == true;
		}

		return false;
	}
}
