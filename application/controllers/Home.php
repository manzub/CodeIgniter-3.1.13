<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Main_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_surveys');
		$this->load->model('model_users');
		$this->data["title"] = "SurveyMonkey!";

		// print_r(serialize(array('completeSurvey', 'completeQuestion','earnRewards')));
	}

	public function my_balance() {
		$user_id = $this->session->userdata('id');

		$my_rewards = $this->model_users->getUserRewardsBalance($user_id);

		echo json_encode(array('balance' => $my_rewards));
	}

	public function update_status($user_id) {
		if ($user_id) {
			$valid_user = $this->model_users->getUserById($user_id);
			
			if ($valid_user) {
				$this->session->set_userdata('curr_status', $this->user_status[$this->input->post('status')]);
			}
		}

		echo json_encode(array('status' => 1));
	}

	public function index()
	{
		$user_id = $this->session->userdata('id');
		$group_name = $this->session->userdata('group_name');

		$this->data['surveys'] = $this->model_surveys->getAvailableSurveys($group_name, $user_id, true, 0, 7);
		$this->render_template('pages/home', $this->data);
	}
}
