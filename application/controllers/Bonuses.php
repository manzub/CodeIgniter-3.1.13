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
		$this->load->model('model_bonuses');
		$this->load->model('model_users');
		$this->load->model('model_config');
		$this->load->model('model_logs');
		$this->data["title"] = "SurveyVine!";
	}

	public function index()
	{
		$this->not_logged_in();

		$result = array();
		// available bonuses
		$available_bonuses = $this->model_bonuses->getAvailableBonuses();
		foreach ($available_bonuses as $key => $bonus_item) {
			$activity_title = "";
			$activity_type = "";
			$link = "";
			switch ($bonus_item['activity_type']) {
				case 'rv':
					$x = $this->model_reviews->getReviewItemById($bonus_item['review_id']);
					$activity_title = $x['tittle'];
					$activity_type = 'Review';
					$link = base_url('reviews/single/' . $x['slug']);
					break;
				case 'av':
					$x = $this->model_transcribe->getTranscribeItemById($bonus_item['transcribe_id']);
					$activity_title = $x['title'];
					$activity_type = 'Transcribe';
					$link = base_url('transcribe/single/' . $x['slug']);
					break;
				default:
					$x = $this->model_surveys->getSurveyItemById($bonus_item['survey_id']);
					$activity_title = $x['title'];
					$activity_type = 'Survey';
					$link = base_url('surveys/single/' . $x['slug']);
					break;
			}

			$result[$key] = array(
				'title' => $activity_title,
				'activity_type' => $activity_type,
				'reward_points' => $bonus_item['reward_points'],
				'link' => $link
			);
		}

		$this->data['bonuses'] = $result;

		$this->render_template('pages/bonus_activity', $this->data);
	}

	public function readBonuses()
	{
		$result = array('data' => array());

		if (in_array('manageBonus', $this->permission)) {
			$items = $this->model_bonuses->getAllBonuses();
			foreach ($items as $key => $value) {
				// title, activity_type, reward_points, buttons
				$activity_type = "";
				$activity_title = "";
				switch ($value['activity_type']) {
					case 'rv':
						$x = $this->model_reviews->getReviewItemById($value['review_id']);
						$activity_title = $x['title'];
						$activity_type = 'Review item';
						break;
					case 'av':
						$x = $this->model_transcribe->getTranscribeItemById($value['transcribe_id']);
						$activity_title = $x['title'];
						$activity_type = 'Transcribe Item';
						break;
					default:
						# default survey item
						$x = $this->model_surveys->getSurveyItemById($value['survey_id']);
						$activity_title = $x['title'];
						$activity_type = 'Survey Item';
						break;
				}

				$buttons = "";
				if (in_array('manageBonus', $this->permission)) {
					// no edit button
					$buttons .= "<button onclick='removeFunc(" . $value['id'] . ")' data-toggle='modal' data-target='#removeModal' class='btn btn-danger'><i class='fa fa-trash'></i></button>";
				}

				$result['data'][$key] = array(
					$activity_title,
					$activity_type,
					$value['reward_points'] . "SB",
					$buttons
				);
			}
		}

		// no redirect jsut display empty
		echo json_encode($result);
	}


	// admin functions

	public function admin()
	{
		$this->not_logged_in();

		$this->render_admin('pages/admin/bonuses/index', $this->data);
	}

	public function create()
	{
		$this->not_logged_in();

		$user_id = $this->session->userdata('id');

		if (in_array('createBonus', $this->permission)) {
			# set form_rules, validate create, display view
			// required item ids and reward_points
			$this->form_validation->set_rules('activity_type', 'Activity Type', 'trim|required');
			$this->form_validation->set_rules('activity_id', 'Activity ID', 'required');
			$this->form_validation->set_rules('reward_points', 'Reward Points', 'required');
			if ($this->form_validation->run() == TRUE) {
				// CODE, update item category too
				$activity_id = $this->input->post('activity_id');
				$activity_type = $this->input->post('activity_type');
				$reward_points = $this->input->post('reward_points');
				$data = array('status' => 'available', 'activity_type' => $activity_type, 'reward_points' => $reward_points, 'created_by' => $user_id);

				// update category item for that activity and update activity_id in data
				switch ($activity_type) {
					case 'rv':
						$item = $this->model_reviews->getReviewItemById($activity_id);
						$categories = explode(',', $item['category']);
						array_push($categories, '1');
						$this->model_reviews->updateReviewItem($activity_id, array('category' => implode(',', $categories)));
						$data['review_id'] = $activity_id;
						break;
					case 'av':
						$item = $this->model_transcribe->getTranscribeItemById($activity_id);
						$categories = explode(',', $item['category']);
						array_push($categories, '1');
						$this->model_transcribe->updateTranscribeItem($activity_id, array('category' => implode(',', $categories)));
						$data['transcribe_id'] = $activity_id;
						break;
					default:
						$item = $this->model_surveys->getSurveyItemById($activity_id);
						$categories = explode(',', $item['category']);
						array_push($categories, '1');
						$this->model_surveys->updateSurveyItem($activity_id, array('category' => implode(',', $categories)));
						$data['survey_id'] = $activity_id;
						break;
				}

				$created = $this->model_bonuses->create($data);
				if ($created) {
					// log activity
					$log = array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Item Created', 'message' => 'Created New Bonus Item');
					$this->model_logs->logActivity($log);
					// redirect to reviews dashboard
					$this->session->set_flashdata('alert', array('title' => 'Item Created', 'classname' => 'alert-success', 'message' => 'Successfully created new bonus item'));
				} else {
					$this->session->set_flashdata('alert', array('title' => 'Error Occurred', 'classname' => 'alert-warning', 'message' => 'Could not create item.'));
				}
				redirect('bonuses/admin', 'refresh');
			} else {
				// load all activity items
				$this->data['all_surveys'] = $this->model_surveys->getAllSurveyItems();;
				$this->data['all_reviews'] = $this->model_reviews->getAllReviewItems();
				$this->data['all_transcribe'] = $this->model_transcribe->getAllTranscribeItems();

				$this->render_admin('pages/admin/bonuses/create', $this->data);
			}
		} else {
			redirect('bonuses/admin', 'refresh');
		}
	}

	public function delete()
	{
		if (!in_array('manageBonus', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$bonus_id = $this->input->post('bonus_id');
		if ($bonus_id) {
			$delete = $this->model_bonuses->remove($bonus_id);
			if ($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";
			} else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing this item";
			}
		} else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}
}
