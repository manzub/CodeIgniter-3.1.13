<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transcribe extends Member_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->helper('my_helper.php');
    // load needed db configs
    $this->load->model('model_transcribe');
    $this->load->model('model_users');
    $this->load->model('model_config');
    $this->load->model('model_bonuses');
    $this->load->model('model_categories');
    $this->load->model('model_logs');
    $this->data['title'] = "Transcribe | SurveyVine";
    $this->data['per_page'] = 5;
  }

  public function completeTranscribeItem($slug = null)
  {
    $user_id = $this->session->userdata('id');
    if ($slug == null) {
      redirect('transcribe', 'refresh');
    }

    $this->form_validation->set_rules('transcribe_text', 'Text', 'trim|required');

    if ($this->form_validation->run() == TRUE) {
      // check permission
      if (in_array('completeTranscribe', $this->permission)) {
        // check if user already completed and limits_per_user
        $transcribe_item = $this->model_transcribe->getTranscribeItemBySlug($slug);

        if (!empty($transcribe_item)) {
          // check if item hasnt expired
          if ($transcribe_item['status'] == 'available') {
            // limit_per_user hasnt been reached
            $completed_items = $this->model_transcribe->getCompletedByTranscribeId($user_id, $transcribe_item['id']);
            if (!empty($completed_items)) {
              if (count($completed_items) >= $transcribe_item['limit_per_user']) {
                $this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Oops', 'message' => 'You have already completed this activity'));
                redirect('transcribe', 'refresh');
              }
            }

            // save completed item
            $comp_raw = htmlspecialchars($this->input->post('transcribe_text'));
            $data = array('transcribe_id' => $transcribe_item['id'], 'transcribe_text' => $comp_raw, 'completed_by' => $user_id, 'status' => 'completed', 'points_earned' => '0');
            $completed = $this->model_transcribe->completeItem($data);

            if ($completed) {
              // log activity
              $activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Transcribed 1 Item', 'message' => 'Well done!');
              $this->model_logs->logActivity($activity);
              // redirect to claims page
              redirect('transcribe/completed/' . $slug, 'refresh');
            }
          }
        } else {
          // redirect('transcribe', 'refresh');
        }
      }
    }
  }

  // page functions

  public function index($page = 1)
  {
    $this->no_admin();
    $user_id = $this->session->userdata('id');
    $group_name = $this->session->userdata('group_name');

    // get available activities
    $activities_page = $this->model_transcribe->getMyAvailableActivities($group_name, $user_id, true, $page - 1, $this->data['per_page']);
    $this->data['activities'] = $activities_page;

    // create navigation
    $available_items = $this->model_transcribe->getMyAvailableActivities($group_name, $user_id);
    $count_available_av = count($available_items);
    $this->data['pages'] = round($count_available_av / $this->data['per_page']);
    $this->data['page'] = $page;

    $this->render_template('pages/transcribe/index', $this->data);
  }

  public function single($slug = null)
  {
    $this->no_admin();
    if ($slug == null) {
      show_404('Page Not Found');
    }

    // completeTranscribe - can transcribe items
    if (in_array('completeTranscribe', $this->permission)) {
      // render view
      $transcribe_item = $this->model_transcribe->getTranscribeItemBySlug($slug);

      if (!empty($transcribe_item)) {
        $categories = array();
        $cat_arr = explode(",", $transcribe_item['category']);
        foreach ($cat_arr as $key => $item) {
          $cat_item = $this->model_categories->getCategoryById($item);
          array_push($categories, $cat_item['value']);
        }

        // todo multiple audio files

        $this->data['title'] = $transcribe_item['title'] . " | SurveyVine";
        $this->data['transcribe_item'] = $transcribe_item;
        $this->render_template('pages/transcribe/single', $this->data);
      } else {
        show_404('Page Not Found');
      }
    } else {
      $this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Oops - you don\'t have permission', 'message' => 'Why miss out, create a member account today and completing activities.'));
      redirect('transcribe', 'refresh');
    }
  }

  public function completed($slug = null)
  {
    $this->not_logged_in();
    $this->no_admin();
    $user_id = $this->session->userdata('id');

    if ($slug == null) {
      redirect('transcribe', 'refresh');
    }

    if (in_array('earnRewards', $this->permission)) {
      $transcribe_item = $this->model_transcribe->getTranscribeItemBySlug($slug);
      $completed_items = $this->model_transcribe->getCompletedByTranscribeId($user_id, $transcribe_item['id']);

      if (!empty($transcribe_item) && !empty($completed_items)) {
        // on user click claim button
        $this->form_validation->set_rules('completed', 'slug', 'required');
        if ($this->form_validation->run() == TRUE) {
          // TODO: member multiply
          $points_earned = $transcribe_item['reward_points'];
          $data = array('points_earned' => $points_earned);
          foreach ($completed_items as $key => $comp_item) {
            $update = $this->model_transcribe->updateCompletedItem($comp_item['id'], $data);
            if ($update) {
              // check if bonus item
              $categories = explode(',', $transcribe_item['category']);
              if (in_array('1', $categories)) { // found as bonus_item
                $bonus_item = $this->model_bonuses->getBonusItemByCond(array('transcribe_id' => $transcribe_item['transcribe_id']));
                $reward_points = intval($bonus_item['reward_points']);
                $points_earned =  $points_earned + $reward_points;
                $global_limit = intval($bonus_item['global_limit']) - 1;
                $status = $global_limit == 0 ? 'completed' : 'available';
                // update bonus item
                $this->model_bonuses->update($bonus_item['id'], array('global_limit' => $global_limit, 'status' => $status));
              }
              // reduce global limit
              if ($transcribe_item['global_limit'] != NULL && intval($transcribe_item['global_limit']) > 0) {
                $global_limit = intval($transcribe_item['global_limit']) - 1;
                $this->model_transcribe->updateTranscribeItem($transcribe_item['transcribe_id'], array('global_limit' => $global_limit));
              }
            }
          }
          // reward points
          $total_earned = round($points_earned * count($completed_items));
          $this->reward_points($points_earned, $total_earned, $transcribe_item['slug'], array('transcribe_id' => $transcribe_item['id'], 'type' => 'completed_activity', 'streak' => '0'));

          $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $total_earned, 'title' => 'Completed'));
          // log activity
          $activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Earned Reward', 'message' => 'Congratulations! Earned' . $total_earned);
          $this->model_logs->logActivity($activity);
          redirect('transcribe', 'refresh');
        }

        $this->data['title'] = "Claim your rewards - " . $transcribe_item['title'] . " | SurveyVine!";
        $this->data['transcribe_item'] = $transcribe_item;
        $this->render_template('pages/transcribe/completed', $this->data);
      } else {
        redirect('transcribe', 'refresh');
      }
    }
  }

  // admin functions
  public function admin()
  {
    $this->not_logged_in();

    if (in_array('manageTranscribe', $this->permission) || in_array('manageActivity', $this->permission)) {
      $this->render_admin('pages/admin/activities/transcribe/index', $this->data);
    } else {
      redirect('dashboard', 'refresh');
    }
  }

  public function create()
  {
    $this->not_logged_in();
    $user_id = $this->session->userdata('id');

    if (in_array('createTranscribe', $this->permission)) {

      $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'mp3|wav';
			$this->load->library('upload', $config);

      $this->form_validation->set_rules('transcribe_title', 'required|trim');
      $this->form_validation->set_rules('categories[]', 'required');
      $this->form_validation->set_rules('limit_per_user', 'User Limits', 'trim|required');
      $this->form_validation->set_rules('global_limit', 'Global Limit', 'trim|required');
      $this->form_validation->set_rules('files_links', 'trim');

      if ($this->form_validation->run() == TRUE) {
        // audio files and file links
        $files = array();
        if (isset($_FILES['av_files[]'])) {
          $av_files = $_FILES['av_files'];
          if (!empty($av_files)) {
            $file_desc = $this->reArrayFiles($av_files);
            foreach ($file_desc as $val) {
              $newname = time() . $val['name'];
              $new_dir = $config['upload_path'] . $newname;
              if (move_uploaded_file($val['tmp_name'], $new_dir)) {
                array_push($av_files, base_url(substr($config['upload_path'], 1) . $newname));
              }
            }
          }
        } else {
          // use file links
          $av_links_html = $this->input->post('files_links');
          $av_links = array_map(function ($x) {
            return trim($x);
          }, explode(';', $av_links_html));
          $files = $av_links;
        }

        // categories
        $categories = implode(',', $this->input->post('categories'));
        $transcribe_title = trim($this->input->post('transcribe_title'));
        $limit_per_user = intval($this->input->post('limit_per_user'));
        $global_limit = intval($this->input->post('global_limit'));

        // create item as draft
        $reward_config = $this->model_config->getConfigByName('transcribe_item_reward_points');
        $reward_points = intval($reward_config['value']);
        $slug = slugify('av-' . rand(0000, 9999) . '-' . substr($transcribe_title, 0, 25));
        $data = array('slug' => $slug, 'title' => $transcribe_title, 'category' => $categories, 'limit_per_user' => $limit_per_user, 'global_limit' => $global_limit, 'status' => 'draft', 'reward_points' => $reward_points);
        $created_item = $this->model_transcribe->createTranscribeItem($user_id, $data);

        if ($created_item) {
          $transcribe_item_files = array('transcribe_id' => $created_item, 'no_of_files' => sizeof($files), 'files' => serialize($files));
          $saved_files = $this->model_transcribe->saveTranscribeItemFiles($transcribe_item_files);
          if ($saved_files) {
            // log activity
            $log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Item Created', 'message' => 'Created New Transcribe Item');
            $this->model_logs->logActivity($log);
            // redirect to reviews dashboard
            $this->session->set_flashdata('alert', array('title' => 'Item Created', 'classname' => 'alert-success', 'message' => 'Successfully created transcribe item'));
          } else {
            $this->session->set_flashdata('alert', array('title' => 'Error Occurred', 'classname' => 'alert-warning', 'message' => 'Could not save files.'));
          }
        } else {
          $this->session->set_flashdata('alert', array('title' => 'Error Occurred', 'classname' => 'alert-warning', 'message' => 'Could not save files.'));
        }
        redirect('transcribe/admin', 'refresh');
      }

      $cat_arr = $this->model_categories->getAllCategories();
      $this->data['categories'] = $cat_arr;

      $this->render_admin('pages/admin/activities/transcribe/create', $this->data);
    } else {
      redirect('dashboard', 'refresh');
    }
  }

  public function readTranscribeItems()
  {
    $user_id = $this->session->userdata('id');
    $result = array('data' => array());

    if (in_array('manageTranscribe', $this->permission) || in_array('manageActivity', $this->permission)) {
      $group_name = $this->session->userdata('group_name');

      // if any admin user list all, else list created by
      $items = null;
      if ((strpos($group_name, 'admin') !== false) || $group_name == 'moderator') {
        $items = $this->model_transcribe->getAllTranscribeItems();
        if ($group_name == 'moderator') {
          $items = array_values(array_filter($items, function ($x) {
            return $x['status'] == 'draft';
          }));
        }
      } else {
        $items = $this->model_transcribe->getTranscribeItemsCreatedBy($user_id);
      }
      foreach ($items as $key => $value) {
        // create list items
        $buttons = "";

        if (in_array('manageActivity', $this->permission)) {
          if ($value['status'] == 'draft') {
            $buttons .= "<a href='" . base_url('transcribe/review_item/' . $value['slug']) . "' title='Review' class='btn btn-primary'><i class='fa fa-archive'></i></a>";
          }
        }

        if (in_array('manageTranscribe', $this->permission)) {
          if ($value['status'] == 'draft') {
            $buttons .= "<a href='" . base_url('transcribe/edit/' . $value['slug']) . "' class='btn btn-primary' style='margin-right:10px'><i class='fa fa-pencil'></i></a>";
          }
          $buttons .= "<button onclick='removeFunc(`" . $value['slug'] . "`)' data-toggle='modal' data-target='#removeModal' class='btn btn-danger'><i class='fa fa-trash'></i></button>";
        }

        // no of times completed
        $completed_count = 0;
        $completed_items = $this->model_transcribe->getCompletedByTranscribeId(null, $value['id']);
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
    }

    echo json_encode($result);
  }

  public function readCompletedItems()
  {
    $result = array('data' => array());

    if (in_array('reviewActivity', $this->permission)) {
      $items = $this->model_transcribe->getCompletedAvailable();

      foreach ($items as $key => $value) {
        $transcribe_item = $this->model_transcribe->getTranscribeItemById($value['transcribe_id']);
        $buttons = "";

        if (in_array('reviewActivity', $this->permission)) {
          $buttons .= "<a href='" . base_url('transcribe/review_completed_item/' . $value['id']) . "' class='btn btn-primary'>Review</a>";
        }

        $result['data'][$key] = array(
          $transcribe_item['title'],
          $value['date_completed'],
          $value['points_earned'] . "SB",
          $buttons
        );
      }
    } else {
      redirect('transcribe/admin', 'refresh');
    }

    echo json_encode($result);
  }

  public function review_item($transcribe_slug)
  {
    $this->not_logged_in();
    $user_id = $this->session->userdata('id');
    $group_name = $this->session->userdata('group_name');

    if ($transcribe_slug == null) {
      redirect('dashboard', 'refresh');
    }

    if (!in_array('reviewActivity', $this->permission)) {
      redirect('dashboard', 'refresh');
    }

    $transcribe_item = $this->model_transcribe->getTranscribeItemBySlug($transcribe_slug);


    $this->form_validation->set_rules('approve_deny', 'Approve or Deny', 'required');
    if ($this->form_validation->run() == TRUE) {
      // do something
      $approve_deny = $this->input->post('approve_deny');
      $status = $approve_deny == 'deny' ? 'rejected' : 'available';

      $data = array('status' => $status);
      $update = $this->model_transcribe->updateTranscribeItem($transcribe_item['transcribe_id'], $data);
      if ($update) {
        // reward points
        $reward_config = $this->model_config->getConfigByName('mod_review_reward');
        $points_earned = intval($reward_config['value']);

        // log claimed reward
        if ($group_name == 'moderator') {
          $this->reward_points($points_earned, $points_earned, null, array('transcribe_id' => $transcribe_item['transcribe_id'], 'type' => 'mod_reward', 'streak' => '0'));
          $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $points_earned, 'title' => 'Completed'));
        } else {
          $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Completed Review ', 'title' => 'Completed'));
        }
        // log activity
        $activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Reviewed Activity Item', 'message' => 'Completed Review!');
        $this->model_logs->logActivity($activity);
        redirect('transcribe/admin', 'refresh');
      }
    } else {
      $files_html = implode(',', unserialize($transcribe_item['files']));
      $cat_arr = $this->model_categories->getAllCategories();
      $this->data['files_html'] = $files_html;
      $this->data['transcribe_item'] = $transcribe_item;
      $this->data['categories'] = $cat_arr;
      $this->render_admin('pages/admin/activities/transcribe/review_item', $this->data);
    }
  }

  public function review_completed_item($comp_id)
  {
    $this->not_logged_in();
    $user_id = $this->session->userdata('id');

    if (!in_array('reviewActivity', $this->permission)) {
      redirect('transcribe/admin', 'refresh');
    }

    if ($comp_id == null) {
      redirect('transcribe/admin', 'refresh');
    }

    $completed_item = $this->model_transcribe->getCompletedItemById($comp_id);
    if (!empty($completed_item)) {
      $transcribe_item = $this->model_transcribe->getTranscribeItemById($completed_item['transcribe_id']);

      $this->form_validation->set_rules('approve_deny', 'Approve or Deny', 'required');
      if ($this->form_validation->run() == TRUE) {
        $approve_deny = $this->input->post('approve_deny');
        $status = $approve_deny == 'deny' ? 'rejected' : 'approved';

        // if approved -> reward comp user points.
        $data = array('mod_status' => $status);
        $update = $this->model_transcribe->updateCompletedItem($comp_id, $data);
        if ($update) {
          if ($approve_deny == 'approve') {
            // reward comp user
            $bonus = intval($this->model_config->getConfigByName('mod_approve_reward')['value']);
            $this->model_users->logClaimedReward($completed_item['completed_by'], array('user_id' => $completed_item['completed_by'], 'transcribe_id' => $completed_item['transcribe_id'], 'reward_earned' => $bonus, 'type' => 'mod_reward', 'streak' => '0'));
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
          $this->model_users->logClaimedReward($user_id, array('user_id' => $user_id, 'transcribe_id' => $transcribe_item['transcribe_id'], 'reward_earned' => $points_earned, 'type' => 'mod_reward', 'streak' => '0'));
          $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'message' => 'Earned ' . $points_earned, 'title' => 'Completed'));
          // log activity
          $activity = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Reviewed Activity Item', 'message' => 'Completed Review! Earned' . $points_earned);
          $this->model_logs->logActivity($activity);
          redirect('transcribe/admin', 'refresh');
        }
      }
    } else {
      redirect('transcribe/admin', 'refresh');
    }

    // transcribe audio, comp review text, approve_deny
    $this->data['completed_item'] = $completed_item;
    $this->data['transcribe_item'] = $transcribe_item;
    $this->render_admin('pages/admin/activities/transcribe/completed_item', $this->data);
  }

  public function completed_list()
  {
    $this->not_logged_in();

    if (in_array('reviewActivity', $this->permission)) {
      $this->render_admin('pages/admin/activities/transcribe/completed_list', $this->data);
    } else {
      redirect('transcribe/admin', 'refresh');
    }
  }

  public function edit($transcribe_slug)
  {
    $user_id = $this->session->userdata('id');
    if ($transcribe_slug != null) {
      if (in_array('manageTranscribe', $this->permission)) {
        $transcribe_item = $this->model_transcribe->getTranscribeItemBySlug($transcribe_slug);

        if (!empty($transcribe_item)) {
          // form
          $this->form_validation->set_rules('transcribe_title', 'required|trim');
          $this->form_validation->set_rules('categories[]', 'required');
          $this->form_validation->set_rules('limit_per_user', 'User Limits', 'trim|required');
          $this->form_validation->set_rules('global_limit', 'Global Limit', 'trim|required');
          $this->form_validation->set_rules('files_links', 'trim');

          if ($this->form_valudation->run() == TRUE) {
            // audio files and file links
            $files = array();
            if (isset($_FILES['av_files[]'])) {
              # code...
            } else {
              // use file links
              $av_links_html = $this->input->post('files_links');
              $av_links = array_map(function ($x) {
                return trim($x);
              }, explode(';', $av_links_html));
              $files = $av_links;
            }

            // categories
            $categories = implode(',', $this->input->post('categories'));
            $transcribe_title = trim($this->input->post('transcribe_title'));
            $limit_per_user = intval($this->input->post('limit_per_user'));
            $global_limit = intval($this->input->post('global_limit'));

            // update review item
            $data = array('title' => $transcribe_title, 'category' => $categories, 'limit_per_user' => $limit_per_user, 'global_limit' => $global_limit,);
            $updated_item = $this->model_transcribe->updateTranscribeItem($transcribe_item['id'], $data);

            if ($updated_item) {
              $transcribe_item_files = array('no_of_files' => sizeof($files), 'files' => serialize($files));
              $updated_files = $this->model_transcribe->updateTranscribeItemFiles($transcribe_item['id'], $transcribe_item_files);
              if ($updated_files) {
                // log activity
                $log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Item Updated', 'message' => 'Updated Transcribe Item #' . $transcribe_item['id']);
                $this->model_logs->logActivity($log);
                // redirect to reviews dashboard
                $this->session->set_flashdata('alert', array('title' => 'Item Updated', 'classname' => 'alert-success', 'message' => 'Successfully updated transcribe item'));
              } else {
                $this->session->set_flashdata('alert', array('title' => 'Error Occurred', 'classname' => 'alert-warning', 'message' => 'Could not save files.'));
              }
            } else {
              $this->session->set_flashdata('alert', array('title' => 'Error Occurred', 'classname' => 'alert-warning', 'message' => 'Could not save files.'));
            }
            redirect('transcribe/admin', 'refresh');
          }
          // load items
          $cat_arr = $this->model_categories->getAllCategories();
          $this->data['transcribe_item'] = $transcribe_item;

          $files_html = implode(',', unserialize($transcribe_item['files']));
          $this->data['files_html'] = $files_html;
          $this->data['categories'] = $cat_arr;
          $this->render_admin('pages/admin/activities/transcribe/edit', $this->data);
        }
      }
    }
  }

  public function delete()
  {
    $user_id = $this->session->userdata('id');
    $group_name = $this->session->userdata('group_name');

    $response = array();

    $transcribe_slug = $this->input->post('slug');

    if (in_array('manageTranscribe', $this->permission)) {
      $transcribe_item = $this->model_transcribe->getTranscribeItemBySlug($transcribe_slug);

      if (!empty($transcribe_item)) {
        $can_delete = false;
        // is not admin user
        if (!(strpos($group_name, 'admin') !== false)) {
          $can_delete = $review_item['created_by'] == $user_id ? true : false;
        } else {
          $can_delete = true;
        }

        if ($can_delete) {
          // delete files if uploaded

          // model transcribe to remove item
          $deleted = $this->model_transcribe->removeTranscribeItem($transcribe_item['id']);
          if ($deleted) {
            // log activity
            $log = array('user_id' => $user_id, 'activity_code' => '1', 'activity' => 'Deleted Item', 'message' => 'Successfully deleted transcribe item');
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
          $response['message'] = 'Cannot delete product';
        }
      }
    }
  }
}
