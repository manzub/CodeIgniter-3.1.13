<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Surveys extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_surveys');
		$this->load->model('model_users');
		$this->load->model('model_config');
		$this->load->model('model_logs');
		$this->data['title'] = "Surveys | SurveyMonkey";
		$this->data['per_page'] = 5;
	}

	public function fetchSurveyData($slug = null)
	{
		$user_id = $this->session->userdata('id');;

		$result = array('data' => array());
		if ($user_id != null) {
			$survey_item = $this->model_surveys->getSurveyItemBySlug($slug);
			if (!empty($survey_item)) {
				// check if item already completed;
				$last_session = null; // last completed session
				$completed_item = $this->model_surveys->getCompletedBySurveyId($survey_item['id']);
				if (!empty($completed_item)) { // if user has completed item
					if (count($completed_item) >= $survey_item['limit_per_user']) {
						redirect('surveys', 'refresh');
					}
					// if hasnt completed
					// if hasnt last session.
					$last_comp_item = $completed_item[count($completed_item) - 1];
					if (count(unserialize($last_comp_item['questions'])) >= $survey_item['no_questions']) {
						// another check to make sure item wasnt incorrectly completed
						if ($last_comp_item['status'] != 'completed') {
							$last_session = $last_comp_item;
						} else {
							redirect('surveys/completed/' . $survey_item['slug'], 'refresh');
						}
					}
				}

				// todo: get questions and options
				// compare last session
				// construct html
				$sv_questions = $this->model_surveys->getSurveyQuestionsById($survey_item['id']);
				if (!empty($sv_questions)) {
					foreach ($sv_questions as $key => $sv_question) {
						if ($last_session != null) {
							if (array_key_exists($sv_question['id'], $last_session['questions'])) {
								continue;
							}
						}
						// get options
						$input_type = $sv_question['option_style'] == 'single' ? 'radio' : 'checkbox';
						$options_arr = array();
						$sv_quest_options = $this->model_surveys->getSurveyOptionsByQuestionId($sv_question['id']);
						foreach ($sv_quest_options as $key => $option) {
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
							'survey_id' => $survey_item['id'],
							'question_html' => '<h2 style="font-weight: bold;font-size:14px">' . $sv_question['question_text'] . '</h2>',
							'options' => $options_arr
						));
					}
				} else {
					redirect('surveys', 'refresh');
				}
			}
		}

		echo json_encode($result);
	}

	// TODO: on each complete page complete as guest user
	public function completeQuestion($question_id = null)
	{
		$result = array('status' => 0);
		$user_id = $this->session->userdata('id');

		if ($question_id != null) {
			$x = $this->model_surveys->getSurveyOptionsByQuestionId($question_id);
			if (!empty($x)) {
				$survey_item = $this->model_surveys->getSurveyItemBySlug($x[0]['survey_slug']);
				if (!empty($survey_item)) {
					$completed_questions = array();

					$completed_items = $this->model_surveys->getCompletedBySurveyId($user_id, $survey_item['id']);
					if (count($completed_items) <= intval($survey_item['limit_per_user'])) {
						$last_session = null;
						if (!empty($completed_items)) {
							$last_session = $completed_items[count($completed_items) - 1];
							if (!empty($last_session)) {
								if ($last_session['status'] != 'completed') {
									$completed_questions = unserialize($last_session['questions']);
								}
							}
						}


						if (!array_key_exists($question_id, $completed_questions)) {
							$options = gettype($this->input->post('sv_opt')) == 'array' ? $this->input->post('sv_opt') : array($this->input->post('sv_opt'));
							array_push($completed_questions, array($question_id => $options));

							$status = count($completed_questions) >= $survey_item['no_questions'] ? 'completed' : 'in_progress';
							$data = array('survey_slug' => $survey_item['slug'], 'survey_id' => $survey_item['id'], 'questions' => serialize($completed_questions), 'completed_by' => $user_id, 'status' => $status, 'points_earned' => '0');
							if ($last_session['status'] == 'in_progress') {
								// complete item
								$this->model_surveys->updateCompletedItem($last_session['id'], $data);
								$result['status'] = count($completed_questions) >= intval($survey_item['no_questions']) ? 2 : 1;
							} else {
								if (count($completed_items) < intval($survey_item['limit_per_user'])) {
									$this->model_surveys->completeSurveyItem($data);
									$result['status'] = count($completed_questions) >= intval($survey_item['no_questions']) ? 2 : 1;
								}
							}
							// log activity
							$activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Completed Question', 'message' => 'Well done!');
							$this->model_logs->logActivity($activity);
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
		$group_name = $this->session->userdata('group_name');

		$surveys_page = $this->model_surveys->getAvailableSurveys($group_name, $user_id, true, $page - 1, $this->data['per_page']);
		$this->data['surveys'] = $surveys_page;

		// count pages
		$available_surveys = $this->model_surveys->getAvailableSurveys($group_name, $user_id);
		$count_available_sv = count($available_surveys);
		$this->data['pages'] = round($count_available_sv / $this->data['per_page']);
		$this->data['page'] = $page;

		$this->render_template('pages/surveys/index', $this->data);
	}

	//  TODO: show alert if limit reached
	public function single($slug = null)
	{
		if ($slug == null) {
			show_404('Page Not Found');
		}

		// completeQuestion - can answer question, completeSurvey can reduce global limit
		if (in_array('completeQuestion', $this->permission)) {
			// render view
			$survey_item = $this->model_surveys->getSurveyItemBySlug($slug);
			$this->data['title'] = $survey_item['title'] . " | SurveyMonkey!";
			$this->data['survey_item'] = $survey_item;
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
		if (!in_array('earnRewards', $this->permission)) {
			$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Why Miss Out', 'message' => 'Create an account to start earning'));
			redirect('surveys', 'refresh');
		}

		if ($slug == null) {
			redirect('surveys', 'refresh');
		}

		$survey_item = $this->model_surveys->getSurveyItemBySlug($slug);
		$completed_items = $this->model_surveys->getCompletedBySurveyId($user_id, $survey_item['id']);

		if (!empty($survey_item) && !empty($completed_items)) {
			$last_comp_item = $completed_items[count($completed_items) - 1];
			if (intval($last_comp_item['points_earned']) != 0) {
				redirect('surveys', 'refresh');
			}

			// on click claim button
			$this->form_validation->set_rules('completed', 'Survey Item', 'required');
			if ($this->form_validation->run() == TRUE) {
				$points_earned = $survey_item['reward_points'];

				// check if daily activity
				$dl_list = $this->session->userdata('daily_activity');
				$dl_slug = array_map(function ($o) {
					return $o['slug'];
				}, $dl_list);
				if (!empty($dl_slug)) {
					if (in_array($survey_item['slug'], $dl_slug)) {
						$daily_activity_config = $this->model_config->getConfigByName('daily_activity_reward');
						$points_earned = intval($daily_activity_config['value']);
						$key = array_search($survey_item['slug'], $dl_slug);
						$dl_list[$key]['status'] = 'completed';
						$this->session->set_userdata('daily_activity', $dl_list);
					}
				}

				$data = array('points_earned' => $points_earned);

				$update = $this->model_surveys->updateCompletedItem($last_comp_item['id'], $data);
				if ($update) {
					// reward ref parent user
					$my_account = $this->model_users->getuserById($user_id);
					if (!in_array($my_account['referred_by'], array(null, 'NULL'))) {
						$my_referrer = $this->model_users->getUserByRefCode($my_account['referred_by']);
						// load interest config
						$reward_interest_config = $this->model_config->getConfigByName('ref_reward_interest');
						$reward_interest = intval($reward_interest_config['value']);
						$interest_earned = $points_earned / $reward_interest;

						$this->model_users->logClaimedReward($my_referrer['id'], array('user_id' => $my_referrer['id'], 'reward_earned' => $interest_earned, 'type' => 'ref_interest', 'streak' => '0'));
					}

					// reduce global limit
					if ($survey_item['global_limit'] != NULL && intval($survey_item['global_limit']) > 0) {
						$global_limit = intval($survey_item['global_limit']) - 1;
						$this->model_surveys->updateSurveyItem($survey_item['id'], array('global_limit' => $global_limit));
					}
				}

				$total_earned = round($points_earned * count($completed_items));
				$this->model_users->logClaimedReward($user_id, array('user_id' => $user_id, 'review_id' => $survey_item['id'], 'reward_earned' => $total_earned, 'type' => 'completed_activity', 'streak' => '0'));
				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $total_earned, 'title' => 'Completed'));
				// log activity
				$activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Earned Reward', 'message' => 'Congratulations! Earned' . $total_earned);
				$this->model_logs->logActivity($activity);
				redirect('surveys', 'refresh');
			}

			$this->data['title'] = "Claim your rewards - " . $survey_item['title'] . " | SurveyMonkey!";
			$this->data['survey_item'] = $survey_item;
			$this->render_template('pages/surveys/completed', $this->data);
		} else {
			// redirect("surveys", 'refresh');
		}
	}

	// admin functions

	public function admin()
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		if (in_array('manageSurvey', $this->permission) || in_array('manageActivity', $this->permission)) {
			$this->render_admin('pages/admin/activities/surveys/index', $this->data);
		} else {
			redirect('dashboard', 'refresh');
		}
	}

	public function readSurveyItems()
	{
		$user_id = $this->session->userdata('id');
		$group_name = $this->session->userdata('group_name');

		if ($group_name == 'moderator') {
			if (!in_array('manageActivity', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		if ((strpos($group_name, 'admin') !==  false)) {
			if (!in_array('manageSurvey', $this->permission)) {
				redirect('dashboard', 'refresh');
			}
		}

		$result = array('data' => array());

		// if any admin user or mod list all, else list created by
		$items = null;
		if ((strpos($group_name, 'admin') !==  false) || $group_name == 'moderator') {
			$items = $this->model_surveys->getAllSurveyItems();
			if ($group_name == 'moderator') {
				$items = array_values(array_filter($items, function ($x) {
					return $x['status'] == 'draft';
				}));
			}
		} else {
			$items = $this->model_surveys->getSurveyItemsCreatedBy($user_id);
		}

		foreach ($items as $key => $value) {
			// buttons
			$buttons = "";

			if (in_array('manageActivity', $this->permission)) {
				if ($value['status'] == 'draft') {
					$buttons .= "<a href='" . base_url('surveys/review_item/' . $value['slug']) . "' class='btn btn-primary'><i class='fa fa-pencil'></i></a>";
				}
			} else {
				if (in_array('manageSurvey', $this->permission)) {
					if ($value['status'] == 'draft') {
						$buttons .= "<a href='" . base_url('surveys/edit/' . $value['slug']) . "' class='btn btn-primary' style='margin-right:10px'><i class='fa fa-pencil'></i></a>";
					}
					$buttons .= "<button onclick='removeFunc(" . $value['slug'] . ")' data-toggle='modal' data-target='#removeModal' class='btn btn-danger'><i class='fa fa-trash'></i></button>";
				}
			}

			$status = "<span class='label label-info'>" . strtoupper($value['status']) . "</span>";

			$result['data'][$key] = array(
				$value['title'],
				$value['no_questions'],
				$value['global_limit'],
				$value['reward_points'] . "SB",
				$status,
				$buttons
			);
		}

		echo json_encode($result);
	}

	public function create()
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		// form to create new survey
		$this->render_admin('pages/admin/activities/surveys/create', $this->data);
	}
}
