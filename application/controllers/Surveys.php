<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Surveys extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_surveys');
		$this->load->model('model_categories');
		$this->load->model('model_users');
		$this->load->model('model_bonuses');
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
				$data = array('points_earned' => $points_earned);
				$update = $this->model_surveys->updateCompletedItem($last_comp_item['id'], $data);
				if ($update) {
					// check if bonus item
					$categories = explode(',', $survey_item['category']);
					if (in_array('1', $categories)) { // found as bonus_item
						$bonus_item = $this->model_bonuses->getBonusItemByCond(array('survey_id' => $survey_item['id']));
						$reward_points = intval($bonus_item['reward_points']);
						$points_earned =  $points_earned + $reward_points;
						$global_limit = intval($bonus_item['global_limit']) - 1;
						$status = $global_limit == 0 ? 'completed' : 'available';
						// update bonus item
						$this->model_bonuses->update($bonus_item['id'], array('global_limit' => $global_limit, 'status' => $status));
					}

					// reduce global limit
					if ($survey_item['global_limit'] != NULL && intval($survey_item['global_limit']) > 0) {
						$global_limit = intval($survey_item['global_limit']) - 1;
						$this->model_surveys->updateSurveyItem($survey_item['id'], array('global_limit' => $global_limit));
					}
				}
				// reward points
				$total_earned = round($points_earned * count($completed_items));
				$this->reward_points($points_earned, $total_earned, $survey_item['slug'], array('survey_id' => $survey_item['id'], 'type' => 'completed_activity', 'streak' => '0'));

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
					$buttons .= "<a href='" . base_url('surveys/review_item/' . $value['slug']) . "' class='btn btn-primary'><i class='fa fa-archive'></i></a>";
				}
			} else {
				if (in_array('manageSurvey', $this->permission)) {
					if ($value['status'] == 'draft') {
						$buttons .= "<a href='" . base_url('surveys/edit/' . $value['slug']) . "' class='btn btn-primary' style='margin-right:10px'><i class='fa fa-pencil'></i></a>";
					}
				}
				$buttons .= "<button onclick='removeFunc(`" . $value['slug'] . "`)' data-toggle='modal' data-target='#removeModal' class='btn btn-danger'><i class='fa fa-trash'></i></button>";
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

	public function review_item($survey_slug = null)
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');


		if ($survey_slug == null) {
			redirect('surveys/admin', 'refresh');
		}

		if (in_array('reviewActivity', $this->permission)) {
			$survey_item = $this->model_surveys->getSurveyItemBySlug($survey_slug);

			$this->form_validation->set_rules('approve_deny', 'Approve or Deny', 'required');
			if ($this->form_validation->run() == TRUE) {
				# code...
				$approve_deny = $this->input->post('approve_deny');
				$status = $approve_deny == 'deny' ? 'rejected' : 'available';

				$data = array('status' => $status);
				$update = $this->model_surveys->updateSurveyItem($survey_item['id'], $data);
				if ($update) {
					// reward points
					$reward_config = $this->model_config->getConfigByName('mod_review_reward');
					$points_earned = intval($reward_config['value']);

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

					// log claimed reward
					$this->model_users->logClaimedReward($user_id, array('user_id' => $user_id, 'review_id' => $survey_item['id'], 'reward_earned' => $points_earned, 'type' => 'mod_reward', 'streak' => '0'));
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $points_earned, 'title' => 'Completed'));
					// log activity
					$activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Reviewed Activity Item', 'message' => 'Completed Review! Earned' . $points_earned);
					$this->model_logs->logActivity($activity);
					redirect('surveys/admin', 'refresh');
				}
			}
			$cat_arr = $this->model_categories->getAllCategories();
			$sv_questions = $this->model_surveys->getSurveyQuestionsById($survey_item['id']);
			$this->data['survey_item'] = $survey_item;
			$this->data['sv_questions'] = $sv_questions;
			$this->data['categories'] = $cat_arr;
			$this->render_admin('pages/admin/activities/surveys/review_item', $this->data);
		} else {
			redirect('surveys/admin', 'refresh');
		}
	}

	public function create()
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		if (in_array('createSurvey', $this->permission)) {
			// survey_title, survey_cat[], limit_per_user, global_limit
			$this->form_validation->set_rules('survey_title', 'Survey Title', 'trim|required');
			$this->form_validation->set_rules('survey_cat[]', 'Categories', 'required');
			$this->form_validation->set_rules('limit_per_user', 'Limit Per User', 'required');
			$this->form_validation->set_rules('global_limit', 'Global Limit', 'required');

			if ($this->form_validation->run() == TRUE) {
				$categories = $this->input->post('survey_cat[]');
				$survey_title = trim($this->input->post('survey_title'));
				$limit_per_user = intval($this->input->post('limit_per_user'));
				$global_limit = intval($this->input->post('global_limit'));

				// create item as draft state
				$reward_config = $this->model_config->getConfigByName('survey_item_reward_points');
				$reward_points = intval($reward_config['value']);
				$slug = 'sv-' . rand(100, 9999) . '-' . str_replace(' ', '-', substr($survey_title, 0, 25));
				$data = array('slug' => $slug, 'title' => $survey_title, 'category' => implode(",", $categories), 'limit_per_user' => $limit_per_user, 'global_limit' => $global_limit, 'status' => 'draft', 'reward_points' => $reward_points);
				$created_item = $this->model_surveys->createSurveyItem($user_id, $data);

				if ($created_item) {
					// log activity
					$log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Item Created', 'message' => 'Created New Survey Item');
					$this->model_logs->logActivity($log);
					// flashdata
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Item Created', 'message' => 'Created New Survey Item'));
				} else {
					$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error Occurred', 'message' => 'Could not create Item'));
				}
				redirect('surveys/admin', 'refresh');
			}
		} else {
			$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'title' => 'Permission Denied', 'message' => 'No access to create survey item.'));
			redirect('surveys/admin', 'refresh');
		}

		// form to create new survey
		$cat_arr = $this->model_categories->getAllCategories();
		$this->data['categories'] = $cat_arr;
		$this->render_admin('pages/admin/activities/surveys/create', $this->data);
	}

	public function edit($survey_slug = null)
	{
		$user_id = $this->session->userdata('id');

		if ($survey_slug == null) {
			redirect('surveys/admin', 'refresh');
		}

		if (in_array('manageSurvey', $this->permission)) {
			$survey_item = $this->model_surveys->getSurveyItemBySlug($survey_slug);

			if (!empty($survey_item)) {
				$this->form_validation->set_rules('survey_title', 'Title', 'trim|required');
				$this->form_validation->set_rules('categories[]', 'Categories', 'required');
				$this->form_validation->set_rules('limit_per_user', 'User Limits', 'trim|required');
				$this->form_validation->set_rules('global_limit', 'Global Limit', 'trim|required');

				if ($this->form_validation->run() == TRUE) {
					# code...
					$categories = implode(',', $this->input->post('categories'));
					$survey_title = $this->input->post('survey_title');
					$limit_per_user = $this->input->post('limit_per_user');
					$global_limit = $this->input->post('global_limit');

					$data = array('title' => $survey_title, 'category' => $categories, 'limit_per_user' => $limit_per_user, 'global_limit' => $global_limit);
					$updated_item = $this->model_surveys->updateSurveyItem($survey_item['id'], $data);

					if ($updated_item) {
						// log activity
						$log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Item Updated', 'message' => 'Updated Survey Item');
						$this->model_logs->logActivity($log);
						// flashdata
						$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Item Created', 'message' => 'Updated Survey Item'));
					} else {
						$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error Occurred', 'message' => 'Could not update Item'));
					}
					redirect('surveys/admin', 'refresh');
				}

				$cat_arr = $this->model_categories->getAllCategories();
				$sv_questions = $this->model_surveys->getSurveyQuestionsById($survey_item['id']);
				$this->data['categories'] = $cat_arr;
				$this->data['survey_item'] = $survey_item;
				$this->data['sv_questions'] = $sv_questions;
				$this->render_admin('pages/admin/activities/surveys/edit', $this->data);
			} else {
				redirect('surveys/admin', 'refresh');
			}
		} else {
			redirect('surveys/admin', 'refresh');
		}
	}

	public function addQuestion($survey_slug = null)
	{
		$user_id = $this->session->userdata('id');
		if ($survey_slug == null) {
			redirect('surveys/admin', 'refresh');
		}

		if (in_array('manageSurvey', $this->permission)) {
			$survey_item = $this->model_surveys->getSurveyItemBySlug($survey_slug);
			if ($survey_item['status'] == 'draft') {
				if ($survey_item['created_by'] == $user_id) {
					# can add questions
					$this->form_validation->set_rules('question_text', 'Question Name', 'trim|required');
					$this->form_validation->set_rules('option_style', 'Option Style', 'required');
					$this->form_validation->set_rules('option_text[]', 'Options', 'trim|required');

					if ($this->form_validation->run() == TRUE) {
						# code...
						$question_text = $this->input->post('question_text');
						$option_style = $this->input->post('option_style');
						$data = array('survey_slug' => $survey_slug, 'survey_id' => $survey_item['id'], 'question_text' => $question_text, 'option_style' => $option_style);
						$created_item = $this->model_surveys->createSurveyQuestion($data);
						$created_item = true;
						if ($created_item) {
							// update question count
							$no_questions = intval($survey_item['no_questions']) + 1;
							$this->model_surveys->updateSurveyItem($survey_item['id'], array('no_questions' => $no_questions));
							// add options
							$options = $this->input->post('option_text');
							foreach ($options as $key => $value) {
								$options_data = array('survey_slug' => $survey_slug, 'survey_id' => $survey_item['id'], 'sv_quest_id' => $created_item, 'option_text' => $value);
								$this->model_surveys->createSurveyOption($options_data);
							}
							// log activity
							$log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Item Created', 'message' => 'Updated Survey Options');
							$this->model_logs->logActivity($log);
							// flashdata
							$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Item Created', 'message' => 'Updated Survey Item'));
						} else {
							$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error Occurred', 'message' => 'Could not update Item'));
						}
						redirect('surveys/edit/' . $survey_slug, 'refresh');
					} else {
						redirect('surveys/edit/' . $survey_slug, 'refresh');
					}
				} else {
					$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error Occurred', 'message' => 'Could not update Item'));
					redirect('surveys/edit/' . $survey_slug, 'refresh');
				}
			} else {
				redirect('surveys/admin', 'refresh');
			}
		}
	}

	public function deleteQuestion($question_id = null)
	{

		$user_id = $this->session->userdata('id');

		if ($question_id == null) {
			redirect('surveys/admin', 'refresh');
		}

		if (in_array('manageSurvey', $this->permission)) {
			$question_item = $this->model_surveys->getSurveyQuestionByQuestId($question_id);
			$survey_item = $this->model_surveys->getSurveyItemBySlug($question_item['survey_slug']);
			// remove options, remove question
			$this->model_surveys->removeSurveyOptionsByQuestId($question_id);
			$remove = $this->model_surveys->removeSurveyQuestionByQuestId($question_id);
			if ($remove) {
				// update question count
				$no_questions = intval($survey_item['no_questions']) - 1;
				$this->model_surveys->updateSurveyItem($survey_item['id'], array('no_questions' => $no_questions));
				// log activity
				$log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Item Updated', 'message' => 'Updated Survey Item');
				$this->model_logs->logActivity($log);
				// flashdata
				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Item Created', 'message' => 'Updated Survey Item'));
			} else {
				$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error Occurred', 'message' => 'Could not update Item'));
			}

			redirect('surveys/edit', 'refresh');
		}
	}

	public function delete()
	{
		$user_id = $this->session->userdata('id');
		$group_name = $this->session->userdata('group_name');

		$response = array();

		$survey_slug = $this->input->post('slug');

		if (in_array('manageSurvey', $this->permission)) {
			$survey_item = $this->model_surveys->getSurveyItemBySlug($survey_slug);

			if (!empty($survey_item)) {
				$can_delete = false;
				// is not admin user
				if (!(strpos($group_name, 'admin') !== false)) {
					$can_delete = $survey_item['created_by'] == $user_id ? true : false;
				} else {
					$can_delete = true;
				}

				if ($can_delete) {
					$deleted = $this->model_surveys->removeSurveyItem($survey_item['id']);
					if ($deleted) {
						// log activity
						$log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Deleted Item', 'message' => 'Successfully deleted review item');
						$this->model_logs->logActivity($log);
						$response['success'] = true;
						$response['messages'] = 'Successfully removed';
					} else {
						$response['success'] = false;
						$response['messages'] = 'Error occurred while deleting product, contact admin';
						$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'title' => 'Error occurred', 'message' => 'Could not delete item, please try again later.'));
					}
				} else {
					$response['success'] =  false;
					$response['message'] = 'Cannot delete item';
				}
			}
		}

		echo json_encode($response);
	}
}
