<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reviews extends Member_Controller
{
	public function __construct()
	{
		parent::__construct();
		// load needed db configs
		$this->load->model('model_reviews');
		$this->load->model('model_categories');
		$this->load->model('model_users');
		$this->load->model('model_config');
		$this->load->model('model_logs');
		$this->data['title'] = "Reviews | SurveyMonkey";
		$this->data['per_page'] = 5;
	}

	public function completeReviewItem($slug = null)
	{
		$user_id = $this->session->userdata('id');
		if ($slug == null) {
			redirect('reviews', 'refresh');
		}

		$this->form_validation->set_rules('review_text', 'Text', 'trim|required');
		$this->form_validation->set_rules('review_rating', 'Rating', 'trim|required');

		if ($this->form_validation->run() == TRUE) {
			// check permission
			if (in_array('completeReview', $this->permission)) {
				// check if user has already completed item and limit hasnt reached
				$review_item = $this->model_reviews->getReviewItemBySlug($slug);

				if (!empty($review_item)) {
					// check if item hasnt expired
					if ($review_item['status'] == 'available') {
						// limit_per_user hasnt reached
						$completed_items = $this->model_reviews->getCompletedByReviewId($user_id, $review_item['id']);
						if (!empty($completed_items)) {
							if (count($completed_items) >= $review_item['limit_per_user']) {
								$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Oops', 'message' => 'You have already completed this activity'));
								redirect('reviews', 'refresh');
							}
						}

						// save completed item
						$review_text = htmlspecialchars($this->input->post('review_text'));
						$review_rating = $this->input->post('review_rating');
						$has_watched = $this->input->post('review_watched') || 0;
						$data = array('review_id' => $review_item['id'], 'review_rating' => $review_rating, 'review_text' => $review_text, 'has_watched' => $has_watched, 'completed_by' => $user_id, 'points_earned' => '0');
						$completed = $this->model_reviews->completeItem($data);

						if ($completed) {
							// log activity
							$activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Reviewed 1 Item', 'message' => 'Well done!');
							$this->model_logs->logActivity($activity);
							// redirect to claims page
							redirect('reviews/completed/' . $slug, 'refresh');
						}
					}
				}
			}
		} else {
			$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Leave a Rating/Review', 'message' => 'Please complete all activity'));
			redirect('reviews/single/' . $slug, 'refresh');
		}
	}

	// page functions

	public function index($page = 1)
	{
		$user_id = $this->session->userdata('id');
		$group_name = $this->session->userdata('group_name');

		// get available review_items
		$activities_page = $this->model_reviews->getMyAvailableActivities($group_name, $user_id, true, $page - 1, $this->data['per_page']);
		$this->data['activities'] = $activities_page;

		// create navigation
		$available_items = $this->model_reviews->getMyAvailableActivities($group_name, $user_id);
		$count_available_rv = count($available_items);
		$this->data['pages'] = round($count_available_rv / $this->data['per_page']);
		$this->data['page'] = $page;

		$this->render_template('pages/reviews/index', $this->data);
	}

	public function single($slug = null)
	{
		if ($slug == null) {
			show_404('Page Not Found');
		}

		// completeReview - can review item
		if (in_array('completeReview', $this->permission)) {
			// render review
			$review_item = $this->model_reviews->getReviewItemBySlug($slug);

			if (!empty($review_item)) {
				$categories = array();
				$cat_arr = explode(",", $review_item['category']);
				foreach ($cat_arr as $key => $item) {
					$cat_item = $this->model_categories->getCategoryById($item);
					array_push($categories, $cat_item['value']);
				}

				$this->data['title'] = $review_item['title'] . " | SurveyMonkey";
				$this->data['review_item'] = $review_item;
				$this->data['categories'] = $categories;
				$this->render_template('pages/reviews/single', $this->data);
			} else {
				show_404('Page Not Found');
			}
		} else {
			$this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Oops - you don\'t have permission', 'message' => 'Why miss out, create a member account today and completing activities.'));
			redirect('reviews', 'refresh');
		}
	}

	public function completed($slug = null)
	{
		$this->not_logged_in();
		$user_id = $this->session->userdata('id');

		if ($slug == null) {
			redirect('reviews', 'refresh');
		}

		if (in_array('earnRewards', $this->permission)) {
			$review_item = $this->model_reviews->getReviewItemBySlug($slug);
			$completed_items = $this->model_reviews->getCompletedByReviewId($user_id, $review_item['id']);

			if (!empty($review_item) && !empty($completed_items)) {
				$last_comp_item = $completed_items[count($completed_items) - 1];
				if (intval($last_comp_item['points_earned']) != 0) {
					redirect('reviews', 'refresh');
				}

				// on click claim button
				$this->form_validation->set_rules('completed', 'slug', 'required');
				if ($this->form_validation->run() == TRUE) {
					// TODO: member multiply
					$points_earned = $review_item['reward_points'];

					// check if daily activity
					$dl_list = $this->session->userdata('daily_activity');
					$dl_slug = array_map(function ($o) {
						return $o['slug'];
					}, $dl_list);
					if (!empty($dl_slug)) {
						if (in_array($review_item['slug'], $dl_slug)) {
							$daily_activity_config = $this->model_config->getConfigByName('daily_activity_reward');
							$points_earned = intval($daily_activity_config['value']);
							$key = array_search($review_item['slug'], $dl_slug);
							$dl_list[$key]['status'] = 'completed';
							$this->session->set_userdata('daily_activity', $dl_list);
						}
					}

					$data = array('points_earned' => $points_earned);

					// update last comp item
					$update = $this->model_reviews->updateCompletedItem($last_comp_item['id'], $data);
					if ($update) {
						// reward ref parent user
						$my_account = $this->model_users->getuserById($user_id);
						if (!in_array($my_account['referred_by'], array(null, 'NULL'))) {
							$my_referrer = $this->model_users->getUserByRefCode($my_account['referred_by']);
							// load interest config
							$reward_interest_config = $this->model_config->getConfigByName('ref_reward_interest');
							$reward_interest = intval($reward_interest_config['value']);
							$interest_earned = $points_earned / $reward_interest;

							$this->model_users->logClaimedReward($my_referrer['id'], array('user_id' => $my_referrer['id'], 'reward_earned' => $interest_earned, 'type' => 'ref_interest', 'streak' => '0'));
						}

						// reduce global limit
						if ($review_item['global_limit'] != NULL && intval($review_item['global_limit']) > 0) {
							$global_limit = intval($review_item['global_limit']) - 1;
							$this->model_reviews->updateReviewItem($review_item['id'], array('global_limit' => $global_limit));
						}
					}

					$total_earned = round($points_earned * count($completed_items));
					$this->model_users->logClaimedReward($user_id, array('user_id' => $user_id, 'review_id' => $review_item['id'], 'reward_earned' => $total_earned, 'type' => 'completed_activity', 'streak' => '0'));
					$this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $total_earned, 'title' => 'Completed'));
					// log activity
					$activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Earned Reward', 'message' => 'Congratulations! Earned' . $total_earned);
					$this->model_logs->logActivity($activity);
					redirect('reviews', 'refresh');
				}

				$this->data['title'] = "Claim your rewards - " . $review_item['title'] . " | SurveyMonkey!";
				$this->data['review_item'] = $review_item;
				$this->render_template('pages/reviews/completed', $this->data);
			} else {
				redirect('reviews', 'refresh');
			}
		} else {
			redirect('reviews', 'refresh');
		}
	}
}
