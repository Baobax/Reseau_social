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
        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $data['evenements'] = $this->evenements_model->getEvenementsParticipe($BDdata);
        $data['evenementsInvite'] = $this->evenements_model->getEvenementsInvite($BDdata);

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

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        if ($this->form_validation->run() !== FALSE) {
            $BDdata['nom'] = $this->input->post('nom');
            $evenement = $this->evenements_model->verifExistenceNom($BDdata);

            if (!isset($evenement[0])) {
                //Formatage de la date pour la BD, ce qui permettra de supprimer l'événement si sa date est dépassée
                //Passage de d/m/Y à Y/m/d
                $dateTmp = explode('/', $this->input->post('date'));
                $BDdata['date'] = $dateTmp[2] . '/' . $dateTmp[1] . '/' . $dateTmp[0];
                $BDdata['type'] = $this->input->post('type');
                $BDdata['lieu'] = $this->input->post('lieu');
                $this->evenements_model->creerEvenement($BDdata);
                $this->session->set_flashdata('message', '<div class="alert alert-success">L\'événement a bien été créé.</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Ce nom d\'événement existe déjà.</div>');
            }
        }

        $data['page_title'] = 'Mes événements';
        $data['evenements'] = $this->evenements_model->getEvenementsParticipe($BDdata);
        $data['evenementsInvite'] = $this->evenements_model->getEvenementsInvite($BDdata);

        $this->load->view('layout/header', $data);
        $this->load->view('evenements/afficher');
        $this->load->view('layout/footer');
    }

    public function rechercher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['recherche'] = $this->input->post('nom');
        $resultat = $this->evenements_model->getResultatRecherche($BDdata);

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

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['nom'] = urldecode($nom);
        $this->evenements_model->participerEvenement($BDdata);

        redirect('evenements/afficher');
    }

    public function nePlusParticiper($nom) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['nom'] = urldecode($nom);
        $this->evenements_model->nePlusParticiperEvenement($BDdata);

        redirect('evenements/afficher');
    }

    public function page($nom) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        //Je passe en argument le login de l'user pour être sur qu'il participe bien à l'événement
        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['nom'] = urldecode($nom);
        $evenement = $this->evenements_model->getEvenement($BDdata);

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

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['recherche'] = $this->input->post('recherche');
        $BDdata['nomEvenement'] = $this->input->post('nomEvenement');
        $this->load->model('amis_model');
        $resultat = $this->amis_model->rechercherAmiPourInviterEvenement($BDdata);

        if (isset($resultat[0])) {
            $return[] = '<ul>';
            foreach ($resultat as $ami) {
                $return[] = '<li>' . $ami[0]['prenom'] . ' ' . $ami[0]['nom'] . '<a href="' . base_url('evenements/demanderAParticiper/') . $ami[0]['login'] . '/' . $BDdata['nomEvenement'] . '" title="Lui demander de participer"> <i class="fa fa-sign-in"></i></a></li>';
            }
            $return[] = '</ul>';

            echo json_encode($return);
        } else {
            echo json_encode('Pas de résultat');
        }
    }

    public function demanderAParticiper($loginAmi, $nomEvenement) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['loginAmi'] = urldecode($loginAmi);
        $BDdata['nomEvenement'] = urldecode($nomEvenement);
        $this->evenements_model->demanderAParticiper($BDdata);

        redirect('evenements/page/' . $nomEvenement);
    }

    public function accepterInvitation($nomEvenement) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['nomEvenement'] = urldecode($nomEvenement);
        $this->evenements_model->accepterInvitation($BDdata);

        redirect('evenements/afficher');
    }

    public function refuserInvitation($nomEvenement) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $BDdata['monLogin'] = $this->session->userdata('user_login');
        $BDdata['nomEvenement'] = urldecode($nomEvenement);
        $this->evenements_model->refuserInvitation($BDdata);

        redirect('evenements/afficher');
    }

}
