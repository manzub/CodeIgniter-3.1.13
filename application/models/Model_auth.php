<?php

class Model_auth extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function check_email($email)
	{
		if ($email) {
			$query = $this->db->get_where('users', array('email' => $email));
			$result = $query->num_rows();
			return ($result == 1) ? true : false;
		}

		return false;
	}

	public function check_username($username)
	{
		if ($username) {
			$query = $this->db->get_where('users', array('username' => $username));
			$result = $query->num_rows();
			return ($result == 1) ? true : false;
		}

		return false;
	}

  public function set_otp_code($data = array()) {
    if (!empty($data)) {
      $insert = $this->db->insert('otp_codes', $data);
      return ($insert == true) ? true : false;
    }
    return false;
  }

  public function check_code($user_id = null, $otp_code = null) {
    if ($user_id != null && $otp_code != null) {
      $query = $this->db->get_where('otp_codes', array('user_id' => $user_id, 'code' => $otp_code));
      $result = $query->num_rows();
      return ($result == 1) ? true : false;
    }

    return false;
  }

  public function use_code($id = null) {
    if ($id != null) {
      $this->db->where('id', $id);
			$delete = $this->db->delete('otp_codes');
			return ($delete == true) ? true : false;
    }
  }

	public function login($username_email, $password)
	{
		if ($username_email && $password) {
			$cond = array('email' => $username_email);
			if (!filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
				$cond = array('username' => $username_email);
			}
			$query = $this->db->get_where('users', array_merge(array('status' => 'active'), $cond));

			if ($query->num_rows() == 1) {
				$result = $query->row_array();

				$hash_password = password_verify($password, $result['password']);
				return ($hash_password == true) ? $result : false;
			} else {
				return false;
			}
		}
	}
}
