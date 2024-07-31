<?php 

class Users extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();
		
		$this->data['page_title'] = 'Users';
		

		$this->load->model('model_users');
		$this->load->model('model_groups');
		$this->load->model('model_config');
		$this->load->model('model_logs');
	}

	public function index() {
		$this->not_logged_in();

		$this->render_admin('pages/admin/users/index', $this->data);
	}
}
