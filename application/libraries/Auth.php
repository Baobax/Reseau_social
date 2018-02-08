<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Auth
{
	protected $CI;

	function __construct()
	{
		// Assign by reference with "&" so we don't create a copy
		$this->CI = &get_instance();
	}

	public function is_user()
	{
		/*
		$user_id = $this->CI->session->userdata('user_id');
		$this->CI->load->model("user_model");
		$user      = $this->CI->user_model->get($user_id);
		if (isset($user))
		{
			if (is_numeric($this->CI->session->userdata('user_id')) && $user->actif == 1)
				return true;
		}
		return false;
		*/
	}

	public function check_is_logged_user()
	{
		/*
		if (!$this->is_user())
		{
			redirect('front/user/login', 'refresh');
			return;
		}
		else
		{
			$user_id = $this->CI->session->userdata('user_id');
			$this->CI->load->model("user_model");
			$user      = $this->CI->user_model->get($user_id);
			if (!$user)
				redirect("front/user/logout");

			return $this->CI->session->userdata('user_id');
		}
		*/
	}

############################################################################

	public function is_admin()
	{
		/*
		if (is_numeric($this->CI->session->userdata('admin_id')))
			return true;
		return false;
		*/
	}

	public function check_is_logged_admin()
	{
		/*
		if (!$this->is_admin())
		{
			redirect('back/admin/login', 'refresh');
			return;
		}
		else
		{
			$admin_id = $this->CI->session->userdata('admin_id');
			$this->CI->load->model('admin_model');
			$user     = $this->CI->admin_model->get($admin_id);
			if (!$user)
				redirect("back/admin/logout");

			return $this->CI->session->userdata('admin_id');
		}
		*/
	}
}