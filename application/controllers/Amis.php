<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Amis extends CI_Controller {

    public function index() {
        redirect('amis/afficher');
    }

    public function afficher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->load->model('amis_model');
        $amis = $this->amis_model->getListeAmis($this->session->userdata('user_login'));

        $data['page_title'] = 'Amis';
        $data['amis'] = $amis;

        $this->load->view('layout/header', $data);
        $this->load->view('amis/liste');
        $this->load->view('layout/footer');
    }

    public function rechercher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->load->model('amis_model');
        $recherche = $this->input->post('personne');
        $resultat = $this->amis_model->getResultatRecherche($recherche);

        $return[] = '<ul>';
        foreach ($resultat as $personne) {
            $return[] = '<li><a href="' . base_url('amis/ajouter/') . $personne[0]['login'] . '">' . $personne[0]['prenom'] . ' ' . $personne[0]['nom'] . '<i class="fa fa-user-plus"></i></a></li>';
        }
        $return[] = '</ul>';

        echo json_encode($return);
    }

    public function ajouter($login) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        /* $this->load->model('amis_model');
          $this->amis_model->ajouter($login); */

        redirect('amis/afficher');
    }

}
