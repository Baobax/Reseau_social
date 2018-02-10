<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Evenements extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        redirect('evenements/afficher');
    }

    public function afficher() {
        $data['page_title'] = 'Mes événements';

        $this->load->view('layout/header', $data);
        $this->load->view('evenements/afficher');
        $this->load->view('layout/footer');
    }

}
