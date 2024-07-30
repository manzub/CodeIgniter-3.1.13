<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_auth');
		$this->load->model('model_users');
		$this->load->model('model_groups');
		$this->load->model('model_logs');
		$this->load->model('model_config');
		$this->load->model('model_referrals');
	}

	public function login()
	{
		$this->logged_in();

		$this->data['title'] = 'Login | SurveyMonkey!';
		$this->form_validation->set_rules('name_email', 'Username/Email', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$user_exists = FALSE;
			$username_email = $this->input->post('name_email');
			if (filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
				$user_exists = $this->model_auth->check_email($username_email);
			} else {
				$user_exists = $this->model_auth->check_username($username_email);
			}

			if ($user_exists == TRUE) {
				$login = $this->model_auth->login($username_email, $this->input->post('password'));

				if ($login) {
					$this->model_users->update($login['id'], array('last_login' => strtotime('now')));
					// TODO: check account status
					if ($login['status'] == 'active') {
						// create session data
						$logged_in_sess = array('id' => $login['id'], 'username' => $login['username'], 'email' => $login['email'], 'logged_in' => TRUE);
						$this->session->set_userdata($logged_in_sess);
						$this->session->set_userdata('curr_status', $this->user_status['logged_in']);
						// log activity
						$activity = array('user_id' => $login['id'], 'activity_code' => '0', 'activity' => 'Login Successful', 'message' => 'Welcome!');
						$this->model_logs->logActivity($activity);
						// redirect
	
						$group_data = $this->model_groups->getUserGroupByUserId($login['id']);
	
						// get admin users
						$admins = $this->model_config->getConfigByName('admin_accounts');
						$admin_arr = unserialize($admins['value']);
						if (in_array($group_data['group_name'], $admin_arr)) {
							redirect('dashboard', 'refresh');
						} else {
							redirect('home', 'refresh');
						}
					} else {
						$message = $login['status'] == 'pending' ? 'Your account is pending activation.' : 'Your account has been '.$login['status'];
						$this->sesion->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error Occurred', 'message' => $message));
					}
				} else {
					$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'Incorrect username/password combination.', 'title' => 'Oops an error occured'));
					// log activity
					$activity = array('user_id' => $user_id, 'activity_code' => '0', 'activity' => 'Failed Login', 'message' => 'Incorrect username/password combination.');
					$this->model_logs->logActivity($activity);
				}
			} else {
				$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'Email does not exist.', 'title' => 'Oops an error occured'));
			}
		}


		$this->render_template('pages/login', $this->data);
	}

	public function signup($ref_code = null)
	{
		$this->logged_in();

		$this->data['title'] = "Create an account | SurveyMonkey";
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$username_exists = $this->model_auth->check_username($this->input->post('username'));
			if (!$username_exists) {
				$email_exists = $this->model_auth->check_email($this->input->post('email'));
				if (!$email_exists) {
					// valid username and email
					// create user
					$password_hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
					$group_data = $this->model_groups->getGroupByGroupName('member');
					$data = array('username' => $this->input->post('username'), 'password' => $password_hash, 'email' => $this->input->post('email'), 'user_group' => $group_data['id']);

					$raw_ref_code = $this->input->post('ref_code') != '' ? $this->input->post('ref_code') : $ref_code;
					$ref_id = null;
					if (!in_array($raw_ref_code, array('', null))) {
						// validate ref_code
						$ref_user = $this->model_users->getUserByRefCode($raw_ref_code);
						if ($ref_user['username']) {
							// log referral -> invited, active
							$saved_ref = $this->model_referrals->referUser(array('ref_code' => $raw_ref_code, 'email' => $this->input->post('email'), 'status' => 'invited'));
							$my_ref_code = $this->generate_ref_code(8);
							$data = array_merge($data, array('referred_by' => $raw_ref_code, 'ref_code' => $my_ref_code));
							$ref_id = $saved_ref;
						}
					}

					$signup = $this->model_users->create($data);

					if ($signup) {
						if (!in_array($raw_ref_code, array('', null))) {
							// edit ref info -> save user_id
							$this->model_referrals->updateReferral($ref_id, array('user_id' => $signup, 'status' => 'active'));
						}
						// log activity
						$activity = array('user_id' => $signup, 'activity_code' => '0', 'activity' => 'Created User Account', 'message' => 'Welcome to SurveyMonkey!');
						$this->model_logs->logActivity($activity);
						// TODO: OTP codes
						$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Congratulations', 'message' => 'Sign in to continue'));
						redirect('auth/login', 'refresh');
					}
				}
			}
		}

		$this->render_template('pages/signup', $this->data);
	}

	public function logout()
	{
		$this->not_logged_in();

		$this->session->sess_destroy();
		redirect('home', 'refresh');
	}

	public function deactivate_user()
	{
		$this->not_logged_in();

		$user_id = $this->session->userdata('id');
		// TODO: reactivate user account
		$update = $this->model_users->update($user_id, array('status' => 'deactivated'));
		if ($update) {
			// log activity
			$activity = array('user_id' => $user_id, 'activity_code' => '0', 'activity' => 'Deactivated User', 'message' => 'Sad to see you leave, we do hope you come back soon!');
			$this->model_logs->logActivity($activity);
			// destroy session
			$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Done', 'message' => 'You have successfully deactivated your account'));
			$this->session->sess_destroy();
			redirect('auth/login', 'refresh');
		}
	}
}
