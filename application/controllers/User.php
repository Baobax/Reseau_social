<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function index()
	{
		$this->load->view('connexion');
	}

	public function inscription()
	{
		$this->load->view('inscription');
	}
}
