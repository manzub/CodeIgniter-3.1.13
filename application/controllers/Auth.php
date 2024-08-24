<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Member_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('model_auth');
    $this->load->model('model_users');
    $this->load->model('model_groups');
    $this->load->model('model_logs');
    $this->load->model('model_config');
    $this->load->model('model_referrals');
  }

  public function login()
  {
    $this->logged_in();

    $this->data['title'] = 'Login | SurveyVine!';
    $this->form_validation->set_rules('name_email', 'Username/Email', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');

    if ($this->form_validation->run() == TRUE) {
      $user_exists = FALSE;
      $username_email = $this->input->post('name_email');
      if (filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
        $user_exists = $this->model_auth->check_email($username_email);
      } else {
        $user_exists = $this->model_auth->check_username($username_email);
      }

      if ($user_exists == TRUE) {
        $login = $this->model_auth->login($username_email, $this->input->post('password'));

        if ($login) {
          $this->model_users->update($login['id'], array('last_login' => strtotime('now')));
          // check account status
          if ($login['status'] == 'active') {
            // create session data
            $logged_in_sess = array('id' => $login['id'], 'username' => $login['username'], 'email' => $login['email'], 'logged_in' => TRUE);
            $this->session->set_userdata($logged_in_sess);
            $this->session->set_userdata('curr_status', $this->user_status['logged_in']);
            // log activity
            $activity = array('user_id' => $login['id'], 'activity_code' => '0', 'activity' => 'Login Successful', 'message' => 'Welcome!');
            $this->model_logs->logActivity($activity);
            // redirect

            $group_data = $this->model_groups->getUserGroupByUserId($login['id']);

            // get admin users
            $admins = $this->model_config->getConfigByName('admin_accounts');
            $admin_arr = unserialize($admins['value']);
            if (in_array($group_data['group_name'], $admin_arr)) {
              redirect('dashboard', 'refresh');
            } else {
              redirect('home', 'refresh');
            }
          } else {
						if ($login['status'] == 'pending') {
							$message = 'Your account is pending activation, please check your emails for any activation codes. if you are a moderator or publisher please contact support.';
							$this->sesion->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Unverified account.', 'message' => $message));
							redirect('auth/verify', 'refresh');
						}
          }
        } else {
          $this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'Incorrect username/password combination.', 'title' => 'Oops an error occured'));
        }
      } else {
        $this->session->set_flashdata('alert', array('classname' => 'alert-danger', 'message' => 'Email does not exist.', 'title' => 'Oops an error occured'));
      }
    }


    $this->render_template('pages/auth/login', $this->data);
  }

  public function signup($ref_code = null)
  {
    $this->logged_in();

    $account_type = $this->input->get('type');

    $this->data['title'] = "Create an account | SurveyMonkey";
    $this->form_validation->set_rules('username', 'Username', 'trim|required');
    $this->form_validation->set_rules('email', 'Email', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');

    if ($this->form_validation->run() == TRUE) {
      $username_exists = $this->model_auth->check_username($this->input->post('username'));
      if (!$username_exists) {
        $email_exists = $this->model_auth->check_email($this->input->post('email'));
        if (!$email_exists) {
          // valid username and email
          $password_hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
          $status = 'pending';
          $group_data = $this->model_groups->getGroupByGroupName('member');
          $is_admin_user = false;

          if ($account_type != null) {
            if (in_array($account_type, array('moderator', 'publisher', 'advertiser'))) {
              $group_data = $this->model_groups->getGroupByGroupName($account_type);
              $status = 'pending';
              $is_admin_user = true;
            }
          }

          // create user
          $data = array('username' => $this->input->post('username'), 'password' => $password_hash, 'email' => $this->input->post('email'), 'user_group' => $group_data['id'], 'status' => $status);
          $raw_ref_code = $this->input->post('ref_code') != '' ? $this->input->post('ref_code') : $ref_code;
          $ref_id = null;
          if (!in_array($raw_ref_code, array('', null))) {
            // validate ref_code
            $ref_user = $this->model_users->getUserByRefCode($raw_ref_code);
            if ($ref_user['username']) {
              // log referral -> invited, active
              $saved_ref = $this->model_referrals->referUser(array('ref_code' => $raw_ref_code, 'email' => $this->input->post('email'), 'status' => 'invited'));
              $my_ref_code = $this->generate_ref_code(8);
              $data = array_merge($data, array('referred_by' => $raw_ref_code, 'ref_code' => $my_ref_code));
              $ref_id = $saved_ref;
            }
          }

          $signup = $this->model_users->create($data);

          if ($signup) {
            if (!in_array($raw_ref_code, array('', null))) {
              // edit ref info -> save user_id
              $this->model_referrals->updateReferral($ref_id, array('user_id' => $signup, 'status' => 'active'));
            }

            // log activity
            $activity = array('user_id' => $signup, 'activity_code' => '0', 'activity' => 'Created User Account', 'message' => 'Welcome to SurveyMonkey!');
            $this->model_logs->logActivity($activity);

            // send superadmin notification
            if ($is_admin_user) {
              $this->send_email('wizzdom@surveyvine.com', 'Elevated User Request', 'A new user has requested to be registered as a ' . $account_type . 'do you approve this request?');
              $this->session->set_flashdata('alert', array('classname' => 'alert-info', 'title' => 'Congratulations', 'message' => 'Your ' . $account_type . ' has been created but will need admin approval. you will get an email once approved.'));

              // send welcome message
              $message = "Welcome to SurveyVine!\n\n Your account is currently pending approval, your account should be approved in 1-2 hours.";
              $this->send_email($this->input->post('email'), 'Welcome to SurveyVine!', $message);
            } else {

              // generate otp code, send verification code and link, set account to pending
              $otp_code = $this->generate_ref_code(6);
              $this->model_auth->set_otp_code(array('user_id' => $signup, 'code' => $otp_code));

              // send welcome message
              $message = "<div style='align-items:left'>
              <h1>Welcome to SurveyVine!</h1>
              <p>Let's get you started with bonus earnings.\n</p>
              <p>You will need to verify your account before logging in.</p>
              <p>Use Verification code below.</p>
              <h3>OTP Code</h3>
              <p>" . $otp_code . "</p>
              <p>cannot copy code? click link here <a href='".base_url('auth/verify?code='.$otp_code)."'></a></p>
              </div>";
              $this->send_email($this->input->post('email'), 'Welcome to SurveyVine, verify your account.', $message);

              $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Congratulations', 'message' => 'Verify your account to continue.'));
              redirect('auth/verify', 'refresh');
            }
          }
        }
      }
    }

    $this->render_template('pages/auth/signup', $this->data);
  }

  public function logout()
  {
    $this->not_logged_in();

    $this->session->sess_destroy();
    redirect('home', 'refresh');
  }

  public function verify()
  {
    $this->logged_in();

    $this->form_validation->set_rules('email', 'Email', 'trim|required');
    $this->form_validation->set_rules('code', 'Code', 'trim|required');

    if ($this->form_validation->run() == TRUE) {
      // check code, change account status
      $this_user = $this->model_users->getUserByCond(array('email' => $this->input->post('email')));
      if (!empty($this_user)) {
        // check code
        $valid_code = $this->model_auth->check_code($this_user['id'], $this->input->post('code'));
        if ($valid_code) {
          // change account status
          $updated = $this->model_users->update($this_user['id'], array('status' => 'active'));
          if ($updated) {
            $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Account Verified', 'message' => 'Your account has been verified, you can login now. <a href="'.base_url('auth/login').'">click here</a>'));
          } else {
            $this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'An error occurred', 'message' => 'An error occurred'));
          }
        }
      }
    }

    $this->data['code'] = $this->input->get('code');
    $this->data['title'] = 'Verify your account';
    $this->render_template('pages/auth/verify', $this->data);
  }

  public function forgot_password()
  {
    $this->logged_in();

    $this->form_validation->set_rules('email', 'Email Address', 'trim|required');

    if ($this->form_validation->run() == TRUE) {
      $user_exists = $this->model_auth->check_email($this->input->post('email'));
      if ($user_exists) {
        // generate password
        $new_password = $this->generatePassword(8);
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);

        $this_user = $this->model_users->getUserByCond(array('email' => $this->input->post('email')));
        $updated = $this->model_users->update($this_user['id'], array('password' => $password_hash));
        if ($updated) {
          // log activity
          $this->model_logs->logActivity(array('user_id' => $this_user['id'], 'activity_code' => '4', 'activity' => 'Password Changed', 'message' => 'Your password has been changed'));

          $subject = "SurveyVine! Your password has been changed";

          $message = "<div style='align-items:left'>
          <h1>Your Password has been changed</h1>
          <p>Here are the details.\n</p>
          <p>New Password: " . $new_password . "</p>
          </div>";

          if ($this->send_email($this->input->post('email'), $subject, $message)) {
            $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Email Sent', 'message' => 'An email with your new password has been sent to you.'));
          } else {
            $this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'An error occurred', 'message' => 'an error occurred and could not complete your request, try again later.'));
          }
        }
      } else {
        $this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Account not found', 'message' => 'The email you entered could not be found'));
      }
    }

    $this->data['title'] = 'Forgot Password';
    $this->render_template('pages/auth/forgot_password', $this->data);
  }

  public function reset_password()
  {
    $this->not_logged_in();

    $user_id = $this->session->userdata('id');

    $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required');
    $this->form_validation->set_rules('new_password', 'New Password', 'trim|required');

    if ($this->form_validation->run() == TRUE) {
      // verify old password
      $this_user = $this->model_users->getUserById($user_id);
      $password_matches = password_verify($this->input->post('old_password'), $this_user['password']);

      if ($password_matches) {
        $password_hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT);
        $updated = $this->model_users->update($user_id, array('password' => $password_hash));
        if ($updated) {
          // log activity
          $this->model_logs->logActivity(array('user_id' => $user_id, 'activity_code' => '4', 'activity' => 'Password Changed', 'message' => 'Your password has been changed'));

          $subject = "Your password has been changed";
          $message = "<div style='align-items:left'>
          <h1>Your Password has been changed</h1>
          <p>Here are the details.\n</p>
          <p>New Password: " . $this->input->post('new_password') . "</p>
          </div>";

          if ($this->send_email($this_user['email'], $subject, $message)) {
            $this->session->set_flashdata('alert', array('classname' => 'alert-success', 'title' => 'Success', 'message' => 'Your password has been changed. go to <a href="' . base_url('auth/login') . '">login</a> page to login again'));
            $this->session->sess_destroy();
          } else {
            $this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'An error occurred', 'message' => 'an error occurred and could not complete your request, try again later.'));
          }
        } else {
          $this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'An error occurred', 'message' => 'could not update password'));
        }
      } else {
        $this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Incorrect password', 'message' => 'The password you entered doesn\'t match'));
      }
      // send an email
      // log out user.
    }

    $this->data['title'] = 'Change your Password';
    $this->render_template('pages/auth/reset_password', $this->data);
  }

  public function deactivate_user()
  {
    $this->not_logged_in();

    $user_id = $this->session->userdata('id');
    // reactivate user account
    $update = $this->model_users->update($user_id, array('status' => 'deactivated'));
    if ($update) {
      // log activity
      $activity = array('user_id' => $user_id, 'activity_code' => '0', 'activity' => 'Deactivated User', 'message' => 'Sad to see you leave, we do hope you come back soon!');
      $this->model_logs->logActivity($activity);
      // destroy session
      $this->session->set_flashdata('alert', array('classname' => 'alert-warning', 'title' => 'Done', 'message' => 'You have successfully deactivated your account'));
      $this->session->sess_destroy();
      redirect('auth/login', 'refresh');
    }
  }

  // misc functions
  public function getRandomBytes($nbBytes = 32)
  {
    $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
    if (false !== $bytes && true === $strong) {
      return $bytes;
    } else {
      throw new \Exception("Unable to generate secure token from OpenSSL.");
    }
  }

  public function generatePassword($length)
  {
    return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode($this->getRandomBytes($length + 1))), 0, $length);
  }
}
