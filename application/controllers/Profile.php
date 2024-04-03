<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends Main_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data['title'] = "My Account | SurveyMonkey";
		$this->load->model('model_users');
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

	public function referrals() {
		$this->not_logged_in();

		$this->render_template('pages/profile/referrals', $this->data);

	}

	public function invite() {
		$this->not_logged_in();
		$this->data['title'] = 'Invite your Friends | SurveyMonkey';

		$this->render_template('pages/profile/invite', $this->data);
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
					redirect('profile/settings', 'refresh');
				}
			}
		}
	}
}
