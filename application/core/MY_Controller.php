<?php

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
}

class Member_Controller extends MY_Controller
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
		$this->load->helper('my_helper.php');
		$this->load->library('dailyactivities');
		$this->load->library('email');
 
		$this->load->model('model_logs');
		$this->load->model('model_groups');
		$this->load->model('model_users');
		$this->load->model('model_config');
		$this->load->model('model_dailyactivity');
		$this->load_banner_configs();

		// construct permissions here
		// $default_member_permission = array('completeQuestion', 'completeSurvey', 'earnRewards', 'completeTranscribe', 'completeReview');
		// print_r(serialize($default_member_permission));
		// $default_admin_permission = array('createUser', 'updateUser', 'deleteUser', 'viewUser', 'createGroup', 'updateGroup', 'deleteGroup', 'viewGroup', 'manageActivity', 'createCategory', 'editCategory', 'deleteCategory', 'updateSetting', 'createTransaction', 'updateTransaction', 'viewTransaction', 'deleteTransaction', 'manageReview', 'allReview', 'manageSurvey', 'allSurvey', 'manageTranscribe', 'allTranscribe');
		// echo serialize($default_admin_permission);

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
		} else { //load user
			$user_id = $this->session->userdata('id');
			$group_data = $this->model_groups->getUserGroupByUserId($user_id);

			$this->data['user_permission'] = unserialize($group_data['permissions']);
			$this->permission = unserialize($group_data['permissions']);
			$this->session->set_userdata('group_name', $group_data['group_name']);

			// $permissions = array_merge(array('manageBonus', 'createBonus'), unserialize($group_data['permissions']));
			// print_r(serialize($permissions));
			if (empty($this->session->userdata('currency'))) {
				// set default currency
				$default_currency = $this->model_config->getConfigByName('default_currency_option');
				$this->session->set_userdata('currency', array('currency' => $default_currency['value'], 'rate' => $default_currency['extra']));
			}

			$this->bonus_available();
			$this->earned_from_refered_users($user_id);
			// $this->daily_limit_reached($user_id);
			$this->get_daily_activities($user_id, $group_data['group_name']);
			$this->default_balance();
		}
	}

	public function alertAdmins($user_group = null, $message = "") {
		if ($user_group) {
			$users = $this->model_users->getAllUsers($user_group);
			if (!empty($users)) {
				foreach ($users as $value) {
					$subject = "Hi ".$value['username'].", you have a message from Surveyvine!";
					$this->send_email($value['email'], $subject, $message);
				}
			}
		}
	}

	public function default_balance() {
		$user_id = $this->session->userdata('id');
		$balance = $this->model_users->getUserRewardsBalance($user_id)['total_rewards'];
		$this->data['mvc_balance'] = $balance;
	}

	public function daily_limit_reached($user_id = null)
	{
		if ($user_id != null) {
			// TODO: on membership plans change default loaded
			$daily_limit_config = $this->model_config->getConfigByName('daily_limit');
			$daily_limit = intval($daily_limit_config['value']);

			// select all earning from db with date time greater than yesterday
			$todays_earnings_raw = $this->model_users->getUserTotalRewardsToday($user_id);
			$todays_earnings = intval($todays_earnings_raw['total_earned']);

			// compare with db set limit
			if ($todays_earnings >= $daily_limit) {
				return true;
				// $this->session->set_userdata(array('daily_limit_reached' => true));
			}
		}

		return false;
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
				$diff_in_days = diff_in_days(strtotime($lastest_streaks[0]['last_modified']));
				if ($diff_in_days == 1) {
					// set session
					$x = intval($lastest_streaks[0]['streak']);
					$new_count = $x < 5 ? $x + 1 : 1;
					$this->session->set_userdata(array('streak_count' => $new_count, 'last_time' => $lastest_streaks[0]['last_modified'], 'bonus_available' => true));
				} else {
					if ($diff_in_days > 1) {
						$this->session->set_userdata(array('streak_count' => 1, 'bonus_available' => true));
					} else {
						$this->session->set_userdata(array('streak_count' => $lastest_streaks[0]['streak'], 'bonus_available' => false));
					}
				}
			} else {
				// bonus item available with streak 0
				$this->session->set_userdata(array('streak_count' => 1, 'bonus_available' => true));
			}
		}
	}

	public function get_daily_activities($user_id, $group_name)
	{
		$activity_list = '';
		if ($user_id != null && $group_name == 'member') {
			$last_saved_activities = $this->model_dailyactivity->get_last_activity_list($user_id);
			if (!empty($last_saved_activities)) {
				// compare date difference
				$diff_in_days = diff_in_days(strtotime($last_saved_activities['date_created']));
				if ($diff_in_days <= 1) {
					$activity_list = $last_saved_activities['activity_slug'];
				}
			}

			// if empty call dl_activity library and generate new list.
			if (strlen($activity_list) == 0) {
				$activity_list = $this->dailyactivities->generate_daily_activity_list($user_id);
			}
			// load session
			$this->load_activities($activity_list, $user_id);
		}
	}

	public function load_activities($activity_slug, $user_id)
	{
		$activity_list = unserialize($activity_slug);
		$sorted_activities = $this->dailyactivities->has_completed_item($activity_list, $user_id);
		$this->session->set_userdata('daily_activity', $sorted_activities);
	}

	// reward points
	public function reward_points($points_earned = 0, $total_earned = 0, $slug = null, $data = array())
	{
		$user_id = $this->session->userdata('id');
		if (!empty($data) && $points_earned != 0) {
			// calculate earning limit
			$limit_reached = $this->daily_limit_reached();
			if (!$limit_reached) {
				if ($slug != null) {
					// check if is daily activity
					$dl_list = $this->session->userdata('daily_activity');
					$dl_slugs = array_map(function ($o) {
						return $o['slug'];
					}, $dl_list);
					if (!empty($dl_slugs)) {
						if (in_array($slug, $dl_slugs)) {
							$daily_activity_config = $this->model_config->getConfigByName('daily_activity_reward');
							$points_earned = intval($daily_activity_config['value']);
							$key = array_search($slug, $dl_slugs);
							$dl_list[$key]['status'] = 'completed';
							$this->session->set_userdata('daily_activity', $dl_list);
						}
					}
				}

				// reward ref user
				$my_account = $this->model_users->getuserById($user_id);
				if (!in_array($my_account['referred_by'], array(null, 'NULL'))) {
					$my_referrer = $this->model_users->getUserByRefCode($my_account['referred_by']);
					// load interest config
					$reward_interest_config = $this->model_config->getConfigByName('ref_reward_interest');
					$reward_interest = (float) $reward_interest_config['value'];
					$interest_earned = $points_earned * $reward_interest;

					$this->model_users->logClaimedReward($my_referrer['id'], array('user_id' => $my_referrer['id'], 'reward_earned' => $interest_earned, 'type' => 'ref_interest', 'streak' => '0'));
				}
				// reward user
				$rewarded = $this->model_users->logClaimedReward($user_id, array_merge($data, array('user_id' => $user_id, 'reward_earned' => $total_earned)));
				return $rewarded;
			}
		}

		return false;
	}

	public function earned_from_refered_users($user_id = null)
	{
		// test referrals
		// check refered users | check refered_by user
		if ($user_id != null) {
			// check if already earned from ref bonus
			$user = $this->model_users->getUserById($user_id);
			$refered_by = $user['referred_by'];
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

	public function generate_ref_code($count = 8)
	{
		// $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$ref_code = '';

		for ($i = 0; $i < $count; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$ref_code .= $characters[$index];
		}

		return $ref_code;
	}

	public function load_banner_configs()
	{
		// page banners
		// top_left, sidebar_center, home_bottom, sidebar_left_center, sidebar_left_bottom
		$this->data['banner_top_right'] = $this->model_config->getConfigByName('banner_page_top_right_340');
		$this->data['banner_right_center'] = $this->model_config->getConfigByName('banner_sidebar_right_center');
		$this->data['banner_home_bottom'] = $this->model_config->getConfigByName('banner_page_home_bottom');
		$this->data['banner_sidebar_left_center'] = $this->model_config->getConfigByName('banner_sidebar_left_center');
		$this->data['banner_sidebar_left_bottom'] = $this->model_config->getConfigByName('banner_sidebar_left_bottom');
	}

	public function send_referral_email($sender = null, $recipient = null, $subject = null, $link = null)
	{
		// construct mail body
		// send email
		// return true or false case
		$from = "noreply@surveyvine.com";
		$fromName = "SurveyVine";
		$this->email->from($from, $fromName);

		$email_body = '<div style="text-align: left; color: #666666;">
		Your friend has invited you to join SurveyVine. Get ready for a rewarding experience
		like no other!
		<ol>As a SurveyVine member, you\'ll enjoy these benefits:
			<li>Earn Points (SB): Complete tasks like surveys, reviews, transcribe audio recordings,
				and more to earn SB points.</li>
			<li>Multiple Reward Options: Redeem SB points for gift cards from popular
				retailers or cash through PayPal or Local Bank.</li>
			<li>Daily Goal Bonuses: Meet your daily goals and earn bonus SB points for extra
				rewards.</li>
			<li>Community and Support: Connect with our active community for support and
				advice.</li>
		</ol>
		Explore our website to start earning SB points today! Earn your first $5 gift card
		quickly and easily by verifying your email address and mobile number, and completing
		your first profiler quiz. <br><br>Click below to join SurveyVine! Get ready to turn
		your online activities into rewards.
	</div>';

		$htmlContent = html_email_template($email_body, $subject, $link, $sender);

		// Set content-type header for sending HTML email 
		$this->email->set_header('MIME-Version', "1.0");
		$this->email->set_header('From', $from . "\r\n");
		$this->email->set_header('Reply-To', $from . "\r\n");
		$this->email->set_header('Return-Path', $from . "\r\n");
		$this->email->set_header('Organization', "SurveyVine\r\n");
		$this->email->set_header('X-Mailer', phpversion() . "\r\n");
		$this->email->set_mailtype('html');

		$this->email->to($recipient);
		$this->email->subject($subject);
		$this->email->message($htmlContent);

		// Send email 
		if ($this->email->send()) {
			return true;
		}

		return false;
	}

	public function send_email($to = null, $subject = null, $message = null)
	{
		if ($to && $subject && $message) {
			$from = "noreply@surveyvine.com";
			$fromName = 'SurveyVine';
			$this->email->from($from, $fromName);

			// Set content-type header for sending HTML email 
			$this->email->set_header('MIME-Version', "1.0");
			$this->email->set_header('From', $from . "\r\n");
			$this->email->set_header('Reply-To', $from . "\r\n");
			$this->email->set_header('Return-Path', $from . "\r\n");
			$this->email->set_header('Organization', "SurveyVine\r\n");
			$this->email->set_header('X-Mailer', phpversion() . "\r\n");
			$this->email->set_mailtype('html');

			$this->email->to($to);
			$this->email->subject($subject);
			$this->email->message(html_email_template($message, null, $fromName));

			if ($this->email->send()) {
				return true;
			}
		}

		return false;
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

	public function no_admin($param = true)
	{
		$session_data = $this->session->userdata();
		if ($session_data['logged_in'] == TRUE) {
			// check group name
			$group_name = $session_data['group_name'];
			if ((strpos($group_name, 'admin') !== false) || in_array($group_name, array('moderator', 'publisher'))) {
				if ($param) {
					$this->session->set_flashdata('alert', array('classname' => 'alert-info', 'message' => 'You don\'t have access to view that page.', 'title' => 'Access denied.'));
					redirect('dashboard', 'refresh');
				}
			}
		}
	}

	public function render_template($page = null, $data = array())
	{
		$activity_list = $this->session->userdata('daily_activity');
		$data['dl_activity_list'] = $this->dailyactivities->daily_activity_html($activity_list);
		// $data['dl_activity_list'] = array();

		// load currency options
		$currency_options = $this->model_config->getSimilarConfig('_currency_option');
		if (!empty($currency_options)) {
			$data['currency_options'] = $currency_options;
		}


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

	public function render_admin($page = null, $data = array())
	{

		$this->load->view('templates/admin/header', $data);
		$this->load->view('templates/admin/header_menu', $data);
		$this->load->view('templates/admin/side_menubar', $data);
		$this->load->view($page, $data);
		$this->load->view('templates/admin/footer', $data);
	}

	public function reArrayFiles($file)
	{
		$file_ary = array();
		$file_count = count($file['name']);
		$file_key = array_keys($file);

		for ($i = 0; $i < $file_count; $i++) {
			foreach ($file_key as $val) {
				$file_ary[$i][$val] = $file[$val][$i];
			}
		}
		return $file_ary;
	}
}
