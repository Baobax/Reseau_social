<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Evenements extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('evenements_model');
    }

    public function index() {
        redirect('evenements/afficher');
    }

    public function afficher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $data['page_title'] = 'Mes événements';
        $data['evenements'] = $this->evenements_model->getEvenementsParticipe($this->session->userdata('user_login'));
        $data['evenementsInvite'] = $this->evenements_model->getEvenementsInvite($this->session->userdata('user_login'));

        $this->load->view('layout/header', $data);
        $this->load->view('evenements/afficher');
        $this->load->view('layout/footer');
    }

    public function creerEvenement() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        $this->form_validation->set_rules('nom', 'Nom', 'required|trim');
        $this->form_validation->set_rules('type', 'Type', 'required|trim');
        $this->form_validation->set_rules('date', 'Date', 'required|trim');
        $this->form_validation->set_rules('lieu', 'Lieu', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $nom = $this->input->post('nom');
            $type = $this->input->post('type');

            //Formatage de la date pour la BD, ce qui permettra de supprimer l'événement si sa date est dépassée
            //Passage de d/m/Y à Y/m/d
            $dateTmp = explode('/', $this->input->post('date'));
            $date = $dateTmp[2] . '/' . $dateTmp[1] . '/' . $dateTmp[0];

            $lieu = $this->input->post('lieu');
            $evenement = $this->evenements_model->verifExistenceNom($nom);

            if (!isset($evenement[0])) {
                $this->evenements_model->creerEvenement($this->session->userdata('user_login'), $nom, $type, $date, $lieu);
                $this->session->set_flashdata('message', '<div class="alert alert-success">L\'événement a bien été créé.</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Ce nom d\'événement existe déjà.</div>');
            }
        }

        $data['page_title'] = 'Mes événements';
        $data['evenements'] = $this->evenements_model->getEvenementsParticipe($this->session->userdata('user_login'));
        $data['evenementsInvite'] = $this->evenements_model->getEvenementsInvite($this->session->userdata('user_login'));

        $this->load->view('layout/header', $data);
        $this->load->view('evenements/afficher');
        $this->load->view('layout/footer');
    }

    public function rechercher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $recherche = $this->input->post('nom');
        $resultat = $this->evenements_model->getResultatRecherche($this->session->userdata('user_login'), $recherche);

        if (isset($resultat[0])) {
            $return[] = '<ul>';
            foreach ($resultat as $evenement) {
                $return[] = '<li>' . $evenement['label'] . '<a href="' . base_url('evenements/participer/') . $evenement['nom'] . '" title="Rejoindre"> <i class="fa fa-plus"></i></a></li>';
            }
            $return[] = '</ul>';

            echo json_encode($return);
        } else {
            echo json_encode('Pas de résultat');
        }
    }

    public function participer($nom) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->evenements_model->participerEvenement($this->session->userdata('user_login'), urldecode($nom));

        redirect('evenements/afficher');
    }

    public function nePlusParticiper($nom) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->evenements_model->nePlusParticiperEvenement($this->session->userdata('user_login'), urldecode($nom));

        redirect('evenements/afficher');
    }

    public function page($nom) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        //Je passe en argument le login de l'user pour être sur qu'il participe bien à l'événement
        $evenement = $this->evenements_model->getEvenement($this->session->userdata('user_login'), urldecode($nom));

        //Je vérifie s'il appartient bien à l'événement
        if (isset($evenement[0])) {
            $data['page_title'] = 'Mes événements';
            $data['evenement'] = $evenement;

            $this->load->view('layout/header', $data);
            $this->load->view('evenements/page');
            $this->load->view('layout/footer');
        } else {
            redirect('evenements/afficher');
        }
    }

    public function rechercherAmiPourInviter() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $recherche = $this->input->post('recherche');
        $nomEvenement = $this->input->post('nomEvenement');
        $this->load->model('amis_model');
        $resultat = $this->amis_model->rechercherAmiPourInviterEvenement($this->session->userdata('user_login'), $recherche, $nomEvenement);

        $return[] = '<ul>';
        foreach ($resultat as $ami) {
            $return[] = '<li>' . $ami[0]['prenom'] . ' ' . $ami[0]['nom'] . '<a href="' . base_url('evenements/demanderAParticiper/') . $ami[0]['login'] . '/' . $nomEvenement . '" title="Lui demander de participer"> <i class="fa fa-sign-in"></i></a></li>';
        }
        $return[] = '</ul>';

        echo json_encode($return);
    }

    public function demanderAParticiper($loginAmi, $nomEvenement) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->evenements_model->demanderAParticiper($this->session->userdata('user_login'), urldecode($loginAmi), urldecode($nomEvenement));

        redirect('evenements/afficher');
    }

    public function accepterInvitation($nomEvenement) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->evenements_model->accepterInvitation($this->session->userdata('user_login'), urldecode($nomEvenement));

        redirect('evenements/afficher');
    }

    public function refuserInvitation($nomEvenement) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->evenements_model->refuserInvitation($this->session->userdata('user_login'), urldecode($nomEvenement));

        redirect('evenements/afficher');
    }

}
