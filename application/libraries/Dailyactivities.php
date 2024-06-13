<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dailyactivities
{

	protected $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
		// load models
		// create daily activities list
		$this->CI->load->model('model_surveys');
		$this->CI->load->model('model_reviews');
		$this->CI->load->model('model_transcribe');
		$this->CI->load->model('model_dailyactivity');
	}

	public function generate_daily_activity_list($user_id)
	{
		// get 5 available items each and randomise array
		$review_items = $this->CI->model_reviews->getMyAvailableActivities('member', $user_id, true);
		$survey_items = $this->CI->model_surveys->getAvailableSurveys('member', $user_id, true);
		$transcribe_items = $this->CI->model_transcribe->getMyAvailableActivities('member', $user_id, true);
		$all_activities = array_merge($review_items, $survey_items, $transcribe_items);
		// select 4 random items
		$rand_keys = array_rand($all_activities, 4);
		// create activity_list
		$activities_list = array();
		for ($i = 0; $i < count($rand_keys); $i++) {
			$current_item = $all_activities[$rand_keys[$i]];
			// array(slug, activity_type, status);
			$activity_type = substr($current_item['slug'], 0, 2);
			$item = array('slug' => $current_item['slug'], 'activity_type' => $activity_type, 'status' => 'available');
			array_push($activities_list, $item);
		}
		// save to db
		$data = array('user_id' => $user_id, 'activity_slug' => serialize($activities_list));
		$saved = $this->CI->model_dailyactivity->save_current_activity_list($data);
		if ($saved) {
			return serialize($activities_list);
		}

		return '';
	}

	public function has_completed_item($activities_list, $user_id)
	{
		$activities = $activities_list;
		// check all items if completed item exists.
		// mark item as completed if has
		foreach ($activities as $key => $item) {
			if ($item['status'] == 'available') {
				// array(slug, activity_type, status);
				if ($item['activity_type'] == 'sv') {
					$survey_item = $this->CI->model_surveys->getSurveyItemBySlug($item['slug']);
					if (!empty($survey_item)) {
						$completed_items = $this->CI->model_surveys->getCompletedBySurveyId($user_id, $survey_item['id']);
						if (!empty($completed_items)) {
							$item['status'] = 'completed';
						}
					}
				} elseif ($item['activity_type'] == 'rv') {
					$review_item = $this->CI->model_reviews->getReviewItemBySlug($item['slug']);
					if (!empty($review_item)) {
						$completed_items = $this->CI->model_reviews->getCompletedByReviewId($user_id, $review_item['id']);
						if (!empty($completed_items)) {
							$item['status'] = 'completed';
						}
					}
				} elseif ($item['activity_type'] == 'av') {
					$transcribe_item = $this->CI->model_transcribe->getTranscribeItemBySlug($item['slug']);
					if (!empty($transcribe_item)) {
						$completed_items = $this->CI->model_transcribe->getCompletedByTranscribeId($user_id, $transcribe_item['id']);
						if (!empty($completed_items)) {
							$item['status'] = 'completed';
						}
					}
				}
			}
			$activities[$key] = $item;
		}
		
		// set new session item
		return $activities;
	}

	public function daily_activity_html($activities_list)
	{
		$result = array();

		if (!empty($activities_list)) {
			foreach ($activities_list as $key => $activity) {
				if ($activity['status'] == 'available') {
					$item = null;
					$link = "";
					$ac_type = "";
					switch ($activity['activity_type']) {
						case 'rv':
							$ac_type = "Review";
							$item = $this->CI->model_reviews->getReviewItemBySlug($activity['slug']);
							$link = base_url('reviews/single/' . $activity['slug']);
							break;
						case 'av':
							$ac_type = "Transcribe";
							$item = $this->CI->model_transcribe->getTranscribeItemBySlug($activity['slug']);
							$link = base_url('transcribe/single.' . $activity['slug']);

						default:
							$ac_type = "Survey";
							$item = $this->CI->model_surveys->getSurveyItemBySlug($activity['slug']);
							$link = base_url('surveys/single/' . $activity['slug']);
							break;
					}

					if ($item != null) {
						$result[$key] = array('title' => $item['title'], 'link' => $link, 'type' => $ac_type);
					}
				}
			}
		}

		return $result;
	}
}
