<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Amis extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('amis_model');
    }

    public function index() {
        redirect('amis/afficher');
    }

    public function afficher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $amis = $this->amis_model->getListeAmis($this->session->userdata('user_login'));
        $demandesAmisRecues = $this->amis_model->getDemandesAmisRecues($this->session->userdata('user_login'));
        $etatDemandesAmis = $this->amis_model->getEtatDemandesAmis($this->session->userdata('user_login'));
        $demandesAmisAccepteesEtRefusees = $this->amis_model->getDemandesAccepteesEtRefusees($this->session->userdata('user_login'));

        $data['page_title'] = 'Amis';
        $data['amis'] = $amis;
        $data['demandesAmis'] = $demandesAmisRecues;
        $data['demandesAmisAccepteesEtRefusees'] = $demandesAmisAccepteesEtRefusees;
        $data['etatDemandesAmis'] = $etatDemandesAmis;

        $this->load->view('layout/header', $data);
        $this->load->view('amis/liste');
        $this->load->view('layout/footer');
    }

    public function page($loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->load->model('user_model');
        $data['page_title'] = 'Page';
        $data['publications'] = $this->user_model->getPublications(urldecode($loginAmi));

        $this->load->view('layout/header', $data);
        $this->load->view('amis/page');
        $this->load->view('layout/footer');
    }

    public function rechercher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $recherche = $this->input->post('personne');
        $resultat = $this->amis_model->getResultatRecherche($this->session->userdata('user_login'), $recherche);

        if (isset($resultat[0])) {
            $return[] = '<ul>';
            foreach ($resultat as $personne) {
                $return[] = '<li><a href="' . base_url('amis/ajouter/') . $personne[0]['login'] . '">' . $personne[0]['prenom'] . ' ' . $personne[0]['nom'] . '<i class="fa fa-user-plus"></i></a></li>';
            }
            $return[] = '</ul>';

            echo json_encode($return);
        } else {
            echo json_encode('Pas de résultat');
        }
    }

    public function ajouter($loginPersonne) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->amis_model->ajouter($this->session->userdata('user_login'), urldecode($loginPersonne));

        redirect('amis/afficher');
    }

    public function accepterDemande($loginPersonne) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->amis_model->accepterDemande($this->session->userdata('user_login'), urldecode($loginPersonne));

        redirect('amis/afficher');
    }

    public function refuserDemande($loginPersonne) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->amis_model->refuserDemande($this->session->userdata('user_login'), urldecode($loginPersonne));

        redirect('amis/afficher');
    }

    public function aimerPublication($idPublication, $loginAmi) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->amis_model->aimerPublication($this->session->userdata('user_login'), urldecode($idPublication));

        redirect('amis/page/' . urldecode($loginAmi));
    }

}
