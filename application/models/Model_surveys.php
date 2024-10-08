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
			$arr1 = array();
			$user_id != null && $arr1 = array('completed_by' => $user_id);

			$query = $this->db->get_where('surveys_completed', array_merge($arr1, array('survey_id' => $survey_id)));
			return $query->result_array();
		}

		return array();
	}

	public function getAllSurveyItems()
	{
		$query = $this->db->get('survey_meta');
		$result = $query->result_array();
		return $result;
	}

	public function getSurveyItemsCreatedBy($user_id = null)
	{
		$query = $this->db->get_where('survey_meta', array('created_by' => $user_id));
		$result = $query->result_array();
		return $result;
	}

	public function getSurveyItemBySlug($slug = null)
	{
		if ($slug != null) {
			$query = $this->db->get_where('survey_meta', array('slug' => $slug));
			$result = $query->row_array();
			return $result;
		}
	}

	public function getSurveyItemById($survey_id = null)
	{
		if ($survey_id != null) {
			$query = $this->db->get_where('survey_meta', array('id' => $survey_id));
			$result = $query->row_array();
			return $result;
		}

		return array();
	}

	public function getSurveyQuestionByQuestId($quest_id)
	{
		if ($quest_id != null) {
			$query = $this->db->get_where('sv_questions', array('id', $quest_id));
			$result = $query->row_array();
			return $result;
		}

		return array();
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

	// rewrite surveys module
	public function getAvailableSurveys($group_name = null, $user_id = null, $is_page = false, $page = 0, $per_page = 5)
	{
		$available_items = array();
		if ($user_id != null) {
			// test pagination later
			$sql = "SELECT * FROM survey_meta WHERE `status` = ? ORDER BY rand()";
			$sql .= $is_page ? " LIMIT " . $per_page . " OFFSET " . ($per_page * $page) . "" : "";
			$query = $this->db->query($sql, array('available'));
			$result = $query->result_array();

			// get each survey item and check global_limit
			$count = 0;
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
					$available_items[$count] = $item;
					$count++;
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

	// admin function
	public function createSurveyItem($user_id, $data)
	{
		if (!empty($data) && $user_id != null) {
			$this->db->set(array_merge(array('created_by' => $user_id), $data));
			$this->db->insert('survey_meta');
			$insert_id = $this->db->insert_id();

			return $insert_id;
		}

		return false;
	}

	public function createSurveyQuestion($data)
	{
		if (!empty($data)) {
			$this->db->set($data);
			$this->db->insert('sv_questions');
			$insert_id = $this->db->insert_id();
			return $insert_id;
		}

		return false;
	}

	public function createSurveyOption($data)
	{
		if (!empty($data)) {
			$this->db->set($data);
			$this->db->insert('sv_quest_options');
			$insert_id = $this->db->insert_id();
			return $insert_id;
		}

		return false;
	}

	public function removeSurveyItem($survey_id = null)
	{
		if ($survey_id != null) {
			// remove options, remove questions then item
			$this->removeSurveyOptions($survey_id);
			$this->removeSurveyQuestions($survey_id);
			$this->db->where('id', $survey_id);
			$delete = $this->db->delete('survey_meta');
			return $delete == true;
		}

		return false;
	}

	public function removeSurveyOptions($survey_id = null)
	{
		if ($survey_id != null) {
			$this->db->where('survey_id', $survey_id);
			$delete = $this->db->delete('sv_quest_options');
			return $delete == true;
		}

		return false;
	}

	public function removeSurveyOptionsByQuestId($question_id)
	{
		if ($question_id != null) {
			$this->db->where('sv_quest_id', $question_id);
			$delete = $this->db->delete('sv_quest_options');
			return $delete == true;
		}

		return false;
	}

	public function removeSurveyQuestions($survey_id = null)
	{
		if ($survey_id != null) {
			$this->db->where('survey_id', $survey_id);
			$delete = $this->db->delete('sv_questions');
			return $delete == true;
		}

		return false;
	}

	public function removeSurveyQuestionByQuestId($question_id)
	{
		if ($question_id != null) {
			$this->db->where('id', $question_id);
			$delete = $this->db->delete('sv_questions');
			return $delete == true;
		}

		return false;
	}
}
