<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Groupes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('groupes_model');
    }

    public function index() {
        redirect('groupes/afficher');
    }

    public function afficher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        $data['page_title'] = 'Mes groupes';
        $data['groupesAdmin'] = $this->groupes_model->getGroupesAdmin($this->session->userdata('user_login'));
        $data['groupesMembre'] = $this->groupes_model->getGroupesMembre($this->session->userdata('user_login'));
        $data['demandesIntegration'] = $this->groupes_model->getDemandesIntegration($this->session->userdata('user_login'));
        $data['etatDemandesGroupes'] = $this->groupes_model->getEtatDemandes($this->session->userdata('user_login'));
        $data['demandesAccepteesEtRefusees'] = $this->groupes_model->getDemandesAccepteesEtRefusees($this->session->userdata('user_login'));

        $this->load->view('layout/header', $data);
        $this->load->view('groupes/afficher');
        $this->load->view('layout/footer');
    }

    public function page($label) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        //Je passe en argument le login de l'user pour être sur qu'il appartient bien au groupe
        $groupe = $this->groupes_model->getGroupe($this->session->userdata('user_login'), urldecode($label));

        //Je vérifie s'il appartient bien au groupe
        if (isset($groupe[0])) {
            $data['page_title'] = 'Mes groupes';
            $data['groupe'] = $groupe;

            $this->load->view('layout/header', $data);
            $this->load->view('groupes/page');
            $this->load->view('layout/footer');
        } else {
            redirect('groupes/afficher');
        }
    }

    public function creer() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }


        $this->form_validation->set_rules('label', 'Label du groupe', 'required|trim');
        $this->form_validation->set_rules('configuration', 'Configuration du groupe', 'required|trim');

        if ($this->form_validation->run() !== FALSE) {
            $label = $this->input->post('label');
            $configuration = $this->input->post('configuration');

            $groupe = $this->groupes_model->verifExistenceNom($label);

            if (!isset($groupe[0])) {
                $this->groupes_model->creerGroupe($this->session->userdata('user_login'), $label, $configuration);
                $this->session->set_flashdata('message', '<div class="alert alert-success">Le groupe a bien été créé.</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Ce nom de groupe existe déjà.</div>');
            }
        }


        $data['page_title'] = 'Mes groupes';
        $data['groupesAdmin'] = $this->groupes_model->getGroupesAdmin($this->session->userdata('user_login'));
        $data['groupesMembre'] = $this->groupes_model->getGroupesMembre($this->session->userdata('user_login'));
        $data['demandesIntegration'] = $this->groupes_model->getDemandesIntegration($this->session->userdata('user_login'));
        $data['etatDemandesGroupes'] = $this->groupes_model->getEtatDemandes($this->session->userdata('user_login'));
        $data['demandesAccepteesEtRefusees'] = $this->groupes_model->getDemandesAccepteesEtRefusees($this->session->userdata('user_login'));

        $this->load->view('layout/header', $data);
        $this->load->view('groupes/afficher');
        $this->load->view('layout/footer');
    }

    public function rechercher() {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $recherche = $this->input->post('nom');
        $resultat = $this->groupes_model->getResultatRecherche($this->session->userdata('user_login'), $recherche);

        if (isset($resultat[0])) {
            $return[] = '<ul>';
            foreach ($resultat as $groupe) {
                if ($groupe[0]['configuration'] == 'ouvert') {
                    $return[] = '<li>' . $groupe[0]['label'] . ' (groupe ouvert) <a href="' . base_url('groupes/rejoindre/') . $groupe[0]['label'] . '" title="Rejoindre"><i class="fa fa-plus"></i></a></li>';
                } else {
                    $return[] = '<li>' . $groupe[0]['label'] . ' (groupe fermé) <a href="' . base_url('groupes/envoyerDemande/') . $groupe[0]['label'] . '" title="Envoyer une demande"><i class="fa fa-plus"></i></a></li>';
                }
            }
            $return[] = '</ul>';

            echo json_encode($return);
        } else {
            echo json_encode('Pas de résultat');
        }
    }

    public function rejoindre($labelGroupe) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->groupes_model->rejoindreGroupe($this->session->userdata('user_login'), urldecode($labelGroupe));

        redirect('groupes/afficher');
    }

    public function envoyerDemande($labelGroupe) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->groupes_model->demanderRejoindreGroupe($this->session->userdata('user_login'), urldecode($labelGroupe));

        redirect('groupes/afficher');
    }

    public function accepterDemande($loginPersonne, $labelGroupe) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->groupes_model->accepterDemande($this->session->userdata('user_login'), urldecode($loginPersonne), urldecode($labelGroupe));

        redirect('groupes/afficher');
    }

    public function refuserDemande($loginPersonne, $labelGroupe) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->groupes_model->refuserDemande($this->session->userdata('user_login'), urldecode($loginPersonne), urldecode($labelGroupe));

        redirect('groupes/afficher');
    }

    public function quitterGroupe($label) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->groupes_model->quitterGroupe($this->session->userdata('user_login'), urldecode($label));

        redirect('groupes/afficher');
    }

    public function supprimerGroupe($label) {
        if ($this->session->userdata('user_login') == NULL) {
            redirect('user/connexion');
        }

        $this->groupes_model->supprimerGroupe($this->session->userdata('user_login'), urldecode($label));

        redirect('groupes/afficher');
    }

}
