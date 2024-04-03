<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Surveys extends Main_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_surveys');
		$this->load->model('model_users');
		$this->data['title'] = "Surveys | SurveyMonkey";
		$this->data['per_page'] = 5;
	}

	public function fetchSurveyData($survey_slug = null)
	{
		// current user
		$user_id = $this->session->userdata('id');;

		$result = array('data' => array(), 'status' => 0);
		if ($survey_slug != null) {
			$valid_survey = $this->model_surveys->getSurveyBySlug($survey_slug)[0];

			if ($valid_survey) {
				$completed_questions = array();
				// get survey questions
				// get completed questions - array('quest_id'=>array('option_id','option_id'))
				// get all survey questions omit results in completed_questions
				// a:14:{i:0;s:10:"createUser";i:1;s:10:"updateUser";i:2;s:8:"viewUser";i:3;s:10:"deleteUser";i:4;s:11:"createGroup";i:5;s:11:"updateGroup";i:6;s:9:"viewGroup";i:7;s:11:"deleteGroup";i:8;s:11:"createStore";i:9;s:13:"createProduct";i:10;s:11:"createOrder";i:11;s:13:"updateCompany";i:12;s:11:"viewProfile";i:13;s:13:"updateSetting";}
				$completed_survey = $this->model_surveys->getCompletedSurveysBySlug($user_id, $valid_survey['slug']);
				if (!empty($completed_survey)) {
					$completed_questions = unserialize($completed_survey[0]['questions']);
				}

				$survey_questions = $this->model_surveys->getSurveyQuestionsById($valid_survey['slug']);
				foreach ($survey_questions as $sv_key => $sv_question) {
					if (array_key_exists($sv_question['id'], $completed_questions)) {
						continue;
					}
					$input_type = $sv_question['option_style'] == 'single' ? 'radio' : 'checkbox';
					// get options
					// TODO: build html for checkbox
					$options_arr = array();
					$question_options = $this->model_surveys->getSurveyOptions($sv_question['id']);
					foreach ($question_options as $key => $option) {
						# code...
						$options_arr[$key]['id'] = $option['id'];
						$options_arr[$key]['option_text'] = $option['option_text'];
						$options_arr[$key]['option_html'] = '<div class="col-lg-12">
							<div class="input-group" style="border: 1px solid #e3e3e3;">
								<span class="input-group-addon" style="background-color: inherit;border:none">
									<input id="sv_opt" name="quest_id-' . $sv_question['id'] . '" value="' . $option['id'] . '" type="' . $input_type . '" aria-label="...">
								</span>
								<p style="margin-left:10px;line-height: 2em;">' . $option['option_text'] . '</p>
							</div>
						</div>';
					}

					array_push($result['data'], array(
						'id' => $sv_question['id'],
						'question_text' => $sv_question['question_text'],
						'survey_id' => $valid_survey['id'],
						'question_html' => '<h2 style="font-weight: bold;font-size:14px">' . $sv_question['question_text'] . '</h2>',
						'options' => $options_arr
					));
				}
			}
			$result['status'] = 1;
		}
		echo json_encode($result);
	}

	public function completeQuestion($question_id = null)
	{
		// TODO correct user roles
		$result = array('status' => 0);

		// current user
		$user_id = $this->session->userdata('id');;

		if ($question_id != null) {
			$question_item = $this->model_surveys->getSurveyQuestionById($question_id)[0];
			$survey_item = $this->model_surveys->getSurveyBySlug($question_item['survey_slug'])[0];
			$survey_slug = $survey_item['slug'];
			if ($survey_slug != null) {
				$completed_questions = array();
				// check already started question
				$completed_item = $this->model_surveys->getCompletedSurveysBySlug($user_id, $survey_slug);
				if (!empty($completed_item)) {
					if ($completed_item[0]['status'] == 'completed') {
						$result['status'] = 2;
						// update survey_item
						if (in_array('completeSurvey', $this->permission)) {
							$global_limit = intval($survey_item['limit_per_user']);
							$sv_status = $survey_item['status'];
							if ($global_limit > 0) {
								# item has a global limit - reduce global limit and change status
								$global_limit = $global_limit - 1;
								$sv_status = $global_limit <= 0 ? 'expired' : 'available';
							}

							$data = array('limit_per_user' => $global_limit, 'status' => $status);
							$this->model_surveys->edit($survey_item['id'], $data);
						}

						echo json_encode($result);
						return;
					}

					$completed_questions = unserialize($completed_item[0]['questions']);
				}

				if (!array_key_exists($question_id, $completed_questions)) {
					// get selected options
					$options = $this->input->post('sv_opt');
					$completed_questions[$question_id] = gettype($options) == 'array' ? $options : array($options);

					if (!empty($completed_item)) {
						// update database
						$status = $survey_item['no_questions'] > count($completed_questions) ? 'in_progress' : 'completed';
						$data = array('questions' => serialize($completed_questions), 'status' => $status);
						$update = $this->model_surveys->updateCompletedSurvey($data, $completed_item[0]['id']);
						if ($update == true) {
							$result['status'] = $status == 'completed' ? 2 : 1;
						}
					} else {
						// create completed_item
						$data = array('survey_slug' => $survey_slug, 'survey_id' => $survey_item['id'], 'questions' => serialize($completed_questions), 'completed_by' => $user_id, 'status' => 'in_progress');
						$create = $this->model_surveys->createCompleted($data);
						if ($create == true) {
							$result['status'] = 1;
						}
					}
				}
			}
		}

		echo json_encode($result);
	}

	public function index($page = 1)
	{
		// current user - guest
		$user_id = $this->session->userdata('id');
		$group_name = $this->session->userdata['group_name'];

		$surveys_page = $this->model_surveys->getAvailableSurveys($group_name, $user_id, true, $page - 1, $this->data['per_page']);
		$this->data['surveys'] = $surveys_page;

		// count pages
		$available_surveys = $this->model_surveys->getAvailableSurveys($group_name, $user_id);
		$count_available_sv = count($available_surveys);
		$this->data['pages'] = round($count_available_sv / $this->data['per_page']);
		$this->data['page'] = $page;

		$this->render_template('pages/surveys/index', $this->data);
	}

	public function single($slug = null)
	{
		if ($slug == null) {
			show_404('Page Not Found');
		}

		// completeQuestion - can answer question, completeSurvey can reduce global limit
		if (in_array('completeQuestion', $this->permission)) {
			// render view
			$survey_item = $this->model_surveys->getSurveyBySlug($slug);
			$this->data['title'] = $survey_item[0]['title'] . " | SurveyMonkey!";
			$this->data['survey_item'] = $survey_item[0];
			$this->render_template('pages/surveys/single', $this->data);
		} else {
			$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'message' => 'Create an account to start earning rewards.', 'title' => 'Why Miss Out?'));
			redirect('surveys', 'refresh');
		}
	}

	public function completed($slug = null)
	{
		// current user
		$user_id = $this->session->userdata('id');

		if ($slug == null) {
			redirect('surveys', 'refresh');
		}

		if (in_array('earnRewards', $this->permission)) {
			$survey_item = $this->model_surveys->getSurveyBySlug($slug);
			$completed_item = $this->model_surveys->getCompletedSurveysBySlug($user_id, $survey_item[0]['slug']);

			if (empty($survey_item) || empty($completed_item) || $completed_item[0]['points_earned'] != '0') {
				redirect('surveys', 'refresh');
			}

			$this->form_validation->set_rules('completed', 'Survey Item', 'required');

			if ($this->form_validation->run() == TRUE) {
				$data = array('points_earned' => $survey_item[0]['reward_points']);
				$update = $this->model_surveys->updateCompletedSurvey($data, $completed_item[0]['id']);
				if ($update == true) {
					$this->model_users->logClaimedReward($user_id, array('user_id' => $user_id, 'survey_id' => $survey_item[0]['id'], 'reward_earned' => $survey_item[0]['reward_points'], 'type' => 'completed_activity', 'streak' => '0'));
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned '.$survey_item[0]['reward_points'], 'title' => 'Completed'));
					redirect('surveys/index', 'refresh');
				}
			}

			$this->data['title'] = "Claim your rewards - " . $survey_item[0]['title'] . " | SurveyMonkey!";
			$this->data['survey_item'] = $survey_item[0];
			$this->render_template('pages/surveys/completed', $this->data);
		} else {
			$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'message' => 'Create an account to start earning rewards.', 'title' => 'Why Miss Out?'));
			redirect('surveys', 'refresh');
		}
	}
}
