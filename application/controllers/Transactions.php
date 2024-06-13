<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_transactions');
		$this->load->model('model_users');
		$this->load->model('model_logs');

		$this->data['title'] = 'Transactions';
	}

	public function index()
	{
		$this->not_logged_in();

		if (in_array('manageTransaction', $this->permission)) {
			$this->render_admin('pages/admin/transactions/index.php', $this->data);
		} else {
			redirect('dashboard', 'refresh');
		}
	}

	public function fetchTransactions()
	{
		$result = array('data' => array());

		$data = $this->model_transactions->getAllTransactions();
		foreach ($data as $key => $value) {
			// Customer Name, Coins Requested,	Currency,	Amounts Worth,	Bank Name,	Bank Information,	Paid Status,	Action
			$customer = $this->model_users->getUserById($data['user_id']);

			$date = date('d-m-Y', $value['date_created']);
			$time = date('h:i a', $value['date_created']);

			$date_time = $date . ' ' . $time;

			// buttons
			$buttons = "";
			if (in_array('manageOrder', $this->permission)) {
				$buttons .= '<a target="__blank" href="' . base_url('transactions/printDiv/' . $value['id']) . '" class="btn btn-default"><i class="fa fa-print"></i></a>';
				$buttons .= ' <a href="' . base_url('transactions/update/' . $value['id']) . '" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc(' . $value['id'] . ')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			// load bank information
			$customer_bank_info = $this->model_users->getUserAccountsById($data['user_id']);
			$bank_name = $customer_bank_info['bank'] . " - " . $customer_bank_info['account_holder'];
			$bank_info = $customer_bank_info['sort_code'] . " - " . $customer_bank_info['account_no'];

			if ($value['status'] == 1) {
				$paid_status = '<span class="label label-success">Paid</span>';
			} else {
				$paid_status = '<span class="label label-warning">Not Paid</span>';
			}

			$result['data'][$key] = array(
				$customer['full_name'],
				$data['coins_requested'],
				$date_time,
				$data['value'] . $data['currency'],
				$bank_name,
				$bank_info,
				$paid_status,
				$buttons
			);
		}

		echo json_encode($result);
	}

	public function update($trnx_id = null)
	{
		// change paid/unpaid status
		if (!in_array('manageOrder', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$user_id = $this->session->userdata('id');
		$this->data['title'] = 'Update Transaction';

		if ($trnx_id == null) {
			redirect('transactions', 'refresh');
		}

		$this->form_validation->set_rules('paid_status', 'Status', 'required');
		if ($this->form_validation->run() == TRUE) {
			$data = array('status' => $this->input->post('paid_status'));
			$update = $this->model_transactions->update($trnx_id, $data);

			if ($update == true) {
				$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Successfully updated', 'message' => 'Successfully updated transaction'));
				$this->model_logs->logActivity(array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Transactions', 'message' => 'Successfully updated transaction'));
				redirect('transactions/update/' . $trnx_id, 'refresh');
			} else {
				$this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'title' => 'Error occurred!!', 'message' => 'could not update transaction'));
				redirect('transactions/update/' . $trnx_id, 'refresh');
			}
		} else {
			$transaction_item = $this->model_transactions->getTransactionById($trnx_id);

			$this->render_admin('pages/admin/transactions/edit.php', $this->data);
		}
	}

	public function remove()
	{
		if (!in_array('manageOrder', $this->permission)) {
			redirect('dashbaord', 'refresh');
		}

		$user_id = $this->session->userdata('id');
		$trnx_id = $this->input->post('trnx_id');

		$response = array();
		if ($trnx_id) {
			$delete = $this->model_transactions->remove($trnx_id);
			$log = array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Delete Transaction', 'message' => '');
			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";
				$this->model_logs->logActivity(array_merge(array('message' => $response['messages']), $log));
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the product information";
				$this->model_logs->logActivity(array_merge(array('message' => $response['messages']), $log));
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}
}
