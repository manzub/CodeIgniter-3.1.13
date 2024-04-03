<?php

class Model_surveys extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCompletedSurveysByUserId($user_id = null)
	{
		// get all items from db completed by current_user
		if ($user_id != null) {
			$this->db->select('survey_id');
			$query = $this->db->get_where('surveys_completed', array('completed_by' => $user_id));
			return $query->result_array();
		}
	}

	public function getCompletedSurveysBySlug($user_id = null, $slug = null)
	{
		if ($user_id != null) {
			$query = $this->db->get_where('surveys_completed', array('completed_by' => $user_id, 'survey_slug' => $slug));
			return $query->result_array();
		}
	}

	public function getSurveys()
	{
		$query = $this->db->get('survey_meta');
		return $query->result_array();
	}

	public function getAvailableSurveys($group_name = null, $user_id = null, $is_page = false, $page = 0, $per_page = 5)
	{
		// select all from survey_meta where survey_id not in surveys_completed
		if ($user_id != null) {
			if ($is_page) {
				$this->db->limit($per_page, ($per_page * $page));
			}

			$this->db->order_by('id', 'RANDOM');
			$query = $this->db->get_where('survey_meta', array('status' => 'available')); // default query

			if (!in_array($group_name, array('guest'))) { // members with not access to complete survey
				$completed_surveys = array();

				foreach ($this->getCompletedSurveysByUserId($user_id) as $item) {
					array_push($completed_surveys, $item['survey_id']);
				}

				if (count($completed_surveys) !== 0) {
					$available_items = array();
					$results = $query->result_array();
					foreach ($results as $key => $sv_item) {
						if (!in_array($sv_item['id'], $completed_surveys)) {
							array_push($available_items, $sv_item);
						}
					}
					return $available_items;
				}
			}
			return $query->result_array();
		}
	}

	public function getSurveyBySlug($slug = null)
	{
		if ($slug != null) {
			$query = $this->db->get_where('survey_meta', array('slug' => $slug));
			return $query->result_array();
		}
	}

	public function getSurveyQuestionsById($slug = null)
	{
		if ($slug != null) {
			$query = $this->db->get_where('sv_questions', array('survey_slug' => $slug));
			return $query->result_array();
		}
	}

	public function getSurveyQuestionById($question_id = null)
	{
		if ($question_id != null) {
			$query = $this->db->get_where('sv_questions', array('id' => $question_id));
			return $query->result_array();
		}
	}

	public function getSurveyOptions($question_id = null)
	{
		if ($question_id != null) {
			$query = $this->db->get_where('sv_quest_options', array('sv_quest_id' => $question_id));
			return $query->result_array();
		}
	}

	public function updateCompletedSurvey($data, $id)
	{
		if ($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('surveys_completed', $data);
			return ($update == true) ? true : false;
		}
	}

	public function createCompleted($data)
	{
		if ($data) {
			$insert = $this->db->insert('surveys_completed', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function edit($survey_id = null, $data = array())
	{
		if ($survey_id != null) {
			$this->db->where('id', $survey_id);
			$update = $this->db->update('survey_meta', $data);

			return ($update == true) ? true : false;	
		}
	}
}
