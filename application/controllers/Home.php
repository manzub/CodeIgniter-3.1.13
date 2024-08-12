<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Member_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('email_helper');

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

	public function contact_us()
	{
		$this->data['title'] = 'SurveyVine | Contact Us';

		$this->form_validation->set_rules('fname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required');
		$this->form_validation->set_rules('category', 'Category', 'required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');

		if ($this->form_validation->run() == true) {
			$lastName = !empty($this->input->post('lname')) ? $this->input->post('lname') : '';
			$fromName = $this->input->post('fname') . ' ' . $lastName;

			$subject = "A query has been submitted on SurveyVine!";

			$message = "<div style='align-items:left'>
			<h1>A new query has been submitted on SurveyVine.com</h1>
			<p>Here are the details.\n</p>
			<h3>From Name: ".$fromName."</h3>
			<h3>Email: ".$this->input->post('email')."</h3>
			<h3>Category: ".$this->input->post('category')."\n\n</h3>
			<p>Message details below:</p>
			<p>".$this->input->post('message')."</p>
			</div>";

			$email_body = html_email_template($message, $subject, null, $fromName);

			$this->email->from('noreply@surveyvine.com');
			$this->email->to('noreply@surveyvine.com');
			$this->email->subject($subject);
			$this->email->message($email_body);
			$this->email->set_mailtype('html');

			if ($this->email->send()) {
				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Query Submitted', 'message' => 'Your query has been submitted'));
			} else {
				echo $this->email->print_debugger();
				$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'An error occurred', 'message' => 'an error occurred and could not complete your request, try again later.'));
			}
		}

		$this->render_template('pages/contact_us', $this->data);
	}

	public function about_us()
	{
	}
}
