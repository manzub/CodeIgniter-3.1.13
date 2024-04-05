<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends Main_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['title'] = "My Account | SurveyMonkey";
		$this->load->model('model_users');
		$this->load->model('model_referrals');
		$this->load->model('model_logs');
		$this->load->model('model_config');
	}

	public function claimDailyBonus()
	{
		$user_id = $this->session->userdata('id');

		if ($user_id) {
			$cond = array('type' => 'daily_bonus', 'streak <=' => '5');
			$lastest_streaks = $this->model_users->getUserRewardByCond($user_id, $cond);
			if (!empty($lastest_streaks)) {
				# last item exists
				$curr_count = intval($lastest_streaks[0]['streak']);
				$reward_bonus = (int) $lastest_streaks[0]['reward_earned'];

				if ($curr_count > 0 && $curr_count <= 5) {
					$multiply = (float) $this->model_config->getConfigByName('daily_bonus_multiply')['value'];
					$reward_bonus = round($reward_bonus * $multiply);
					$curr_count = $curr_count + 1;
				} else {
					$curr_count = 1;
					$reward_bonus = (int) $this->model_config->getConfigByName('daily_bonus')['value'];
				}

				$data = array('user_id' => $user_id, 'reward_earned' => $reward_bonus, 'type' => 'daily_bonus', 'streak' => $curr_count);
				$this->model_users->logClaimedReward($user_id, $data);

				if ($curr_count == 5) {
					$this->model_users->logClaimedReward($user_id, array('user_id' => $user_id, 'reward_earned' => 0, 'type' => 'daily_bonus', 'streak' => 0));
				}

				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Congratulations!', 'message' => 'You have Claimed Daily Bonus #' . $curr_count));
				// log activity
				$activity = array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Claimed Daily Bonus', 'message' => 'Claimed Daily Bonus!');
				$this->model_logs->logActivity($activity);
				redirect('profile', 'refresh');
			} else {
				$reward_bonus = (int) $this->model_config->getConfigByName('daily_bonus')['value'];
				$data = array('user_id' => $user_id, 'reward_earned' => $reward_bonus, 'type' => 'daily_bonus', 'streak' => 1);
				$this->model_users->logClaimedReward($user_id, $data);
				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Congratulations!', 'message' => 'You have Claimed Daily Bonus #1'));
				redirect('profile', 'refresh');
			}
		}
	}

	public function index()
	{
		$this->not_logged_in();

		$user_id = $this->session->userdata('id');

		$cond = array('type' => 'daily_bonus', 'streak <=' => '5');
		$lastest_streaks = $this->model_users->getUserRewardByCond($user_id, $cond);
		$reward_bonus = (int) $this->model_config->getConfigByName('daily_bonus')['value'];
		$multiply = (float) $this->model_config->getConfigByName('daily_bonus_multiply')['value'];

		$this->data['last_streak'] = $lastest_streaks;
		$this->data['bonus'] = $reward_bonus;
		$this->data['multiply'] = $multiply;

		$this->render_template('pages/profile/index', $this->data);
	}

	public function settings()
	{
		$this->not_logged_in();

		$user_id = $this->session->userdata('id');
		// user_meta => full name and address info
		$users_meta = $this->model_users->getUserMetaById($user_id);
		$this->data['users_meta'] = $users_meta;
		// users_account => bank info
		$user_accounts = $this->model_users->getUserAccountsById($user_id);

		$this->data['user_accounts'] = $user_accounts;

		$this->render_template('pages/profile/settings', $this->data);
	}

	public function referrals()
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');
		$this->data['title'] = 'My Referrals | SurveyMonkey!';

		$me = $this->model_users->getUserById($user_id);
		$ref_code = $me['ref_code'];

		$referred_by_me = array();

		if (!in_array($ref_code, array(null, 'NULL'))) {
			// get referrals
			$my_referrals = $this->model_referrals->getReferrals($ref_code);
			if (!empty($my_referrals)) {
				// construct referral item with user information
				foreach ($my_referrals as $key => $ref_item) {
					// get email and last modified
					$user_info = '';
					$last_activity = '';
					$ref_user_tt_earned = null;
					if (!in_array($ref_item['user_id'], array(NULL, 'null'))) { // get user if userid exists
						// get user from db
						$ref_user = $this->model_users->getUserById($ref_item['user_id']);
						$user_info = $ref_user['username'] . ' | ' . substr($ref_user['email'], 0, 15) . '...';
						$ref_user_tt_earned = $this->model_users->getUserRewardsBalance($ref_item['user_id']);

						$last_logs = $this->model_logs->getUserLogsById($ref_user['id']);
						$last_activity = $last_logs[0]['activity'];
					} else {
						$user_info = $ref_item['email'];
					}


					$referred_by_me[$key] = array(
						'user_info' => $user_info,
						'status' => $ref_item['status'],
						'last_activity' => $last_activity,
						'total_earned' => $ref_user_tt_earned['total_rewards'] != '' ? $ref_user_tt_earned['total_rewards'] : 0
					);
				}
			}
		} else {
			$new_ref_code = $this->generate_ref_code(8);
			$updated = $this->model_users->update($user_id, array('ref_code' => $new_ref_code));
			if ($updated) {
				// log activity
				$activity = array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Updated Referral Information', 'message' => 'Updated User Referral Code');
				$this->model_logs->logActivity($activity);
				redirect('profile/referrals', 'refresh');
			}
		}

		$total_earned = 0;
		$cond = array('type LIKE' => '%reward_bonus, ref_interest%', 'reward_earned >' => '0');
		$ref_rewards = $this->model_users->getUserRewardByCond($user_id, $cond);
		if (!empty($ref_rewards)) {
			foreach ($ref_rewards as $key => $reward) {
				$total_earned = $total_earned + intval($reward['reward_earned']);
			}
		}

		$this->data['my_referrals'] = $referred_by_me;
		$this->data['total_earned'] = $total_earned;
		$this->render_template('pages/profile/referrals', $this->data);
	}

	public function invite()
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');
		$this->data['title'] = 'Invite your Friends | SurveyMonkey';

		$ref_bonus = $this->model_config->getConfigByName('ref_reward')['value'];
		$ref_reward_config = $this->model_config->getConfigByName('ref_reward_after')['value'];
		$ref_interest = $this->model_config->getConfigByName('ref_reward_interest')['value'];

		$this->data['ref_bonus'] = $ref_bonus;
		$this->data['ref_reward_config'] = $ref_reward_config;
		$this->data['ref_interest'] = $ref_interest;

		// get ref_code
		$ref_code = $this->model_users->getUserById($user_id)['ref_code'];
		$this->data['ref_code'] = $ref_code;

		$this->render_template('pages/profile/invite', $this->data);
	}

	public function send_invite_email()
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		$this->form_validation->set_rules('recipient_email', 'Email', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$recipient = $this->input->post('recipient_email');
			if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
				$subject = 'Your friend invited you to join SurveyMonkey';

				$ref_code = $this->model_users->getUserById($user_id)['ref_code'];
				$link_url = base_url('auth/signup/' . $ref_code);
				$link_html = `<a href="`.$link_url.`"
				style="line-height: 24px; text-decoration: none; word-break: break-word; font-weight: 500; display: block; font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff;">Create
				a Free Account</a>`;
				$send_ref_email = $this->send_referral_email($this->session->userdata('username'), $recipient, $subject, $link_html);
				if ($send_ref_email) {
					// log activity
					$activity = array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Sent Referral Link', 'message' => 'Invited user ' . $recipient);
					$this->model_logs->logActivity($activity);
					// set session
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Invite Link Sent', 'message' => 'Invite Link sent to ' . $recipient));
					redirect('profile/invite', 'refresh');
				} else {
					// set session
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Invite Link Sent', 'message' => 'Invite Link sent to ' . $recipient));
					redirect('profile/invite', 'refresh');
				}
			}
		}
	}

	// misc functions

	public function edit_user()
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		$this->form_validation->set_rules('full_name', 'Full Name', 'trim');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim');

		if ($this->form_validation->run() == TRUE) {
			$data_user = array('email' => $this->input->post('email'), 'username' => $this->input->post('username'));
			$update_user = $this->model_users->update($user_id, $data_user);

			$meta_data = array('full_name' => $this->input->post('full_name'), 'phone' => $this->input->post('phone'));
			$update_meta = $this->model_users->updateMetaById($user_id, $meta_data);

			if ($update_user && $update_meta) {
				// log activity
				$activity = array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Edited User Information', 'message' => 'Edited User Information (Email/Username/Full Name)!');
				$this->model_logs->logActivity($activity);

				$changed_username = $this->input->post('username') != $this->session->userdata('username');
				$changed_email = $this->input->post('email') != $this->session->userdata('email');
				if ($changed_email && $changed_username) {
					$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'User information chanaged', 'message' => 'You have to sign in again'));
					redirect('auth/logout', 'refresh');
				} else {
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Done', 'message' => 'User information chanaged'));
					redirect('profile/settings', 'refresh');
				}
			}
		} else {
			$row = $this->model_users->getUserMetaById($user_id);
			$this->data['row'] = $row;
			$this->render_template('pages/profile/edit_user', $this->data);
		}
	}

	public function edit_meta()
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		$this->form_validation->set_rules('address', 'Address', 'trim');
		$this->form_validation->set_rules('city', 'City', 'trim');
		$this->form_validation->set_rules('state', 'State', 'trim');
		$this->form_validation->set_rules('post_code', 'Post Code', 'trim');
		$this->form_validation->set_rules('country', 'Country', 'trim');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'address' => $this->input->post('address'),
				'city' => $this->input->post('city'),
				'state' => $this->input->post('state'),
				'post_code' => $this->input->post('post_code'),
				'country' => $this->input->post('country'),
			);

			$update = $this->model_users->updateMetaById($user_id, $data);
			if ($update) {
				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Done', 'message' => 'Updated User Information'));
				// log activity
				$activity = array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Edited User Information', 'message' => 'Successfully edited user information');
				$this->model_logs->logActivity($activity);
				redirect('profile/settings');
			} else {
				$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'title' => 'Oops', 'message' => 'Error occurred'));
			}
		} else {
			$row = $this->model_users->getUserMetaById($user_id);
			$this->data['row'] = $row;
			$this->render_template('pages/profile/edit_meta', $this->data);
		}
	}

	public function add_paypal()
	{
		$this->not_logged_in();

		$user_id = $this->session->userdata('id');
		// collect form information
		// save in database
		// refresh
		$this->form_validation->set_rules('paypal_email', 'Email', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$data = array('user_id' => $user_id, 'type' => 'paypal', 'email' => $this->input->post('paypal_email'));
			$update = $this->model_users->insertUserAccount($data);
			if ($update) {
				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Done', 'message' => 'Paypal address set'));
				// log activity
				$activity = array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Linked Account', 'message' => 'Successfully linked Paypal');
				$this->model_logs->logActivity($activity);
				redirect('profile', 'refresh');
			}
		}
	}

	public function unlink_account($account_type = null)
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		if ($account_type != null) {
			if (in_array($account_type, array('paypal', 'bank'))) {
				$updated = $this->model_users->unlinkAccountByUserId($user_id, $account_type);
				if ($updated) {
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Done', 'message' => 'Unlinked ' . $account_type . ' account'));
					// log activity
					$activity = array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Unlinked user account', 'message' => 'You have unlinked ' . $account_type . '!');
					$this->model_logs->logActivity($activity);
					redirect('profile/settings', 'refresh');
				}
			}
		}
	}
}
