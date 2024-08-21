<?php

class Groups extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['title'] = 'Groups';

		$this->load->model('model_users');
		$this->load->model('model_groups');
		$this->load->model('model_config');
		$this->load->model('model_logs');
	}

	public function index()
	{
		if (!in_array('manageGroup', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$groups_data = $this->model_groups->getGroupData();
		$this->data['groups_data'] = $groups_data;

		$this->render_admin('pages/admin/groups/index', $this->data);
	}

	public function create()
	{

		if (!in_array('createGroup', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->form_validation->set_rules('group_name', 'Group name', 'required');

		if ($this->form_validation->run() == TRUE) {
			// true case
			$permission = serialize($this->input->post('permission'));

			$data = array(
				'group_name' => $this->input->post('group_name'),
				'permissions' => $permission
			);

			$create = $this->model_groups->create($data);
			if ($create == true) {
				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Successfully created', 'message' => 'Successfully created new group.'));
				$this->model_logs->logActivity(array('user_id' => $this->session->userdata('id'), 'activity_code' => '5', 'activity' => 'Created new group', 'message' => 'successfully created new group.'));
				redirect('groups', 'refresh');
			} else {
				$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error occurred', 'message' => 'An error occurred could not create group data.'));
				redirect('groups/create', 'refresh');
			}
		} else {
			$this->render_admin('pages/admin/groups/create', $this->data);
		}
	}

	public function edit($id = null)
	{
		if (!in_array('manageGroup', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		if ($id) {
			$this->form_validation->set_rules('group_name', 'Group name', 'required');
			if ($this->form_validation->run() == TRUE) {
				// true case
				$permission = serialize($this->input->post('permission'));

				$data = array(
					'group_name' => $this->input->post('group_name'),
					'permissions' => $permission
				);

				$update = $this->model_groups->edit($data, $id);
				if ($update == true) {
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Successfully updated', 'message' => 'Successfully updated group.'));
					$this->model_logs->logActivity(array('user_id' => $this->session->userdata('id'), 'activity_code' => '5', 'activity' => 'Updated group #' . $id, 'message' => 'successfully updated group #' . $id));
					redirect('groups', 'refresh');
				} else {
					$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error occurred', 'message' => 'An error occurred while editing data.'));
					redirect('groups/edit/' . $id, 'refresh');
				}
			} else {
				// false case
				$group_data = $this->model_groups->getGroupData($id);
				$this->data['group_data'] = $group_data;
				$this->render_admin('pages/admin/groups/edit', $this->data);
			}
		}
	}

	public function delete($id)
	{
		if (!in_array('manageGroup', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		if ($id) {
			if ($this->input->post('confirm')) {
				// do something
				$check = $this->model_groups->existInUserGroup($id);
				if ($check == true) {
					$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error occurred', 'message' => 'Unable to delete group with users assigned.'));
					redirect('groups', 'refresh');
				} else {
					$delete = $this->model_groups->delete($id);
					if ($delete == true) {
						$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Successfully deleted', 'message' => 'Successfully deleted group.'));
						$this->model_logs->logActivity(array('user_id' => $this->session->userdata('id'), 'activity_code' => '5', 'activity' => 'Deleted group #' . $id, 'message' => 'successfully deleted group #' . $id));
						redirect('groups', 'refresh');
					} else {
						$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Error occurred', 'message' => 'An error occurred while deleting group data.'));
						redirect('groups/delete/' . $id, 'refresh');
					}
				}
			} else {
				$this->data['id'] = $id;
				$this->render_admin('pages/admin/groups/delete', $this->data);
			}
		}
	}
}
