<?php

class Users extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['title'] = 'Users';


		$this->load->model('model_users');
		$this->load->model('model_groups');
		$this->load->model('model_config');
		$this->load->model('model_logs');
	}

	public function index()
	{
		$this->not_logged_in();

		if (!in_array('manageUser', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$user_data = $this->model_users->getAllUser();

		$result = array();
		foreach ($user_data as $k => $v) {
			$result[$k]['user_info'] = $v;

			$group = $this->model_users->getGroupData($v['user_group']);
			$result[$k]['user_group'] = $group;
		}

		$this->data['user_data'] = $result;

		$this->render_admin('pages/admin/users/index', $this->data);
	}

	public function create()
	{
		if (!in_array('createUser', $this->permission)) {
			redirect('users', 'refresh');
		}

		$this->form_validation->set_rules('groups', 'Group', 'required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|is_unique[users.username]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('cpassword', 'Confirm password', 'trim|required|matches[password]');

		if ($this->form_validation->run() == TRUE) {
			// true case
			$password = $this->password_hash($this->input->post('password'));
			$data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'password' => $password,
				'user_group' => $this->input->post('groups')
			);

			$create = $this->model_users->create($data);
			if ($create == true) {
				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'successfully created user', 'title' => 'Successfully Created!'));
				redirect('users/', 'refresh');
			} else {
				$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'An error occurred', 'title' => 'Error Occurred!!'));
				redirect('users/create', 'refresh');
			}
		} else {
			$group_data = $this->model_groups->getGroupData();
			$this->data['group_data'] = $group_data;

			$this->render_admin('pages/admin/users/create', $this->data);
		}
	}

	public function edit($id = null)
	{
		$this->not_logged_in();

		if (!in_array('manageUser', $this->permission)) {
			redirect('users', 'refresh');
		}

		if ($id) {
			$this->form_validation->set_rules('groups', 'Group', 'required');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required');
			$this->form_validation->set_rules('status', 'Status', 'trim|required');

			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'username' => $this->input->post('username'),
					'email' => $this->input->post('email'),
					'user_group' => $this->input->post('groups'),
					'status' => $this->input->post('status'),
				);
				// no password change
				if (empty($this->input->post('password')) && empty($this->input->post('cpassword'))) {
					$update = $this->model_users->update($id, $data);
					if ($update) {
						$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'successfully updated user', 'title' => 'Successfully updated!'));
						redirect('users/', 'refresh');
					} else {
						$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'An error occurred', 'title' => 'Error Occurred!!'));
						redirect('users/edit' . $id, 'refresh');
					}
				} else {
					// is password change
					$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
					$this->form_validation->set_rules('cpassword', 'Confirm password', 'trim|required|matches[password]');

					if ($this->form_validation->run() == TRUE) {
						$password = $this->password_hash($this->input->post('password'));
						$data['password'] = $password;

						$update = $this->model_users->update($id, $data);
						if ($update) {
							$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'successfully updated user', 'title' => 'Successfully updated!'));
							redirect('users/', 'refresh');
						} else {
							$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'An error occurred', 'title' => 'Error Occurred!!'));
							redirect('users/edit' . $id, 'refresh');
						}
					} else {
						// false case
						$user_data = $this->model_users->getUserById($id);
						$groups = $this->model_users->getGroupData($user_data['user_group']);

						$this->data['user_data'] = $user_data;
						$this->data['user_group'] = $groups;

						$group_data = $this->model_groups->getGroupData();
						$this->data['group_data'] = $group_data;

						$user_status = $this->model_config->getConfigByName('user_account_types');
						$this->data['user_status'] = unserialize($user_status['value']);

						$this->render_admin('pages/admin/users/edit', $this->data);
					}
				}
			} else {
				// false case
				$user_data = $this->model_users->getUserById($id);
				$groups = $this->model_users->getGroupData($user_data['user_group']);

				$this->data['user_data'] = $user_data;
				$this->data['user_group'] = $groups;

				$group_data = $this->model_groups->getGroupData();
				$this->data['group_data'] = $group_data;

				$user_status = $this->model_config->getConfigByName('user_account_types');
				$this->data['user_status'] = unserialize($user_status['value']);

				$this->render_admin('pages/admin/users/edit', $this->data);
			}
		}
	}

	public function delete($id = null)
	{
		$this->not_logged_in();

		if (!in_array('manageUser', $this->permission)) {
			redirect('users', 'refresh');
		}

		if ($id) {
			if ($this->input->post('confirm')) {
				$delete = $this->model_users->delete($id);
				if ($delete == true) {
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Successfully deleted user!.', 'title' => 'Success!'));
					redirect('users', 'refresh');
				} else {
					$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'An error occurred while deleting user', 'title' => 'Error Occurred!'));
					redirect('users/delete/' . $id, 'refresh');
				}
			} else {
				$this->data['id'] = $id;
				$this->render_admin('pages/admin/users/delete', $this->data);
			}
		}
	}

	public function strike($user_id)
	{
		$this->not_logged_in();

		if ($user_id != null) {
			// add a strike to user account
			if (in_array('manageUser', $this->permission)) {
				$this_user = $this->model_users->getUserById($user_id);
				if (!empty($this_user)) {
					$strike = intval($this_user['strike']) + 1;
					$updated = $this->model_users->update($user_id, array('strike' => $strike));
					if ($updated) {
						$this->model_logs->logActivity(array('user_id' => $user_id, 'activity' => 'Strike', 'activity_code' => '4', 'message' => 'A strike has been added to your account. please contact support to review this.'));
						$this->model_logs->logActivity(array('user_id' => $this->session->userdata('id'), 'activity' => 'Strike User', 'activity_code' => '4', 'message' => 'Added strike to user account.'));
						$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Added strike to user account', 'message' => 'added strike to user account.'));
						redirect('users', 'refresh');
					} else {
						$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'An error occurred', 'message' => 'An error occurred'));
					}
				} else {
					$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Invalid user id provided', 'message' => 'User not found'));
					redirect('users', 'refresh');
				}
			} else {
				$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Access Denied', 'message' => 'You don\'t have permissions to complete this action.'));
				redirect('users', 'refresh');
			}
		} else {
			redirect('users', 'refresh');
		}
	}

	public function remove_strike($user_id)
	{
		$this->not_logged_in();

		if ($user_id != null) {
			// add a strike to user account
			if (in_array('manageUser', $this->permission)) {
				$this_user = $this->model_users->getUserById($user_id);
				if (!empty($this_user)) {
					$strike = intval($this_user['strike']) > 0 ? intval($this_user['strike']) - 1 : 0;
					$updated = $this->model_users->update($user_id, array('strike' => $strike));
					if ($updated) {
						$this->model_logs->logActivity(array('user_id' => $user_id, 'activity' => 'Strike', 'activity_code' => '4', 'message' => 'A strike has been removed from your account.'));
						$this->model_logs->logActivity(array('user_id' => $this->session->userdata('id'), 'activity' => 'Strike User', 'activity_code' => '4', 'message' => 'Remvoed strike from user account.'));
						$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Removed strike from user account', 'message' => 'removed strike from user account.'));
						redirect('users', 'refresh');
					} else {
						$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'An error occurred', 'message' => 'An error occurred'));
					}
				} else {
					$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Invalid user id provided', 'message' => 'User not found'));
					redirect('users', 'refresh');
				}
			} else {
				$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Access Denied', 'message' => 'You don\'t have permissions to complete this action.'));
				redirect('users', 'refresh');
			}
		} else {
			redirect('users', 'refresh');
		}
	}

	// misc function
	public function password_hash($pass = null)
	{
		if ($pass) {
			$password_hash = password_hash($pass, PASSWORD_BCRYPT);
			return $password_hash;
		}
	}
}
