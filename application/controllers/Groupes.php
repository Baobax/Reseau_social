<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groupes extends CI_Controller {

    public function index() {
        redirect('groupes/afficher');
    }

    public function afficher() {
        $data['page_title'] = 'Mes groupes';

        $this->load->view('layout/header', $data);
        $this->load->view('groupes/afficher');
        $this->load->view('layout/footer');
    }

}
