<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bonuses extends Member_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_surveys');
		$this->load->model('model_reviews');
		$this->load->model('model_transcribe');
		$this->load->model('model_users');
		$this->load->model('model_config');
		$this->data["title"] = "SurveyVine!";
	}

	public function index() {
		$this->render_template('pages/bonus_activity', $this->data);
	}

	public function admin() {
		$this->render_admin('pages/admin/bonuses/index', $this->data);
	}
}
