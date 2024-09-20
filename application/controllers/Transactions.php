<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transactions extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_transactions');

		$this->data['title'] = 'Transactions';
	}

	public function index()
	{
		$this->not_logged_in();

		$this->render_admin('pages/admin/transactions/index.php', $this->data);
	}

	public function fetchTransactions()
	{
		$result = array('data' => array());

		if (in_array('manageTransaction', $this->permission)) {
			$data = $this->model_transactions->getAllTransactions();
		} else {
			$user_id = $this->session->userdata('id');
			$data = $this->model_transactions->getUserTransactions($user_id);
		}
		foreach ($data as $key => $value) {
			// Customer Name, Coins Requested,	Currency,	Amounts Worth,	Bank Name,	Bank Information,	Paid Status,	Action
			$customer = $this->model_users->getUserById($value['user_id']);

			$date = date('d-m-Y', strtotime($value['date_created']));
			$time = date('h:i a', strtotime($value['date_created']));

			$date_time = $date . ' ' . $time;

			// buttons
			$buttons = "";
			$buttons .= '<a target="__blank" href="' . base_url('transactions/printDiv/' . $value['id']) . '" class="btn btn-default"><i class="fa fa-print"></i></a>';
			if (in_array('manageOrder', $this->permission)) {
				$buttons .= ' <a href="' . base_url('transactions/update/' . $value['id']) . '" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc(' . $value['id'] . ')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			// load bank information
			$bank_name = "";
			$bank_info = "";

			$customer_bank_info = $this->model_users->getUserAccountByType($value['user_id'], $value['bank_type']);
			if (!empty($customer_bank_info)) {
				if ($customer_bank_info['type'] == 'paypal') {
					$bank_name = 'PayPal';
					$bank_info = $customer_bank_info['email'];
				} else {
					$bank_name = $customer_bank_info['bank'] . " - " . $customer_bank_info['account_holder'];
					$bank_info = $customer_bank_info['sort_code'] . " - " . $customer_bank_info['account_no'];
				}
			}

			if ($value['status'] == 1) {
				$paid_status = '<span class="label label-success">Completed</span>';
			} else {
				$paid_status = '<span class="label label-warning">Pending</span>';
			}

			$result['data'][$key] = array(
				$customer['username'],
				$value['coins_requested'],
				$date_time,
				$value['value'] . $value['currency'],
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
			$customer = $this->model_users->getUserById($transaction_item['created_by']);

			$this->data['customer'] = $customer;
			$this->data['transaction_item'] = $transaction_item;

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

	public function printDiv($id)
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		if ($id) {
			$transaction_item = $this->model_transactions->getTransactionById($id);
			if (!empty($transaction_item)) {
				// only show logged in user transactions unless manageOrder permission
				if ($transaction_item['user_id'] == $user_id || in_array('manageOrder', $this->permission)) {
					$transaction_date = date('d/m/Y', strtotime($transaction_item['date_created']));
					$completed_status = ($transaction_item['status'] == 1) ? 'Completed' : 'Pending';

					$customer = $this->model_users->getUserById($transaction_item['user_id']);
					// load bank information
					$bank_name = "";
					$bank_info = "";

					$customer_bank_info = $this->model_users->getUserAccountByType($transaction_item['user_id'], $transaction_item['bank_type']);
					if (!empty($customer_bank_info)) {
						if ($customer_bank_info['type'] == 'paypal') {
							$bank_name = 'PayPal';
							$bank_info = $customer_bank_info['email'];
						} else {
							$bank_name = $customer_bank_info['bank'] . " - " . $customer_bank_info['account_holder'];
							$bank_info = $customer_bank_info['sort_code'] . " - " . $customer_bank_info['account_no'];
						}
					}

					$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Surveyvine | Invoice</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') . '">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="' . base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') . '">
			  <link rel="stylesheet" href="' . base_url('assets/dist/css/AdminLTE.min.css') . '">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">SurveyVine
			          <small class="pull-right">Date: ' . $transaction_date . '</small>
			        </h2>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			        <b>Username:</b> ' . $customer['username'] . '<br>
			        <b>Bank:</b> ' . $bank_name . '<br>
			        <b>Bank Info:</b> ' . $bank_info . '<br>
			        <!-- <b>Address:</b> customer address <br />
			         <b>Phone:</b> customer phone -->
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <div class="row">
			      
			      <div class="col-xs-6 pull pull-right">

			        <div class="table-responsive">
			          <table class="table">';


					$html .= ' <tr>
			              <th>Amount in points (SB):</th>
			              <td>' . $transaction_item['coins_requested'] . '</td>
			            </tr>
			            <tr>
			              <th>'.$transaction_item['currency'].' Amount:</th>
			              <td>' . $transaction_item['value'] . '</td>
			            </tr>
			            <tr>
			              <th>Paid Status:</th>
			              <td>' . $completed_status . '</td>
			            </tr>
			          </table>
			        </div>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
		</body>
	</html>';

					echo $html;
				} else {
					redirect('dashboard', 'refresh');
				}
			}
		}
	}
}
