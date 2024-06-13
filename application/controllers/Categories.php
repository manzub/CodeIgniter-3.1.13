<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_categories');

		$this->data['title'] = 'Categories';
	}

	public function index()
	{
		$this->not_logged_in();

		if (in_array('manageCategory', $this->permission)) {
			$this->render_admin('pages/admin/categories/index.php', $this->data);
		} else {
			redirect('dashboard', 'refresh');
		}
	}

	public function create()
	{
		if (!in_array('createCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('category_name', 'Category name', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'name' => $this->input->post('category_name'),
				'status' => $this->input->post('status'),
			);

			$create = $this->model_categories->create($data);
			if ($create == true) {
				$response['success'] = true;
				$response['messages'] = 'Succesfully created';
			} else {
				$response['success'] = false;
				$response['messages'] = 'Error in the database while creating the category information';
			}
		} else {
			$response['success'] = false;
			foreach ($_POST as $key => $value) {
				$response['messages'][$key] = form_error($key);
			}
		}

		echo json_encode($response);
	}

	public function fetchCategoryDataById($id)
	{
		if ($id) {
			$data = $this->model_categories->getCategoryById($id);
			echo json_encode($data);
		}

		return false;
	}

	public function readCategories()
	{
		$result = array('data' => array());

		if (in_array('manageCategory', $this->permission)) {
			$items = $this->model_categories->getAllCategories();
			foreach (array_slice($items, 1) as $key => $value) {
				// create list items
				$buttons = "";

				if (in_array('manageCategory', $this->permission)) {
					$buttons .= '<button type="button" class="btn btn-primary" style="margin-right:10px" onclick="editFunc(' . $value['id'] . ')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
					$buttons .= "<button onclick='removeFunc(" . $value['id'] . ")' data-toggle='modal' data-target='#removeModal' class='btn btn-danger'><i class='fa fa-trash'></i></button>";
				}

				$result['data'][$key] = array(
					$value['name'],
					$buttons
				);
			}
		} else {
			redirect('dashboard', 'refresh');
		}

		echo json_encode($result);
	}

	public function update($id = null)
	{
		if (!in_array('manageCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if ($id) {
			$this->form_validation->set_rules('edit_category_name', 'Category name', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'name' => $this->input->post('edit_category_name'),
					'status' => $this->input->post('edit_active'),
				);

				$update = $this->model_categories->update($data, $id);
				if ($update == true) {
					$response['success'] = true;
					$response['messages'] = 'Succesfully updated';
				} else {
					$response['success'] = false;
					$response['messages'] = 'Error in the database while updated the category information';
				}
			} else {
				$response['success'] = false;
				foreach ($_POST as $key => $value) {
					$response['messages'][$key] = form_error($key);
				}
			}
		} else {
			$response['success'] = false;
			$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	public function delete()
	{
		if (!in_array('manageCategory', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$cat_id = $this->input->post('cat_id');
		if ($cat_id) {
			$delete = $this->model_categories->removeCategory($cat_id);
			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the category information";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}
}
