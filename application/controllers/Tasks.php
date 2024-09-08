<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tasks extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	function getAllMembers()
	{
		$results = array();
		$users = $this->model_users->getAllUsers();
		foreach ($users as $value) {
			array_push($results, $value);
		}

		return $results;
	}

	public function dailyReminders()
	{
		$subject = "Wizz, from SurveyVine!";
		$users = $this->getAllMembers();
		foreach ($users as $value) {
			$message = "<div style='align-items:left'>
			<h1>Hi ".$value['username'].", new activities are available for you to complete</h1>
			<p>Some new activities have just been uploaded.\n</p>
			<p>Become one of the first to complete them to earn increased rewards.</p>
			<p>Dont waste any more time, get started now.</p>
			</div>";

			$this->send_email($value['email'], $subject, $message);
		}
	}

	public function deactivateStrikedUsers() {
		$users = $this->getAllMembers();
		foreach ($users as $value) {
			if (intval($value['strike']) >= 3) {
				$this->model_users->update($value['id'], array('status' => 'suspended'));
			}
		}
	}
}
