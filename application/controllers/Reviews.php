<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reviews extends Member_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->helper('my_helper.php');
    // load needed db configs
    $this->load->model('model_reviews');
    $this->load->model('model_categories');
    $this->load->model('model_bonuses');
		
    $this->data['title'] = "Reviews | SurveyVine";
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
            $data = array('review_id' => $review_item['id'], 'review_rating' => $review_rating, 'review_text' => $review_text, 'has_watched' => $has_watched, 'completed_by' => $user_id, 'points_earned' => '0', 'status' => 'pending');
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
    $this->no_admin();
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
    $this->no_admin();
    if ($slug == null) {
      show_404('Page Not Found');
    }

		if ($this->daily_limit_reached($this->session->userdata('id'))) {
			$this->session->set_flashdata('alert', array('message' => 'You have reached your max earnings for today', 'title' => 'Daily Limit Reached', 'classname' => 'alert-warning'));
			redirect('home', 'refresh');
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

        $this->data['title'] = $review_item['title'] . " | SurveyVine";
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
    $this->no_admin();
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
          $points_earned = intval($review_item['reward_points']);
          $data = array('points_earned' => $points_earned);

          // update last comp item
          $update = $this->model_reviews->updateCompletedItem($last_comp_item['id'], $data);
          if ($update) {
            // check if bonus item
            $categories = explode(',', $review_item['category']);
            if (in_array('1', $categories)) { // found as bonus_item
              $bonus_item = $this->model_bonuses->getBonusItemByCond(array('review_id' => $review_item['review_id']));
              $reward_points = intval($bonus_item['reward_points']);
              $points_earned =  $points_earned + $reward_points;
              $global_limit = intval($bonus_item['global_limit']) - 1;
              $status = $global_limit == 0 ? 'completed' : 'available';
              // update bonus item
              $this->model_bonuses->update($bonus_item['id'], array('global_limit' => $global_limit, 'status' => $status));
            }
            // reduce global limit
            if ($review_item['global_limit'] != NULL && intval($review_item['global_limit']) > 0) {
              $global_limit = intval($review_item['global_limit']) - 1;
              $this->model_reviews->updateReviewItem($review_item['review_id'], array('global_limit' => $global_limit));
            }
          }

          // reward points
          $total_earned = round($points_earned * count($completed_items));
          $this->reward_points($points_earned, $total_earned, $review_item['slug'], array('review_id' => $review_item['id'], 'type' => 'completed_activity', 'streak' => '0'));

          $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $total_earned, 'title' => 'Completed'));
          // log activity
          $activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Earned Reward', 'message' => 'Congratulations! Earned' . $total_earned);
          $this->model_logs->logActivity($activity);
          redirect('reviews', 'refresh');
        }

        $this->data['title'] = "Claim your rewards - " . $review_item['title'] . " | SurveyVine!";
        $this->data['review_item'] = $review_item;
        $this->render_template('pages/reviews/completed', $this->data);
      } else {
        redirect('reviews', 'refresh');
      }
    } else {
      redirect('reviews', 'refresh');
    }
  }


  // admin functions

  public function admin()
  {
    $this->not_logged_in();

    if (in_array('manageReview', $this->permission) || in_array('manageActivity', $this->permission)) {
      $this->render_admin('pages/admin/activities/reviews/index', $this->data);
    } else {
      redirect('dashboard', 'refresh');
    }
  }

  public function create()
  {
    $this->not_logged_in();
    $user_id = $this->session->userdata('id');

    if (in_array('createReview', $this->permission)) {

      $config['upload_path'] = './uploads/';
      $config['allowed_types'] = 'gif|jpg|png|jpeg';
      $this->load->library('upload', $config);

      $this->form_validation->set_rules('review_title', 'Title', 'trim|required');
      $this->form_validation->set_rules('short_desc', 'Short Description', 'trim|required');
      $this->form_validation->set_rules('categories[]', 'Categories', 'required');
      $this->form_validation->set_rules('is_movie', 'Is Movie', 'trim|required');
      $this->form_validation->set_rules('imdb_link', 'IMDB Link', 'trim');
      $this->form_validation->set_rules('limit_per_user', 'User Limits', 'trim|required');
      $this->form_validation->set_rules('global_limit', 'Global Limit', 'trim|required');
      $this->form_validation->set_rules('short_clip_link', 'Short Clip', 'trim');
      $this->form_validation->set_rules('thumbnail_links', 'Thumbnails', 'trim');

      if ($this->form_validation->run() == TRUE) {
        // check short_clip and thumbnail_links
        $thumbnails = array();
        if (isset($_FILES['thumbnails'])) {
          $thumb_files = $_FILES['thumbnails'];
          if (!empty($thumb_files)) {
            $img_desc = $this->reArrayFiles($thumb_files);
            foreach ($img_desc as $val) {
              $newname = time() . $val['name'];
              $new_dir = $config['upload_path'] . $newname;
              if (move_uploaded_file($val['tmp_name'], $new_dir)) {
                array_push($thumbnails, base_url(substr($config['upload_path'], 1) . $newname));
              }
            }
          }
        } else {
          // use thumbnail links
          $thumb_links_html = $this->input->post('thumbnail_links');
          $thumb_links = array_map(function ($x) {
            return trim($x);
          }, explode(';', $thumb_links_html));
          $thumbnails = $thumb_links;
        }

        $short_clip = "";
        if (isset($_FILES['short_clip'])) {
          # upload short clip file
          if ($_FILES['short_clip']['name']) {
            $config['file_name'] = time() . $_FILES['short_clip']['name'];
            if (!$this->upload->do_upload('short_clip')) {
              $this->upload->display_errors('<p class="bg-danger">', '</p>');
            } else {
              $upload = $this->upload->data();
              $short_clip = base_url(substr($config['upload_path'], 1) . $upload['file_name']);
            }
          }
        } else {
          $clip_link = $this->input->post('short_clip_link');
          $short_clip = $clip_link;
        }

        $categories = $this->input->post('categories');
        $review_title = trim($this->input->post('review_title'));
        $limit_per_user = intval($this->input->post('limit_per_user'));
        $global_limit = intval($this->input->post('global_limit'));
        $short_desc = htmlspecialchars($this->input->post('short_desc'));
        $is_movie =  intval($this->input->post('is_movie'));
        $imdb_link = "";
        if (!empty($is_movie)) {
          $imdb_link = $this->input->post('imdb_link');
        }

        // create item as draft state
        $reward_config = $this->model_config->getConfigByName('review_item_reward_points');
        $reward_points = intval($reward_config['value']);
        $slug = slugify('rv-' . rand(0000, 9999) . '-' . substr($review_title, 0, 25));
        $data = array('slug' => $slug, 'title' => $review_title, 'category' => implode(",", $categories), 'limit_per_user' => $limit_per_user, 'global_limit' => $global_limit, 'status' => 'draft', 'reward_points' => $reward_points);
        $created_item = $this->model_reviews->createReviewItem($user_id, $data);

        if ($created_item) {
          $review_item_files = array('review_id' => $created_item, 'thumbnail_large' => $thumbnails[0], 'thumbnail_small' => $thumbnails[count($thumbnails) - 1], 'short_desc' => $short_desc, 'short_clip' => $short_clip, 'is_movie' => $is_movie, 'imdb' => $imdb_link);
          $saved_files = $this->model_reviews->saveReviewItemFiles($review_item_files);
          if ($saved_files) {
            // log activity
            $log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Item Created', 'message' => 'Created New Review Item');
            $this->model_logs->logActivity($log);
            // redirect to reviews dashboard
            $this->session->set_flashdata('alert', array('title' => 'Item Created', 'classname' => 'alert-success', 'message' => 'Successfully created review item'));
          } else {
            $this->session->set_flashdata('alert', array('title' => 'Error Occurred', 'classname' => 'alert-warning', 'message' => 'Could not save files.'));
          }
        } else {
          $this->session->set_flashdata('alert', array('title' => 'Error Occurred', 'classname' => 'alert-warning', 'message' => 'Could not save files.'));
        }
        redirect('reviews/admin', 'refresh');
      }

      $cat_arr = $this->model_categories->getAllCategories();
      $this->data['categories'] = $cat_arr;

      $this->render_admin('pages/admin/activities/reviews/create', $this->data);
    } else {
      redirect('dashboard', 'refresh');
    }
  }

  public function readReviewItems()
  {
    $user_id = $this->session->userdata('id');
    $result = array('data' => array());

    if (in_array('manageReview', $this->permission) || in_array('manageActivity', $this->permission)) {
      $group_name = $this->session->userdata('group_name');

      // if any admin user list all, else list created by
      $items = null;
      if ((strpos($group_name, 'admin') !== false) || $group_name == 'moderator') {
        $items = $this->model_reviews->getAllReviewItems();
        if ($group_name == 'moderator') {
          $items = array_values(array_filter($items, function ($x) {
            return $x['status'] == 'draft';
          }));
        }
      } else {
        $items = $this->model_reviews->getReviewItemsCreatedBy($user_id);
      }

      foreach ($items as $key => $value) {
        // create list items
        $buttons = "";

        if (in_array('manageActivity', $this->permission)) {
          if ($value['status'] == 'draft') {
            $buttons .= "<a href='" . base_url('reviews/review_item/' . $value['slug']) . "' class='btn btn-primary'><i class='fa fa-archive'></i></a>";
          }
        }

        if (in_array('manageReview', $this->permission)) {
          if ($value['status'] == 'draft') {
            $buttons .= "<a href='" . base_url('reviews/edit/' . $value['slug']) . "' class='btn btn-primary' style='margin-right:10px'><i class='fa fa-pencil'></i></a>";
          }
          $buttons .= "<button onclick='removeFunc(`" . $value['slug'] . ")`' data-toggle='modal' data-target='#removeModal' class='btn btn-danger'><i class='fa fa-trash'></i></button>";
        }

        // no of times completed
        $completed_count = 0;
        $completed_items = $this->model_reviews->getCompletedByReviewId(null, $value['id']);
        $completed_count = count($completed_items);

        $status = "<span class='label label-info'>" . strtoupper($value['status']) . "</span>";

        $result['data'][$key] = array(
          $value['title'],
          $value['global_limit'],
          $completed_count,
          $value['reward_points'] . "SB",
          $status,
          $buttons
        );
      }
    } else {
      redirect('dashboard', 'refresh');
    }

    echo json_encode($result);
  }

  public function readCompletedItems()
  {
    $result = array('data' => array());

    if (in_array('reviewActivity', $this->permission)) {
      $items = $this->model_reviews->getCompletedAvailable();

      foreach ($items as $key => $value) {
        $review_item = $this->model_reviews->getReviewItemById($value['review_id']);
        $buttons = "";

        if (in_array('reviewActivity', $this->permission)) {
          $buttons .= "<a href='" . base_url('reviews/review_completed_item/' . $value['id']) . "' class='btn btn-primary'>Review</a>";
        }

        $result['data'][$key] = array(
          $review_item['title'],
          $value['review_rating'],
          $value['date_completed'],
          $value['points_earned'] . "SB",
          $buttons
        );
      }
    } else {
      redirect('dashboard', 'refresh');
    }

    echo json_encode($result);
  }

  public function review_item($review_slug)
  {
    $this->not_logged_in();
    $user_id = $this->session->userdata('id');
    $group_name = $this->session->userdata('group_name');

    if ($review_slug == null) {
      redirect('dashboard', 'refresh');
    }

    if (!in_array('reviewActivity', $this->permission)) {
      redirect('dashboard', 'refresh');
    }

    $review_item = $this->model_reviews->getReviewItemBySlug($review_slug);

    $this->form_validation->set_rules('approve_deny', 'Approve or Deny', 'required');
    if ($this->form_validation->run() == TRUE) {
      # code...
      $approve_deny = $this->input->post('approve_deny');
      $status = $approve_deny == 'deny' ? 'rejected' : 'available';

      $data = array('status' => $status);
      $update = $this->model_reviews->updateReviewItem($review_item['review_id'], $data);
      if ($update) { // success change status
        // reward points
        $reward_config = $this->model_config->getConfigByName('mod_review_reward');
        $points_earned = intval($reward_config['value']);

        // log claimed reward
        if ($group_name == 'moderator') {
          $this->reward_points($points_earned, $points_earned, null, array('review_id' => $review_item['review_id'], 'type' => 'mod_reward', 'streak' => '0'));
          $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $points_earned, 'title' => 'Completed'));
        } else {
          $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Completed Review ', 'title' => 'Completed'));
        }

        // log activity
        $activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Reviewed Activity Item', 'message' => 'Completed Review!');
        $this->model_logs->logActivity($activity);
        redirect('reviews/admin', 'refresh');
      }
    }
    $cat_arr = $this->model_categories->getAllCategories();
    $this->data['review_item'] = $review_item;
    $this->data['categories'] = $cat_arr;
    $this->render_admin('pages/admin/activities/reviews/review_item', $this->data);
  }

  public function review_completed_item($comp_id)
  {
    $this->not_logged_in();
    $user_id = $this->session->userdata('id');

    if (!in_array('reviewActivity', $this->permission)) {
      redirect('dashboard', 'refresh');
    }

    if ($comp_id == null) {
      redirect('dashboard', 'refresh');
    }

    $completed_item = $this->model_reviews->getCompletedItemById($comp_id);
    if (!empty($completed_item)) {
      $review_item = $this->model_reviews->getReviewItemById($completed_item['review_id']);

      $this->form_validation->set_rules('approve_deny', 'Approve Or Deny', 'required');
      if ($this->form_validation->run() == TRUE) {
        # code...
        $approve_deny = $this->input->post('approve_deny');
        $status = $approve_deny == 'deny' ? 'rejected' : 'approved';

        // if approved -> reward user points. else remove points and strike.
        $data = array('status' => $status);
        $update = $this->model_reviews->updateCompletedItem($comp_id, $data);
        if ($update) {
          if ($approve_deny == 'approve') { // reward completed user
            // reward completed user
            $bonus = intval($this->model_config->getConfigByName('mod_approve_reward')['value']);
            $this->model_users->logClaimedReward($completed_item['completed_by'], array('user_id' => $completed_item['completed_by'], 'review_id' => $completed_item['review_id'], 'reward_earned' => $bonus, 'type' => 'mod_reward', 'streak' => '0'));
          } else {
						// remove points earned
						$x_2_remove = $review_item['reward_points'];
						$this->model_users->logClaimedReward($completed_item['completed_by'], array('user_id' => $completed_item['completed_by'], 'review_id' => $completed_item['review_id'], 'reward_earned' => "-$x_2_remove", 'type' => 'mod_reward', 'streak' => '0'));

						// add strike
						$this_user = $this->model_users->getUserById($completed_item['completed_by']);
						$strike = intval($this_user['strike']) + 1;
						$this->model_users->update($completed_item['completed_by'], array('strike' => $strike));

						// log activity
						$this->model_logs->logActivity(array('user_id' => $completed_item['completed_by'], 'activity' => 'Review Denied', 'activity_code' => '4', 'message' => 'A review you submitted has been rejected and balance points deducted.'));
					}

          // reward points
          $reward_config = $this->model_config->getConfigByName('mod_review_reward');
          $points_earned = intval($reward_config['value']);

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

          // log claimed reward
          $this->model_users->logClaimedReward($user_id, array('user_id' => $user_id, 'review_id' => $review_item['review_id'], 'reward_earned' => $points_earned, 'type' => 'mod_reward', 'streak' => '0'));
          $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $points_earned, 'title' => 'Completed'));
          // log activity
          $activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Reviewed Activity Item', 'message' => 'Completed Review! Earned' . $points_earned);
          $this->model_logs->logActivity($activity);
          redirect('reviews/admin', 'refresh');
        }
      }
    } else {
      redirect('dashboard', 'refresh');
    }

    // review video, comp rating, comp review text, has watched, approve_deny
    $this->data['completed_item'] = $completed_item;
    $this->data['review_item'] = $review_item;
    $this->render_admin('pages/admin/activities/reviews/completed_item', $this->data);
  }

  public function completed_list()
  {
    $this->not_logged_in();

    if (in_array('reviewActivity', $this->permission)) {
      $this->render_admin('pages/admin/activities/reviews/completed_list', $this->data);
    } else {
      redirect('reviews/admin', 'refresh');
    }
  }

  public function edit($review_slug)
  {
    $user_id = $this->session->userdata('id');
    if ($review_slug != null) {
      if (in_array('manageReview', $this->permission)) {
        $review_item = $this->model_reviews->getReviewItemBySlug($review_slug);

        if (!empty($review_item)) {
          // load items.
          $this->form_validation->set_rules('review_title', 'Title', 'trim|required');
          $this->form_validation->set_rules('short_desc', 'Short Description', 'trim|required');
          $this->form_validation->set_rules('categories[]', 'Categories', 'required');
          $this->form_validation->set_rules('is_movie', 'Is Movie', 'trim|required');
          $this->form_validation->set_rules('imdb_link', 'IMDB Link', 'trim');
          $this->form_validation->set_rules('limit_per_user', 'User Limits', 'trim|required');
          $this->form_validation->set_rules('global_limit', 'Global Limit', 'trim|required');
          $this->form_validation->set_rules('short_clip_link', 'Short Clip', 'trim');
          $this->form_validation->set_rules('thumbnail_links', 'Thumbnails', 'trim');

          if ($this->form_validation->run() == TRUE) {
            // check short_clip and thumbnail_links
            $thumbnails = array();
            if (isset($_FILES['thumbnails'])) {
              # upload thumbnails
            } else {
              // use thumbnail links
              $thumb_links_html = $this->input->post('thumbnail_links');
              $thumb_links = array_map(function ($x) {
                return trim($x);
              }, explode(';', $thumb_links_html));
              $thumbnails = $thumb_links;
            }

            $short_clip = "";
            if (isset($_FILES['short_clip'])) {
              # upload short clip file
            } else {
              $clip_link = $this->input->post('short_clip_link');
              $short_clip = $clip_link;
            }

            $categories = $this->input->post('categories');
            $review_title = trim($this->input->post('review_title'));
            $limit_per_user = intval($this->input->post('limit_per_user'));
            $global_limit = intval($this->input->post('global_limit'));
            $short_desc = htmlspecialchars($this->input->post('short_desc'));
            $is_movie =  intval($this->input->post('is_movie'));
            $imdb_link = "";
            if (!empty($is_movie)) {
              $imdb_link = $this->input->post('imdb_link');
            }

            // update review item
            $data = array('title' => $review_title, 'category' => implode(",", $categories), 'limit_per_user' => $limit_per_user, 'global_limit' => $global_limit);
            $updated_item = $this->model_reviews->updateReviewItem($review_item['review_id'], $data);

            if ($updated_item) {
              $review_item_files = array('thumbnail_large' => $thumbnails[0], 'thumbnail_small' => $thumbnails[count($thumb_links) - 1], 'short_desc' => $short_desc, 'short_clip' => $short_clip, 'is_movie' => $is_movie, 'imdb' => $imdb_link);
              $updated_files = $this->model_reviews->updateReviewItemFiles($review_item['id'], $review_item_files);
              if ($updated_files) {
                // log activity
                $log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Item Updated', 'message' => 'Updated Review Item #' . $review_item['id']);
                $this->model_logs->logActivity($log);
                // redirect to reviews dashboard
                $this->session->set_flashdata('alert', array('title' => 'Item Updated', 'classname' => 'alert-success', 'message' => 'Successfully updated review item'));
              } else {
                $this->session->set_flashdata('alert', array('title' => 'Error Occurred', 'classname' => 'alert-warning', 'message' => 'Could not save files.'));
              }
            } else {
              $this->session->set_flashdata('alert', array('title' => 'Error Occurred', 'classname' => 'alert-warning', 'message' => 'Could not save files.'));
            }
            redirect('reviews/admin', 'refresh');
          }

          $cat_arr = $this->model_categories->getAllCategories();
          $this->data['review_item'] = $review_item;
          $this->data['categories'] = $cat_arr;

          $this->render_admin('pages/admin/activities/reviews/edit', $this->data);
        } else {
          $this->session->set_flashdata('alert', array('title' => 'Error occurred', 'classname' => 'alert-danger', 'message' => 'Invalid Review Item'));
          redirect('reviews/admin', 'refresh');
        }
      } else {
        redirect('reviews/admin', 'refresh');
      }
    }
  }

  public function delete()
  {
    $user_id = $this->session->userdata('id');
    $group_name = $this->session->userdata('group_name');

    $response = array();

    $review_slug = $this->input->post('slug');

    if (in_array('manageReview', $this->permission)) {
      $review_item = $this->model_reviews->getReviewItemBySlug($review_slug);

      if (!empty($review_item)) {
        $can_delete = false;
        // is not admin user
        if (!(strpos($group_name, 'admin') !== false)) {
          $can_delete = $review_item['created_by'] == $user_id ? true : false;
        } else {
          $can_delete = true;
        }

        if ($can_delete) {
          // delete files if uploaded in product dir
          $upload_path = FCPATH . "./uploads/reviews";
          $dir = $upload_path . "/" . $review_item['slug'] . "/";
          if (is_dir($dir) xor file_exists($dir)) {
            # delete all dir files
            $files = glob($dir . "{,.}*", GLOB_BRACE);
            foreach ($files as $file) {
              if (is_dir($file)) {
                rmdir($file);
              } else {
                unlink($file);
              }
            }
            rmdir($dir);
          }

          // model review to remove item
          $deleted = $this->model_reviews->removeReviewItem($review_item['id']);
          if ($deleted) {
            // log activity
            $log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Deleted Item', 'message' => 'Successfully deleted review item');
            $this->model_logs->logActivity($log);
            $response['success'] = true;
            $response['messages'] = 'Successfully removed';
          } else {
            $response['success'] = false;
            $response['messages'] = 'Error occurred while deleting product, contact admin';
            $this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'title' => 'Error occurred', 'message' => 'Could not delete item, please try again later.'));
          }
        } else {
          $response['success'] =  false;
          $response['message'] = 'Cannot delete item';
        }
      }
    }

    echo json_encode($response);
  }
}
