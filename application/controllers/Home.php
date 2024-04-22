<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Member_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_surveys');
		$this->load->model('model_reviews');
		$this->load->model('model_transcribe');
		$this->load->model('model_users');
		$this->load->model('model_config');
		$this->data["title"] = "SurveyMonkey!";
	}

	public function claim_daily_activities_reward()
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		$has_completed_dl = $this->has_completed_daily_activity($user_id);
		// TODO: test
		if ($has_completed_dl) {
			// reward points
			$points_earned = $this->model_config->getConfigByName('daily_activity_reward');
			$data = array('user_id' => $user_id, 'reward_earned' => $points_earned, 'type' => 'daily_activity', 'streak' => '0');
			$this->model_users->logClaimedReward($user_id, $data);
			// log activity
			$activity = array('user_id' => $user_id, 'activity_code' => '2', 'activity' => 'Daily Activities completed', 'message' => 'Well done!');
			$this->model_logs->logActivity($activity);
			// redirect to claims page
			redirect(current_url(), 'refresh');
		}
	}

	public function my_balance()
	{
		$user_id = $this->session->userdata('id');

		$my_rewards = $this->model_users->getUserRewardsBalance($user_id);

		echo json_encode(array('balance' => $my_rewards));
	}

	public function update_status($user_id)
	{
		if ($user_id) {
			$valid_user = $this->model_users->getUserById($user_id);

			if ($valid_user) {
				$this->session->set_userdata('curr_status', $this->user_status[$this->input->post('status')]);
			}
		}

		echo json_encode(array('status' => 1));
	}

	// page functions

	public function index()
	{
		$user_id = $this->session->userdata('id');
		$group_name = $this->session->userdata('group_name');

		$dl_activity_list = $this->session->userdata('daily_activity');
		$completed_dl_count = 0;
		if (!empty($dl_activity_list)) {
			foreach ($dl_activity_list as $key => $item) {
				if ($item['status'] == 'completed') {
					$completed_dl_count += 1;
				}
			}
		}
		$this->data['completed_dl_count'] = $completed_dl_count;

		$this->data['surveys'] = $this->model_surveys->getAvailableSurveys($group_name, $user_id, true, 0, 7);
		$this->data['transcribe'] = $this->model_transcribe->getMyAvailableActivities($group_name, $user_id, true, 0, 7);
		$this->data['reviews'] = $this->model_reviews->getMyAvailableActivities($group_name, $user_id, true, 0, 4);
		$this->render_template('pages/home', $this->data);
	}

	public function bonuses() {
		// select activities where category = bonus
		// use config daily bonus limit
		// check from last claimed
		$this->render_template('pages/bonus_activity', $this->data);
	}
}
