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

		$this->render_admin('pages/admin/dashboard', $this->data);
	}
}
