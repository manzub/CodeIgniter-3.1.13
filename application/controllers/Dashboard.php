<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->data['title'] = 'Dashboard';
	}

	public function index() {
		$this->not_logged_in();
		$group_name = $this->session->userdata('group_name');

		$this->data['is_admin'] = (strpos($group_name, 'admin') !== false);
		$this->data['group_name'] = $group_name;

		$this->render_admin('pages/admin/dashboard', $this->data);
	}
}
