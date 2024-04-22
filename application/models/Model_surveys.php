<?php

class Model_surveys extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCompletedBySurveyId($user_id = null, $survey_id = null)
	{
		// get all items from db completed by current_user
		if ($survey_id != null) {
			$query = $this->db->get_where('surveys_completed', array('survey_id' => $survey_id, 'completed_by' => $user_id));
			return $query->result_array();
		}

		return array();
	}

	public function getSurveyItemBySlug($slug = null)
	{
		if ($slug != null) {
			$query = $this->db->get_where('survey_meta', array('slug' => $slug));
			$result = $query->row_array();
			return $result;
		}
	}

	public function getSurveyQuestionsById($survey_id = null)
	{
		if ($survey_id != null) {
			$sql = "SELECT * FROM sv_questions WHERE sv_questions.survey_id = ?";
			$query = $this->db->query($sql, array($survey_id));
			$result = $query->result_array();
			return $result;
		}

		return array();
	}

	public function getSurveyOptionsByQuestionId($question_id = null)
	{
		if ($question_id != null) {
			$query = $this->db->get_where('sv_quest_options', array('sv_quest_id' => $question_id));
			$result = $query->result_array();
			return $result;
		}

		return array();
	}

	// TODO: rewrite surveys module
	public function getAvailableSurveys($group_name = null, $user_id = null, $is_page = false, $page = 0, $per_page = 5)
	{
		$available_items = array();
		if ($user_id != null) {
			// TODO: test pagination later
			$sql = "SELECT * FROM survey_meta WHERE `status` = ? ORDER BY rand()";
			$sql .= $is_page ? " LIMIT " . $per_page . " OFFSET " . ($per_page * $page) . "" : "";
			$query = $this->db->query($sql, array('available'));
			$result = $query->result_array();

			// get each survey item and check global_limit
			foreach ($result as $key => $item) {
				// check global limit was set
				if (($item['global_limit'] == NULL) || intval($item['global_limit']) > 0) { //item is still valid and hasn't globally expired
					// check if user not guest or mod
					// if (!in_array($group_name, array('guest', 'mod'))) {
					// get completed items and check if user already completed
					$completed_items = $this->getCompletedBySurveyId($user_id, $item['id']);
					if (!empty($completed_items)) { //check if user completed item already
						// compare completed_items.length with limits_per_user
						if (count($completed_items) >= intval($item['limit_per_user'])) { // if already > limit_per_user => next item
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

	public function updateSurveyItem($survey_id = null, $data = array())
	{
		if (!empty($data) && $survey_id) {
			$this->db->where('id', $survey_id);
			$update = $this->db->update('survey_meta', $data);
			return ($update == true) ? true : false;
		}
	}

	public function updateCompletedItem($id, $data)
	{
		if (!empty($data) && $id != null) {
			$this->db->where('id', $id);
			$update = $this->db->update('surveys_completed', $data);

			return ($update == true) ? true : false;
		}
	}

	public function completeSurveyItem($data)
	{
		if ($data) {
			$create = $this->db->insert('surveys_completed', $data);
			return ($create == true) ? true : false;
		}
	}
}
