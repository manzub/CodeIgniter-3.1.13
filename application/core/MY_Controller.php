<?php

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
}


class Main_Controller extends MY_Controller
{
	var $permission = array();
	var $user_status = array(
		'logged_in' => array('code' => 'logged in', 'text' => 'Logged In'),
		'in_activity' => array('code' => 'in_activity', 'text' => 'In Activity'),
		'bonus_avail' => array('code' => 'bonus_avail', 'text' => 'Daily Bonus Available'),
		'in_page' => array('code' => 'in_page', 'text' => 'Idle')
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_groups');
		$this->load->model('model_users');
		$this->load->model('model_config');

		$group_data = array();
		if (empty($this->session->userdata('logged_in'))) {
			$session_data = array('logged_in' => FALSE);
			$this->session->set_userdata($session_data);
			// save not logged in state
			// load guest user
			$group_data = $this->model_groups->getUserByGroupName('guest');
			$this->data['user_permission'] = unserialize($group_data['permissions']);
			$this->permission = unserialize($group_data['permissions']);

			$guest_user = array('id' => $group_data['id'], 'username' => $group_data['username'], 'email' => $group_data['email'], 'group_name' => $group_data['group_name']);
			$this->session->set_userdata($guest_user);
		} else { //load regular user
			$user_id = $this->session->userdata('id');

			$group_data = $this->model_groups->getUserGroupByUserId($user_id);
			$this->data['user_permission'] = unserialize($group_data['permissions']);
			$this->permission = unserialize($group_data['permissions']);
			$this->session->set_userdata('group_name', $group_data['group_name']);
			$this->bonus_available();
			$this->earned_from_refered_users($user_id);
		}
	}

	public function bonus_available()
	{
		// load last claimed with user_id and type dl_bonus and streak < 5

		if (!empty($this->session->userdata('logged_in'))) {
			$user_id = $this->session->userdata('id');
			$cond = array('type' => 'daily_bonus', 'streak <=' => '5');
			$lastest_streaks = $this->model_users->getUserRewardByCond($user_id, $cond);
			if (!empty($lastest_streaks)) { // saved item available
				// compare with today time
				$now = time();
				$last_streak = strtotime($lastest_streaks[0]['last_modified']);
				$datediff = $now - $last_streak;
				$diff_in_days = round($datediff / (60 * 60 * 24));
				if ($diff_in_days > 0 && $diff_in_days < 2) {
					// set session
					$this->session->set_userdata(array('streak_count' => (int) $lastest_streaks[0]['streak'], 'last_time' => $lastest_streaks[0]['last_modified'], 'bonus_available' => true));
				} else {
					$this->session->set_userdata(array('streak_count' => (int) $lastest_streaks[0]['streak'], 'last_time' => $lastest_streaks[0]['last_modified'], 'bonus_available' => false));
				}
			} else {
				// bonus item available with streak 0
				$this->session->set_userdata(array('streak_count' => 0, 'bonus_available' => true));
			}
		}
	}

	public function earned_from_refered_users($user_id = null)
	{
		// TODO: test referrals
		// check refered users | check refered_by user
		if ($user_id != null) {
			// check if already earned from ref bonus
			$user = $this->model_users->getUserById($user_id);
			$refered_by = $user['refered_by'];
			if (!in_array($refered_by, array(null, 'NULL'))) {
				$cond = array('user_id' => $refered_by, 'type' => 'ref_bonus', 'reward_earned >' => '0');
				$earned_ref = $this->model_users->getUserRewardByCond($user_id, $cond);
				if (empty($earned_ref)) { // refered_by user hasn't earned a reward
					// check if current user has completed set activity length
					$config_limit = $this->model_config->getConfigByName('ref_reward_after');
					$completed_activities = $this->model_users->getUserRewardByCond($user_id, array('user_id' => $user_id, 'type' => 'completed_activity', 'reward_earned >' => '0'));
					if (count($completed_activities) >= $config_limit['value']) {
						// reward points
						$config_reward = $this->model_config->getConfigByName('ref_reward');
						$this->model_users->logClaimedReward($refered_by, array('reward_earned' => $config_reward['value'], 'type' => 'ref_bonus'));
					}
				}
			}
		}
	}

	public function generate_ref_code($count = 6)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$ref_code = '';

		for ($i = 0; $i < $n; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$ref_code .= $characters[$index];
		}

		return $ref_code;
	}

	public function logged_in()
	{ //redirect if user already logged in
		$session_data = $this->session->userdata();
		if ($session_data['logged_in'] == TRUE) {
			redirect('home', 'refresh');
		}
	}

	public function not_logged_in()
	{ // restrict page access if not logged in
		$session_data = $this->session->userdata();
		if ($session_data['logged_in'] == FALSE) {
			$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'title' => 'Oops', 'message' => 'You need to be signed in to access that page.'));
			redirect('auth/login', 'refresh');
		}
	}

	public function render_template($page = null, $data = array())
	{
		if (!file_exists(APPPATH . '/views/' . $page . '.php')) {
			# code...
			$data["message"] = "Page Not Found";
			$page = 'errors/html/error_404';
		}
		$this->load->view('templates/topbar', $data);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar_left', $data);
		$this->load->view($page, $data);
		$this->load->view('templates/sidebar_right', $data);
		$this->load->view('templates/footer', $data);
	}
}
