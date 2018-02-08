<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function index()
	{
		$this->load->view('layout/header');
		$this->load->view('connexion');
		$this->load->view('layout/footer');
	}

	public function inscription()
	{
		$this->load->view('layout/header');
		$this->load->view('inscription');
		$this->load->view('layout/footer');
	}
}
