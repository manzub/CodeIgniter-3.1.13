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
		$this->load->helper('email_helper.php');

		$this->load->model('model_groups');
		$this->load->model('model_users');
		$this->load->model('model_dailyactivity');

		// $this->load->model('model_surveys');
		// $this->load->model('model_transcribe');
		// $this->load->model('model_reviews');

		$this->load->model('model_config');
		$this->load_banner_configs();

		// construct permissions here
		// $default_member_permission = array('completeQuestion', 'completeSurvey', 'earnRewards', 'completeTranscribe', 'completeReview');
		// print_r(serialize($default_member_permission));
		$default_admin_permission = array('createUser', 'updateUser', 'deleteUser', 'viewUser', 'createGroup', 'updateGroup', 'deleteGroup', 'viewGroup', 'manageActivity', 'createCategory', 'editCategory', 'deleteCategory', 'updateSetting', 'createTransaction', 'updateTransaction', 'viewTransaction', 'deleteTransaction', 'manageReview', 'allReview', 'manageSurvey', 'allSurvey', 'manageTranscribe', 'allTranscribe');
		echo serialize($default_admin_permission);

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
			// $this->daily_activities($user_id);
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

	public function daily_activities($user_id = null)
	{
		// get last set from db
		$last_activity_list = $this->model_dailyactivity->get_last_activity_list($user_id);
		if (!empty($last_activity_list)) {
			// check if time expired
			// compare with today time
			$now = time();
			$last_created = strtotime($last_activity_list['date_created']);
			$datediff = $now - $last_created;
			$diff_in_days = round($datediff / (60 * 60 * 24));
			if ($diff_in_days > 0) {
				// create new activity list
				$this->generate_daily_activity_list($user_id);
			} else {
				// save previous item in session
				$current_activity_list = unserialize($last_activity_list['activity_slug']);
				$this->session->set_userdata('daily_activity', $current_activity_list);
				if (!empty($this->session->userdata('daily_activity'))) {
					if ($this->has_completed_daily_activity($user_id)) {
						$this->session->userdata(array('daily_activity_comp' => true));
					}
				}
			}
		} else {
			// create new activity list
			$this->generate_daily_activity_list($user_id);
		}
		// TODO: below on every complete activity
		// update session on each activity
		// reward config points
	}

	public function generate_daily_activity_list($user_id)
	{
		$activities_all = array();
		// load 5 random items each activity model
		$review_items = $this->model_reviews->getMyAvailableActivities('member', $user_id, true);
		$transcribe_items = $this->model_transcribe->getMyAvailableActivities('member', $user_id, true);
		$survey_items = $this->model_surveys->getAvailableSurveys('member', $user_id, true);
		$activities_all = array_merge($review_items, $survey_items, $transcribe_items);

		$rand_keys = array_rand($activities_all, 4);


		$activities_list = array();
		for ($i = 0; $i < count($rand_keys); $i++) {
			$curr_item = $activities_all[$rand_keys[$i]];
			// get slug and item id
			// create array('slug' => string, 'activity_type' => enum(action_types), 'item_id' => int)
			$activity_type = substr($curr_item['slug'], 0, 2);
			$activity_item = array('slug' => $curr_item['slug'], 'activity_type' => $activity_type, 'item_id' => $curr_item['id'], 'status' => 'available');
			array_push($activities_list, $activity_item);
		}

		// save array to session -> slugs[slug, status]
		$this->session->set_userdata('daily_activity', $activities_list);
		$saved = $this->model_dailyactivity->save_current_activity_list(array('user_id' => $user_id, 'activity_slug' => serialize($activities_list)));

		return ($saved == true) ? true : false;
	}

	public function has_completed_daily_activity($user_id)
	{
		// check if all items completed
		$activities_list = $this->session->userdata('daily_activity');
		$count_completed = 0;
		foreach ($activities_list as $key => $activity_item) {
			if ($activity_item['status'] == 'completed') {
				$count_completed += 1;
			}
		}

		// TODO: test
		if ($count_completed >= count($activities_list)) {
			return true;
		}
		return false;
	}

	public function get_daily_activities()
	{
		$user_id = $this->session->userdata('id');
		$result = array();

		$activities_list = $this->session->userdata('daily_activity');
		if (!empty($activities_list)) {
			// foreach item in list, get activity from model by slug
			// return result list
			foreach ($activities_list as $key => $dl_activity) {
				// item hasnt been completed
				if ($dl_activity['status'] == 'available') {
					$item = null;
					$link = "";
					$ac_type = "";
					switch ($dl_activity['activity_type']) {
						case 'rv': //review
							$ac_type = "Review";
							$item = $this->model_reviews->getReviewItemBySlug($dl_activity['slug']);
							$link = base_url('reviews/single/' . $dl_activity['slug']);
							break;
						case 'av': //transcribe
							$ac_type = "Transcribe";
							$item = $this->model_transcribe->getTranscribeItemBySlug($dl_activity['slug']);
							$link = base_url('transcribe/single/' . $dl_activity['slug']);
							break;

						default: //default survey
							$ac_type = "Survey";
							$item = $this->model_surveys->getSurveyItemBySlug($dl_activity['slug']);
							$link = base_url('surveys/single/' . $dl_activity['slug']);
							break;
					}

					if ($item != null) {
						$result[$key] = array('title' => $item['title'], 'link' => $link, 'type' => $ac_type);
					}
				}
			}
		}

		return $result;
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
		$from = "noreply@surveymonkey.com";
		$fromName = "SurveyMonkey";

		$email_body = `<div style="text-align: left; color: #666666;">
		Your friend has invited you to join SurveyMonkey. Get ready for a rewarding experience
		like no other!
		<ol>As a SurveyMonkey member, you'll enjoy these benefits:
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
		your first profiler quiz. <br><br>Click below to join SurveyMonkey! Get ready to turn
		your online activities into rewards.
	</div>`;

		$htmlContent = html_email_template($email_body, $link, $sender);

		// Set content-type header for sending HTML email 
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// Additional headers 
		$headers .= 'From: ' . $fromName . '<' . $from . '>' . "\r\n";
		$headers .= 'Cc: noreply@surveymonkey.com' . "\r\n";
		$headers .= 'Bcc: noreply@surveymonkey.com' . "\r\n";

		// Send email 
		if (mail($recipient, $subject, $htmlContent, $headers)) {
			return true;
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

	public function render_template($page = null, $data = array())
	{
		// $data['dl_activity_list'] = $this->get_daily_activities();
		$data['dl_activity_list'] = array();
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
}
