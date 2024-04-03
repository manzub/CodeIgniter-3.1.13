<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Main_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_auth');
		$this->load->model('model_users');
		$this->load->model('model_groups');
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
					// create session data
					$logged_in_sess = array('id' => $login['id'], 'username' => $login['username'], 'email' => $login['email'], 'logged_in' => TRUE);
					$this->session->set_userdata($logged_in_sess);
					$this->session->set_userdata('curr_status', $this->user_status['logged_in']);
					redirect('home', 'refresh');
				} else {
					$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'Incorrect username/password combination.', 'title' => 'Oops an error occured'));
				}
			} else {
				$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'Email does not exist.', 'title' => 'Oops an error occured'));
			}
		}

		$this->render_template('pages/login', $this->data);
	}

	public function signup()
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
					$signup = $this->model_users->create($data);

					if ($signup) {
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

	public function deactivate_user() {
		$this->not_logged_in();

		$user_id = $this->session->userdata('id');

		$update = $this->model_users->update($user_id, array('status' => 'deactivated'));
		if ($update) {
			$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Done', 'message' => 'You have successfully deactivated your account'));
			$this->session->sess_destroy();
			redirect('auth/login', 'refresh');
		}
	}
}
