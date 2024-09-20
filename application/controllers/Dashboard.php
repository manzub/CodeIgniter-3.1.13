<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_users');
		$this->load->model('model_transactions');

		$this->data['title'] = 'Dashboard';
	}

	public function index()
	{
		$this->not_logged_in();
		$group_name = $this->session->userdata('group_name');
		$user_id = $this->session->userdata('id');

		$this->data['is_admin'] = (strpos($group_name, 'admin') !== false);
		$this->data['group_name'] = $group_name;

		// user transactions
		$user_transactions = $this->user_transactions();
		$this->data['pending_transactions'] = $user_transactions['pending_transactions'];
		$this->data['done_transactions'] = $user_transactions['done_transactions'];

		// mod pending review & transcribe activities
		$this->data['pending_completed_transcribe'] = $this->pending_completed_transcribe_count();
		$this->data['pending_completed_reviews'] = $this->pending_completed_review_count();

		// publisher counts
		$this->data['surveys_all'] = count($this->model_surveys->getSurveyItemsCreatedBy($user_id));
		$this->data['reviews_all'] = count($this->model_reviews->getReviewItemsCreatedBy($user_id));
		$this->data['transcribe_all'] = count($this->model_transcribe->getTranscribeItemsCreatedBy($user_id));

		// admin stats
		$this->data['admin_surveys_all'] = count($this->model_surveys->getAllSurveyItems());
		$this->data['admin_reviews_all'] = count($this->model_reviews->getAllReviewItems());
		$this->data['admin_transcribe_all'] = count($this->model_transcribe->getAllTranscribeItems());

		$all_withdrawals = $this->model_transactions->getAllTransactions();
		$this->data['admin_withdrawals_all'] = count($all_withdrawals);

		$completed_payouts = 0;
		foreach ($all_withdrawals as $value) {
			if ($value['status'] == 1) {
				$completed_payouts += 1;
			}
		}
		$this->data['admin_completed_payouts'] = $completed_payouts;

		$this->data['total_users'] = $this->model_users->countTotalUsers($user_id);

		$this->render_admin('pages/admin/dashboard', $this->data);
	}

	// misc functions

	public function user_transactions()
	{
		$user_id = $this->session->userdata('id');
		$all_transactions = $this->model_transactions->getUserTransactions($user_id);
		$pending_transactions = 0;
		$done_transactions = 0;
		if (!empty($all_transactions)) {
			"Dsds";
			foreach ($all_transactions as $value) {
				if ($value['status'] == '0') {
					$pending_transactions += 1;
				} else if ($value['status'] == '1') {
					$done_transactions += 1;
				}
			}
		}

		return array('pending_transactions' => $pending_transactions, 'done_transactions' => $done_transactions);
	}

	public function pending_completed_transcribe_count()
	{
		$pending_completed = 0;
		$all_transcribe = $this->model_transcribe->getCompletedAll();
		if (!empty($all_transcribe)) {
			foreach ($all_transcribe as $value) {
				if ($value['status'] == 'pending') {
					$pending_completed += 1;
				}
			}
		}

		return $pending_completed;
	}

	public function pending_completed_review_count() {
		$pending_completed = 0;
		$all_reviews = $this->model_reviews->getCompletedAll();
		if (!empty($all_reviews)) {
			foreach ($all_reviews as $value) {
				if ($value['status'] == 'pending') {
					$pending_completed += 1;
				}
			}
		}

		return $pending_completed;
	}
}
