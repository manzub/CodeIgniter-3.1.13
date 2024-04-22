<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transcribe extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		// load needed db configs
		$this->load->model('model_transcribe');
		$this->load->model('model_users');
		$this->load->model('model_config');
		$this->load->model('model_categories');
		$this->load->model('model_logs');
		$this->data['title'] = "Transcribe | SurveyMonkey";
		$this->data['per_page'] = 5;
	}

	public function completeTranscribeItem($slug = null)
	{
		$user_id = $this->session->userdata('id');
		if ($slug == null) {
			redirect('transcribe', 'refresh');
		}

		$this->form_validation->set_rules('transcribe_text', 'Text', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			// check permission
			if (in_array('completeTranscribe', $this->permission)) {
				// check if user already completed and limits_per_user
				$transcribe_item = $this->model_transcribe->getTranscribeItemBySlug($slug);

				if (!empty($transcribe_item)) {
					// check if item hasnt expired
					if ($transcribe_item['status'] == 'available') {
						// limit_per_user hasnt been reached
						$completed_items = $this->model_transcribe->getCompletedByTranscribeId($user_id, $transcribe_item['id']);
						if (!empty($completed_items)) {
							if (count($completed_items) >= $transcribe_item['limit_per_user']) {
								$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Oops', 'message' => 'You have already completed this activity'));
								redirect('transcribe', 'refresh');
							}
						}

						// save completed item
						$comp_raw = htmlspecialchars($this->input->post('transcribe_text'));
						$data = array('transcribe_id' => $transcribe_item['id'], 'items' => $comp_raw, 'completed_by' => $user_id, 'status' => 'completed', 'points_earned' => '0');
						$completed = $this->model_transcribe->completeItem($data);

						if ($completed) {
							// log activity
							$activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Transcribed 1 Item', 'message' => 'Well done!');
							$this->model_logs->logActivity($activity);
							// redirect to claims page
							redirect('transcribe/completed/' . $slug, 'refresh');
						}
					}
				} else {
					// redirect('transcribe', 'refresh');
				}
			}
		}
	}

	// page functions

	public function index($page = 1)
	{
		$user_id = $this->session->userdata('id');
		$group_name = $this->session->userdata('group_name');

		// get available activities
		$activities_page = $this->model_transcribe->getMyAvailableActivities($group_name, $user_id, true, $page - 1, $this->data['per_page']);
		$this->data['activities'] = $activities_page;

		// create navigation
		$available_items = $this->model_transcribe->getMyAvailableActivities($group_name, $user_id);
		$count_available_av = count($available_items);
		$this->data['pages'] = round($count_available_av / $this->data['per_page']);
		$this->data['page'] = $page;

		$this->render_template('pages/transcribe/index', $this->data);
	}

	public function single($slug = null)
	{
		if ($slug == null) {
			show_404('Page Not Found');
		}

		// completeTranscribe - can transcribe items
		if (in_array('completeTranscribe', $this->permission)) {
			// render view
			$transcribe_item = $this->model_transcribe->getTranscribeItemBySlug($slug);

			if (!empty($transcribe_item)) {
				$categories = array();
				$cat_arr = explode(",", $transcribe_item['category']);
				foreach ($cat_arr as $key => $item) {
					$cat_item = $this->model_categories->getCategoryById($item);
					array_push($categories, $cat_item['value']);
				}

				// todo multiple audio files

				$this->data['title'] = $transcribe_item['title'] . " | SurveyMonkey";
				$this->data['transcribe_item'] = $transcribe_item;
				$this->render_template('pages/transcribe/single', $this->data);
			} else {
				show_404('Page Not Found');
			}
		} else {
			$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Oops - you don\'t have permission', 'message' => 'Why miss out, create a member account today and completing activities.'));
			redirect('transcribe', 'refresh');
		}
	}

	public function completed($slug = null)
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		if ($slug == null) {
			redirect('transcribe', 'refresh');
		}

		if (in_array('earnRewards', $this->permission)) {
			$transcribe_item = $this->model_transcribe->getTranscribeItemBySlug($slug);
			$completed_items = $this->model_transcribe->getCompletedByTranscribeId($user_id, $transcribe_item['id']);

			if (!empty($transcribe_item) && !empty($completed_items)) {
				// on user click claim button
				$this->form_validation->set_rules('completed', 'slug', 'required');
				if ($this->form_validation->run() == TRUE) {
					// TODO: member multiply
					$points_earned = $transcribe_item['reward_points'];
					$data = array('points_earned' => $points_earned);
					foreach ($completed_items as $key => $comp_item) {
						$update = $this->model_transcribe->updateCompletedItem($comp_item['id'], $data);
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
						}
					}

					$total_earned = round($points_earned * count($completed_items));
					$this->model_users->logClaimedReward($user_id, array('user_id' => $user_id, 'transcribe_id' => $transcribe_item['id'], 'reward_earned' => $total_earned, 'type' => 'completed_activity', 'streak' => '0'));
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $total_earned, 'title' => 'Completed'));
					// log activity
					$activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Earned Reward', 'message' => 'Congratulations! Earned' . $total_earned);
					$this->model_logs->logActivity($activity);
					redirect('transcribe', 'refresh');
				}

				$this->data['title'] = "Claim your rewards - " . $transcribe_item['title'] . " | SurveyMonkey!";
				$this->data['transcribe_item'] = $transcribe_item;
				$this->render_template('pages/transcribe/completed', $this->data);
			} else {
				redirect('transcribe', 'refresh');
			}
		}
	}
}
